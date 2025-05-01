<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddBenefeciaryRequest;
use App\Models\Barangay;
use App\Models\Benefeciary;
use App\Models\BirthdayData;
use App\Models\MonthlyIncome;
use App\Models\Program;
use App\Models\ProgramBeneficiary;
use App\Models\Pwd;
use App\Models\Requests;
use App\Models\Senior;
use App\Models\TransitionData;
use App\Models\User;
use App\Models\Women;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class FocalController extends Controller
{
    protected $notificationService;

    public function __construct()
    {
        $this->notificationService = new NotificationService();
    }

    // Render Dashbord vue
    public function index()
    {
        $user = auth()->user();
        $beneficiaries = Benefeciary::where('barangay_id', $user->barangay_id)->where('benefeciary_type', $user->focal_role)->where('approved_status', 1)->get();

        $dateNow = Carbon::now();
        $programs = Program::with(['program_beneficiary.beneficiary.barangay'])
            ->withCount('program_beneficiary as beneficiary_count')
            ->where('status', 3)
            ->where('end_date', '>=', $dateNow)
            ->whereHas('program_beneficiary.beneficiary.barangay', function ($query) use ($user) {
                $query
                    ->where('barangay_id', $user->barangay_id)  // Assuming the user's barangay is in their profile
                    ->where('benefeciary_type', $user->focal_role);  // Adjust the column name as needed
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $monthNow = Carbon::now()->format('m');
        $birthdays = Benefeciary::where('barangay_id', $user->barangay_id)
            ->where('benefeciary_type', $user->focal_role)
            ->where('approved_status', 1)
            ->whereRaw('MONTH(date_of_birth) = ?', [$monthNow])  // Filter by current month
            ->get();
        return Inertia::render('Focal/Pages/Dashboard', ['beneficiaries' => $beneficiaries, 'programs' => $programs, 'birthdays' => $birthdays]);
    }

    // Render Beneficiaries vue
    public function beneficiaries(Request $request)
    {
        $searchInput = $request->input('searchInput');
        $monthly_income = MonthlyIncome::get();
        $barangays = Barangay::get();
        $user = auth()->user();
        $focal_barangay = $user->barangay->barangay_name;
        $focal_assignment = '';

        switch ($user->focal_role) {
            case 1:
                $focal_assignment = 'senior';
                break;
            case 2:
                $focal_assignment = 'pwd';
                break;
            case 3:
                $focal_assignment = 'women';
                break;
        }

        $benefeciaries = Benefeciary::with([$focal_assignment, 'barangay'])
            ->when($searchInput, function ($query, $search) {
                $query
                    ->search('id', $search)
                    ->search('first_name', $search)
                    ->search('last_name', $search)
                    ->search('middle_name', $search);
            })
            ->where('benefeciary_type', $user->focal_role)
            ->where('barangay_id', $user->barangay_id)
            ->whereIn('approved_status', [1, 2])
            ->paginate(20);

        $dateNow = Carbon::now()->format('d/m/Y');
        // dd($benefeciaries);
        return Inertia::render('Focal/Pages/Beneficiaries', ['benefeciaries' => $benefeciaries, 'monthly_income' => $monthly_income, 'focal_barangay' => $focal_barangay, 'barangays' => $barangays, 'dateNow' => $dateNow]);
    }

    // Render Programs vue
    public function programs(Request $request)
    {
        $user = auth()->user();
        $searchInput = $request->input('searchInput');
        $programs = Program::with(['program_beneficiary.beneficiary.barangay'])
            ->where('status', 3)
            ->whereHas('program_beneficiary.beneficiary.barangay', function ($query) use ($user) {
                $query
                    ->where('barangay_id', $user->barangay_id)  // Assuming the user's barangay is in their profile
                    ->where('benefeciary_type', $user->focal_role);  // Adjust the column name as needed
            })
            ->when($searchInput, function ($query, $search) {
                $query
                    ->search('title', $search)
                    ->search('purpose', $search)
                    ->search('venue', $search);
            })
            ->orderBy('created_at', 'desc')
            ->get();
        // dd($programs);
        return Inertia::render('Focal/Pages/Programs', ['programs' => $programs]);
    }

    public function program_info($id)
    {
        $user = auth()->user();
        $program = Program::with('program_beneficiary')->where('id', $id)->first();
        $program_beneficiaries = ProgramBeneficiary::with('beneficiary')
            ->whereHas('beneficiary', function ($query) use ($user) {
                $query
                    ->where('barangay_id', $user->barangay_id)  // Assuming the user's barangay is in their profile
                    ->where('benefeciary_type', $user->focal_role);  // Adjust the column name as needed
            })
            ->where('program_id', $id)
            ->get();
        $selected_ids = $program_beneficiaries->pluck('benefeciary_id')->toArray();
        $beneficiaries = Benefeciary::with(['pwd', 'women', 'senior', 'barangay'])
            ->whereIn('id', $selected_ids)
            ->get();
        $barangays = Barangay::get();
        $monhtly_income = MonthlyIncome::get();
        return Inertia::render('Focal/Pages/ProgramInfo', ['program' => $program, 'beneficiaries' => $beneficiaries, 'barangays' => $barangays, 'monhtly_income' => $monhtly_income]);
    }

    // Request Group

    // Render New Beneficiary vue
    public function new_beneficiary(Request $request)
    {
        $searchInput = $request->input('searchInput');
        $filterStatus = $request->input('filterStatus');

        $searchInputRequest = $request->input('searchInputRequest');
        $monthly_income = MonthlyIncome::get();
        $barangays = Barangay::get();
        $user = auth()->user();
        $focal_barangay = $user->barangay->barangay_name;
        $focal_assignment = '';

        switch ($user->focal_role) {
            case 1:
                $focal_assignment = 'senior';
                break;
            case 2:
                $focal_assignment = 'pwd';
                break;
            case 3:
                $focal_assignment = 'women';
                break;
        }

        $beneficiaries_in_request = Requests::where('user_id', auth()->user()->id)->get(['benefeciary_id']);
        $beneficiary_ids = $beneficiaries_in_request->pluck('benefeciary_id');

        $benefeciaries = Benefeciary::with([$focal_assignment, 'barangay'])
            ->when($searchInput, function ($query, $search) {
                $query
                    ->search('id', $search)
                    ->search('first_name', $search)
                    ->search('last_name', $search)
                    ->search('middle_name', $search);
            })
            ->where('benefeciary_type', $user->focal_role)
            ->where('barangay_id', $user->barangay_id)
            ->where('approved_status', 0)
            ->whereNotIn('id', $beneficiary_ids)
            ->paginate(20);

        $dateNow = Carbon::now()->format('d/m/Y');

        $made_requests = Requests::with('benefeciary')
            ->where('user_id', auth()->user()->id)
            ->when($searchInputRequest, function ($query, $searchInputRequest) {
                $query->whereHas('benefeciary', function ($q) use ($searchInputRequest) {
                    $q
                        ->where('first_name', 'LIKE', '%' . $searchInputRequest . '%')
                        ->orWhere('last_name', 'LIKE', '%' . $searchInputRequest . '%');
                });
            })
            ->when($filterStatus != "", function ($query) use ($filterStatus) {
                $query->where('status', $filterStatus);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        // dd($made_requests);
        return Inertia::render('Focal/Pages/Requests/NewBeneficiary', ['benefeciaries' => $benefeciaries, 'monthly_income' => $monthly_income, 'focal_barangay' => $focal_barangay, 'barangays' => $barangays, 'dateNow' => $dateNow, 'made_requests' => $made_requests, 'filterStatus' => $filterStatus]);
    }

    // Render Birhtday vue
    public function birthday(Request $request)
    {
        $searchReceivedInput = $request->input('searchReceivedInput');
        $searchInput = $request->input('searchInput');
        $user = auth()->user();
        $focal_barangay = $user->barangay->barangay_name;
        // dd($focal_barangay);
        $monthNow = Carbon::now()->format('m');
        $yearNow = Carbon::now()->format('Y');

        $focal_assignment = '';

        switch ($user->focal_role) {
            case 1:
                $focal_assignment = 'senior';
                break;
            case 2:
                $focal_assignment = 'pwd';
                break;
            case 3:
                $focal_assignment = 'women';
                break;
        }

        $endorsed_benefeciaries = BirthdayData::with('benefeciary')->where('barangay_id', $user->barangay_id)->where('month', $monthNow)->where('year', $yearNow)->whereIn('status', [0, 2])->get();

        $endorsed_ids = $endorsed_benefeciaries->pluck('benefeciary_id')->toArray();

        $received_gifts_benefeciaries = BirthdayData::with('benefeciary')
            ->whereHas('benefeciary', function ($query) use ($searchReceivedInput) {
                $query->when($searchReceivedInput, function ($q) use ($searchReceivedInput) {
                    $q
                        ->where('id', 'LIKE', '%' . $searchReceivedInput . '%')
                        ->orWhere('first_name', 'LIKE', '%' . $searchReceivedInput . '%')
                        ->orWhere('last_name', 'LIKE', '%' . $searchReceivedInput . '%')
                        ->orWhere('middle_name', 'LIKE', '%' . $searchReceivedInput . '%');
                });
            })
            ->where('status', 1)
            ->where('barangay_id', $user->barangay_id)
            ->WhereHas('benefeciary', function ($query) use ($user) {
                $query->where('benefeciary_type', $user->focal_role);
            })
            ->get();

        // dd($received_gifts_benefeciaries);

        $received_gifts_this_year = BirthdayData::with('benefeciary')
            ->where('status', 1)
            ->where('year', $yearNow)
            ->get(['benefeciary_id']);

        $received_gifts_this_year_ids = $received_gifts_this_year->pluck('benefeciary_id')->toArray();

        $benefeciaries = Benefeciary::with([$focal_assignment, 'barangay'])
            ->when($searchInput, function ($query, $search) {
                $query
                    ->search('id', $search)
                    ->search('first_name', $search)
                    ->search('last_name', $search)
                    ->search('middle_name', $search);
            })
            ->where('benefeciary_type', $user->focal_role)
            ->where('barangay_id', $user->barangay_id)
            ->where('approved_status', 1)
            ->whereRaw('MONTH(date_of_birth) = ?', [$monthNow])
            ->whereNotIn('id', $endorsed_ids)
            ->whereNotIn('id', $received_gifts_this_year_ids)
            ->get();

        // dd($benefeciaries);

        return Inertia::render('Focal/Pages/Requests/Birthday', ['focal_barangay' => $focal_barangay, 'benefeciaries' => $benefeciaries, 'endorsed_benefeciaries' => $endorsed_benefeciaries, 'received_gifts_benefeciaries' => $received_gifts_benefeciaries]);
    }

    // Render Transitioning vue
    // commong transition (women and pwd)
    public function transitioning(Request $request)
    {
        $searchInput = $request->input('searchInput');
        $user = auth()->user();
        $focal_barangay = $user->barangay->barangay_name;

        $endorsed_benefeciaries = TransitionData::with('benefeciary')->where('barangay_id', $user->barangay_id)->whereIn('status', [0, 1, 3])->where('from_benefeciary_type', $user->focal_role)->get();

        $transitioned_benefeciaries = TransitionData::with('benefeciary')->where('barangay_id', $user->barangay_id)->where('status', 2)->where('from_benefeciary_type', $user->focal_role)->get();

        $endorsed_ids = $endorsed_benefeciaries->pluck('benefeciary_id')->toArray();
        $transitioned_ids = $transitioned_benefeciaries->pluck('benefeciary_id')->toArray();

        $merged_ids = array_merge($endorsed_ids, $transitioned_ids);

        $benefeciaries = Benefeciary::where('age', '>=', 60)
            ->where('benefeciary_type', $user->focal_role)
            ->where('barangay_id', $user->barangay_id)
            ->where('approved_status', 1)
            ->whereNotIn('id', $merged_ids)
            ->when($searchInput, function ($query, $search) {
                $query
                    ->search('id', $search)
                    ->search('first_name', $search)
                    ->search('last_name', $search)
                    ->search('middle_name', $search);
            })
            ->get();

        if (auth()->user()->focal_role == 1) {
            return redirect()->route('focal.senior_transitioning');
        } else {
            return Inertia::render('Focal/Pages/Requests/Transitioning', ['focal_barangay' => $focal_barangay, 'benefeciaries' => $benefeciaries, 'endorsed_benefeciaries' => $endorsed_benefeciaries, 'transitioned_benefeciaries' => $transitioned_benefeciaries]);
        }
    }

    // Render Senior Transition
    public function senior_transitioning(Request $request)
    {
        $monthly_income = MonthlyIncome::get();
        $barangays = Barangay::get();
        $searchInput = $request->input('searchInput');
        $user = auth()->user();

        $focal_barangay = $user->barangay->barangay_name;

        $endorsed_benefeciaries = TransitionData::with(['benefeciary', 'benefeciary.senior', 'benefeciary.women', 'benefeciary.pwd'])->where('barangay_id', $user->barangay_id)->whereIn('status', [0, 1, 3])->get();

        $transitioned_benefeciaries = TransitionData::with(['benefeciary', 'benefeciary.senior', 'benefeciary.women', 'benefeciary.pwd'])->where('barangay_id', $user->barangay_id)->where('status', 2)->get();

        if (auth()->user()->focal_role != 1) {
            return redirect()->route('focal.transitioning');
        } else {
            return Inertia::render('Focal/Pages/Requests/SeniorTransitioning', ['endorsed_benefeciaries' => $endorsed_benefeciaries, 'transitioned_benefeciaries' => $transitioned_benefeciaries, 'monthly_income' => $monthly_income, 'barangays' => $barangays]);
        }
    }

    public function application()
    {
        $user = auth()->user();
        $applicants = Benefeciary::with(['pwd', 'senior', 'women'])->where('benefeciary_type', $user->focal_role)->where('barangay_id', $user->barangay_id)->where('approved_status', 3)->get();
        $rejected_applicants = Benefeciary::with(['pwd', 'senior', 'women'])->where('benefeciary_type', $user->focal_role)->where('barangay_id', $user->barangay_id)->where('approved_status', 4)->get();
        $monthly_income = MonthlyIncome::get();
        $barangays = Barangay::get();
        $user = auth()->user();
        $focal_barangay = $user->barangay->barangay_name;

        return Inertia::render('Focal/Pages/Requests/Application', [
            'applicants' => $applicants,
            'rejected_applicants' => $rejected_applicants,
            'monthly_income' => $monthly_income,
            'barangays' => $barangays,
            'focal_barangay' => $focal_barangay,
        ]);
    }

    // End Request Group

    // Render Report vue
    public function report()
    {
        $role = auth()->user()->role;
        return Inertia::render('Focal/Pages/Report', ['role' => $role]);
    }

    public function createBenefeciary(AddBenefeciaryRequest $request)
    {
        // Case-insensitive duplication check
        $duplicate = Benefeciary::whereRaw('LOWER(first_name) = ?', [strtolower($request->first_name)])
            ->whereRaw('LOWER(last_name) = ?', [strtolower($request->last_name)])
            ->where('date_of_birth', $request->date_of_birth)
            ->first();

        if ($duplicate) {
            return redirect()->back()->with('error', 'Beneficiary already exists.');
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '-' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $path = $file->move('profile_images', $filename);
        } else {
            $filename = '';
        }

        // Create the beneficiary record
        $benefeciary_values = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'date_of_birth' => $request->date_of_birth,
            'age' => $request->age,
            'civil_status' => $request->civil_status,
            'sex' => $request->sex,
            'religion' => $request->religion,
            'occupation' => $request->occupation,
            'educational_attainment' => $request->educational_attainment,
            'monthly_income_id' => $request->monthly_income,
            'contact_number' => $request->contact_number,
            'city' => $request->city,
            'province' => $request->province,
            'region' => $request->region,
            'barangay_id' => $request->barangay_id,
            'street' => $request->street,
            'remarks' => $request->remarks,
            'image' => $filename,
            'pantawid_benefeciary' => $request->pantawid_benefeciary,
            'indigenous_person' => $request->indigenous_person,
            'lgbtq' => $request->lgbtq,
            'benefeciary_type' => $request->benefeciary_type
        ];

        $benefeciary = Benefeciary::create($benefeciary_values);

        // Create specific beneficiary type record
        switch ($request->benefeciary_type) {
            case 1:
                $senior_values = [
                    'benefeciary_id' => $benefeciary->id,
                    'socpen_benefeciary' => $request->socpen_benefeciary,
                    'senior_id_number' => $request->senior_id_number,
                    'date_sic_issued' => $request->date_sic_issued,
                    'sic_status' => $request->sic_status
                ];
                Senior::create($senior_values);
                break;

            case 2:
                $pwd_values = [
                    'benefeciary_id' => $benefeciary->id,
                    'pwd_id_number' => $request->pwd_id_number,
                    'date_pic_issued' => $request->date_pic_issued,
                    'pic_status' => $request->pic_status
                ];
                Pwd::create($pwd_values);
                break;

            case 3:
                $women_values = [
                    'benefeciary_id' => $benefeciary->id,
                    'kalipi_id_number' => $request->kalipi_id_number,
                    'date_kic_issued' => $request->date_kic_issued,
                    'kic_status' => $request->kic_status
                ];
                Women::create($women_values);
                break;
        }

        return redirect()->back()->with('success', 'Beneficiary Added!');
    }

    public function UpdateBenefeciary(Request $request, $id)
    {
        try {
            $old_benefeciary_image = Benefeciary::where('id', $id)->first('image')->image;
            if ($request->hasFile('image')) {
                $file = $request->file('image');

                $filename = time() . '.' . $file->getClientOriginalExtension();

                $image_data = public_path('profile_images/' . $old_benefeciary_image);
                if (!empty($old_benefeciary_image) && file_exists($image_data)) {
                    unlink($image_data);
                }

                $path = $file->move('profile_images', $filename);
            } else {
                $filename = $old_benefeciary_image;
            }

            $benefeciary_values = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'date_of_birth' => $request->date_of_birth,
                'age' => $request->age,
                'civil_status' => $request->civil_status,
                'sex' => $request->sex,
                'religion' => $request->religion,
                'occupation' => $request->occupation,
                'educational_attainment' => $request->educational_attainment,
                'monthly_income_id' => $request->monthly_income,
                'contact_number' => $request->contact_number,
                'city' => $request->city,
                'province' => $request->province,
                'region' => $request->region,
                'barangay_id' => $request->barangay_id,
                'street' => $request->street,
                'remarks' => $request->remarks,
                'image' => $filename,
                'pantawid_benefeciary' => $request->pantawid_benefeciary,
                'indigenous_person' => $request->indigenous_person,
                'lgbtq' => $request->lgbtq,
                'benefeciary_type' => $request->benefeciary_type
            ];

            Benefeciary::where('id', $id)->update($benefeciary_values);

            $doesHaveSeniorInfo = Senior::where('benefeciary_id', $id)->count();

            switch ($request->benefeciary_type) {
                case 1:
                    $senior_values = [
                        'benefeciary_id' => $id,
                        'socpen_benefeciary' => $request->socpen_benefeciary,
                        'senior_id_number' => $request->senior_id_number,
                        'date_sic_issued' => $request->date_sic_issued,
                        'sic_status' => $request->sic_status
                    ];

                    if ($doesHaveSeniorInfo > 0) {
                        Senior::where('benefeciary_id', $id)->update($senior_values);
                    } else {
                        Senior::create($senior_values);
                    }
                    break;

                case 2:
                    $pwd_values = [
                        'pwd_id_number' => $request->pwd_id_number,
                        'date_pic_issued' => $request->date_pic_issued,
                        'pic_status' => $request->pic_status
                    ];

                    Pwd::where('benefeciary_id', $id)->update($pwd_values);
                    break;

                case 3:
                    $women_values = [
                        'kalipi_id_number' => $request->kalipi_id_number,
                        'date_kic_issued' => $request->date_kic_issued,
                        'kic_status' => $request->kic_status
                    ];
                    // dd($women_values);
                    Women::where('benefeciary_id', $id)->update($women_values);
                    break;
            }

            return redirect()->back()->with('success', 'Benefeciary Data Updated!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function deleteBenefeciary($id, $type)
    {
        $old_user_image = Benefeciary::where('id', $id)->first('image')->image;
        $oldFilePath = public_path('profile_images/' . $old_user_image);
        if (File::exists($oldFilePath)) {
            File::delete($oldFilePath);
        }
        Benefeciary::where('id', $id)->delete();

        switch ($type) {
            case 1:
                Senior::where('benefeciary_id', $id)->delete();
                break;
            case 2:
                Pwd::where('benefeciary_id', $id)->delete();
                break;
            case 3:
                Women::where('benefeciary_id', $id)->delete();
                break;
        }

        return redirect()->back()->with('success', 'Benefeciary Deleted!');
    }

    public function createRequest(Request $request)
    {
        // dd($request->all());
        $request->validate(
            [
                'beneficiaries' => 'required'
            ],
            [
                'beneficiaries.required' => 'Select at least 1 person'
            ]
        );

        try {
            foreach ($request->beneficiaries as $benefeciary_id) {
                Requests::create([
                    'user_id' => $request->user_id,
                    'benefeciary_id' => $benefeciary_id,
                    'barangay_id' => $request->barangay_id,
                ]);
            }

            $barangay_personnel = User::where('role', 1)->where('barangay_id', $request->barangay_id)->get();

            $details = [
                'type' => 0,
                'from' => auth()->user()->name,
                'image' => auth()->user()->user_image,
                'message' => 'Submitted a new beneficiary request',
                'url' => route('barangay.new_beneficiary')
            ];

            $this->notificationService->sendNotification($barangay_personnel, $details);

            return redirect()->back()->with('success', 'Request Created!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function editRequest(Request $request, $id)
    {
        $request->validate(
            [
                'request_name' => 'required',
                'beneficiaries' => 'required'
            ],
            [
                'beneficiaries.required' => 'Select at least 1 person'
            ]
        );

        try {
            $beneficiaries = json_encode($request->beneficiaries);

            $values = [
                'name' => $request->request_name,
                'beneficiaries' => $beneficiaries,
            ];
            // dd($values);
            Requests::where('id', $id)->update($values);
            return redirect()->back()->with('success', 'Request Updated!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function deleteRequest($id)
    {
        try {
            Requests::where('id', $id)->delete();

            $barangay_personnel = User::where('role', 1)->where('barangay_id', auth()->user()->barangay_id)->get();

            $details = [
                'type' => 0,
                'from' => auth()->user()->name,
                'image' => auth()->user()->user_image,
                'message' => 'Cancelled the endorsement request',
                'url' => route('barangay.new_beneficiary')
            ];

            $this->notificationService->sendNotification($barangay_personnel, $details);

            return redirect()->back()->with('success', 'Endorsement Cancelled!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function endorseBeneficiary(Request $request)
    {
        try {
            foreach ($request->beneficiaries as $beneficiary) {
                $values = [
                    'user_id' => auth()->user()->id,
                    'benefeciary_id' => $beneficiary,
                    'from_benefeciary_type' => $request->from_benefeciary_type,
                    'barangay_id' => $request->barangay_id,
                    'status' => 0
                ];

                TransitionData::create($values);
            }

            $senior_focal = User::where('focal_role', 1)->where('barangay_id', $request->barangay_id)->get();

            $details = [
                'type' => 1,
                'from' => auth()->user()->name,
                'image' => auth()->user()->user_image,
                'message' => 'Endorse a beneficiary for transitioning',
                'url' => route('focal.transitioning')
            ];

            $this->notificationService->sendNotification($senior_focal, $details);

            return redirect()->back()->with('success', 'Beneficiaries Endorsed!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function endorseBirthdayBeneficiary(Request $request)
    {
        try {
            $month = Carbon::now()->format('m');
            $year = Carbon::now()->format('Y');

            foreach ($request->beneficiaries as $beneficiary) {
                $values = [
                    'user_id' => auth()->user()->id,
                    'benefeciary_id' => $beneficiary,
                    'barangay_id' => $request->barangay_id,
                    'month' => $month,
                    'year' => $year,
                ];

                BirthdayData::create($values);
            }

            $details = [
                'type' => 3,
                'from' => auth()->user()->name,
                'image' => auth()->user()->user_image,
                'message' => 'Endorsed a birthday celebrant for this month.',
                'url' => route('admin.request.birthdays')
            ];

            $admins = User::where('role', 3)->get();
            $this->notificationService->sendNotification($admins, $details);

            // dd($admins, $query);
            return redirect()->back()->with('success', 'Celebrants Endorsed!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function cancelBirthdayEndorseBeneficiary($id)
    {
        try {
            BirthdayData::where('id', $id)->delete();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function retryBirthdayEndorseBeneficiary($id)
    {
        try {
            BirthdayData::where('id', $id)->update(['status' => 0]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function request_info(Request $request, $id)
    {
        $request_info = Requests::with('user')->where('id', $id)->first();
        $beneficiary_ids = json_decode($request_info->beneficiaries);
        // dd($beneficiary_ids);
        $monthly_income = MonthlyIncome::get();
        $barangays = Barangay::get();
        $searchInput = $request->input('searchInput');
        $user = auth()->user();
        $focal_role = '';

        switch ($request_info->user->focal_role) {
            case 1:
                $focal_role = 'senior';
            case 2:
                $focal_role = 'pwd';
            case 3:
                $focal_role = 'women';
        }
        $benefeciaries = Benefeciary::with([$focal_role, 'barangay'])
            ->when($searchInput, function ($query, $search) {
                $query
                    ->search('id', $search)
                    ->search('first_name', $search)
                    ->search('last_name', $search)
                    ->search('middle_name', $search);
            })
            ->whereIn('id', $beneficiary_ids)
            ->get();
        // dd($benefeciaries);
        return Inertia::render('Focal/Pages/Requests/RequestInfo', ['benefeciaries' => $benefeciaries, 'barangays' => $barangays, 'monthly_income' => $monthly_income, 'requestID' => $id]);
    }

    public function respondTransition($id, $status)
    {
        try {
            $message = '';
            $beneficiary_id = TransitionData::where('id', $id)->first(['benefeciary_id'])->benefeciary_id;
            $user_id = TransitionData::where('id', $id)->first('user_id')->user_id;

            $focal = User::where('id', $user_id)->first(['barangay_id', 'focal_role']);

            $barangay_personnel = User::where('role', 1)->where('barangay_id', $focal->barangay_id)->get();

            $focal_personnel = User::where('focal_role', $focal->focal_role)->where('barangay_id', $focal->barangay_id)->get();

            switch ($status) {
                case 1:
                    $message = 'Request Forwarded!';
                    TransitionData::where('id', $id)->update(['status' => $status]);
                    $for_focal_details = [
                        'type' => 1,
                        'from' => auth()->user()->name,
                        'image' => auth()->user()->user_image,
                        'message' => 'Forwarded the request beneficiary for transitioning',
                        'url' => route('focal.transitioning')
                    ];

                    $for_barangay_details = [
                        'type' => 1,
                        'from' => auth()->user()->name,
                        'image' => auth()->user()->user_image,
                        'message' => 'Forwarded the request beneficiary for transitioning',
                        'url' => route('barangay.transitioning')
                    ];

                    break;
                case 3:
                    $message = 'Request Rejected!';
                    TransitionData::where('id', $id)->update(['status' => $status, 'date_approved' => $dateNow]);
                    $for_focal_details = [
                        'type' => 1,
                        'from' => auth()->user()->name,
                        'image' => auth()->user()->user_image,
                        'message' => 'Rejected the request for beneficiary for transitioning',
                        'url' => route('focal.transitioning')
                    ];
                    $for_barangay_details = [
                        'type' => 1,
                        'from' => auth()->user()->name,
                        'image' => auth()->user()->user_image,
                        'message' => 'Rejected the request for beneficiary for transitioning',
                        'url' => route('barangay.transitioning')
                    ];
                    break;
            }

            $this->notificationService->sendNotification($focal_personnel, $for_focal_details);
            $this->notificationService->sendNotification($barangay_personnel, $for_barangay_details);

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function respondApplication(Request $request)
    {
        try {
            $user = Benefeciary::where('id', $request->id)->first(['user_id']);

            $beneficiary_user = User::where('id', $user->user_id)->first();

            switch ($request->status) {
                case 0:
                    Benefeciary::where('id', $request->id)->update(['approved_status' => 0, 'date_approved' => now()]);

                    $for_applicant_details = [
                        'type' => 0,
                        'from' => auth()->user()->name,
                        'image' => auth()->user()->user_image,
                        'message' => 'Approved your application',
                        'url' => ''
                    ];

                    $this->notificationService->sendNotification($beneficiary_user, $for_applicant_details);

                    return redirect()->back()->with('success', 'Application Approved!');
                    break;
                case 4:
                    Benefeciary::where('id', $request->id)->update(['approved_status' => 4, 'date_rejected' => now(), 'reason' => $request->reason]);

                    $for_applicant_details = [
                        'type' => 0,
                        'from' => auth()->user()->name,
                        'image' => auth()->user()->user_image,
                        'message' => 'Declined your application. Go to your barangay assigned focal for more information.',
                        'url' => ''
                    ];

                    $this->notificationService->sendNotification($beneficiary_user, $for_applicant_details);
                    return redirect()->back()->with('success', 'Application Declined!');
                    break;
                case 3:
                    Benefeciary::where('id', $request->id)->update(['approved_status' => 3, 'date_rejected' => null, 'reason' => null]);
                    $for_applicant_details = [
                        'type' => 0,
                        'from' => auth()->user()->name,
                        'image' => auth()->user()->user_image,
                        'message' => 'Cancelled the rejection of your application',
                        'url' => ''
                    ];

                    $this->notificationService->sendNotification($beneficiary_user, $for_applicant_details);
                    return redirect()->back()->with('success', 'Rejection Cancelled!');
                    break;
                case 5:
                    User::where('id', $user->user_id)->delete();
                    Benefeciary::where('id', $request->id)->delete();
                    Pwd::where('benefeciary_id', $request->id)->delete();
                    Senior::where('benefeciary_id', $request->id)->delete();
                    Women::where('benefeciary_id', $request->id)->delete();
                    break;
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
