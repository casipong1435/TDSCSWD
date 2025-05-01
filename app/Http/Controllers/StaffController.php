<?php

namespace App\Http\Controllers;

use App\Models\AicsComposition;
use App\Models\AicsGis;
use App\Models\AicsStaff;
use App\Models\AssistanceType;
use App\Models\Barangay;
use App\Models\Benefeciary;
use App\Models\MonthlyIncome;
use App\Models\Program;
use App\Models\ProgramBeneficiary;
use App\Models\Requests;
use App\Models\TransitionData;
use App\Models\User;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StaffController extends Controller
{
    protected $notificationService;

    public function __construct()
    {
        $this->notificationService = new NotificationService();
    }

    // Render Dashboard vue
    public function index()
    {
        $user = auth()->user();

        // Totals
        $totalSeniors = Benefeciary::where('approved_status', 1)
            ->where('benefeciary_type', 1)
            ->count();

        $totalPWDs = Benefeciary::where('approved_status', 1)
            ->where('benefeciary_type', 2)
            ->count();

        $totalWomen = Benefeciary::where('approved_status', 1)
            ->where('benefeciary_type', 3)
            ->count();

        $totalBeneficiaries = Benefeciary::where('approved_status', 1)
            ->count();

        // AICS Beneficiaries by Year
        $currentYear = Carbon::now()->year;
        $years = range(2024, $currentYear);
        $aicsData = [];
        foreach ($years as $year) {
            $aicsData[$year] = [
                'Financial Assistance' => AicsGis::whereYear('date', $year)
                    ->where('type_of_assistance', 1)
                    ->count(),
                'Material Assistance' => AicsGis::whereYear('date', $year)
                    ->where('type_of_assistance', 2)
                    ->count(),
                'Medical Assistance' => AicsGis::whereYear('date', $year)
                    ->where('type_of_assistance', 3)
                    ->count(),
                'Educational Assistance' => AicsGis::whereYear('date', $year)
                    ->where('type_of_assistance', 4)
                    ->count(),
                'Psychosocial Assistance' => AicsGis::whereYear('date', $year)
                    ->where('type_of_assistance', 5)
                    ->count(),
            ];
        }

        // Ongoing Programs
        $ongoingPrograms = Program::withCount('program_beneficiary')
            ->where('status', 3)  // Active status
            ->where('end_date', '>=', now())
            ->get();

        $barangays = Barangay::withCount([
            'benefeciary as seniors_count' => function ($query) {
                $query->where('approved_status', 1)->where('benefeciary_type', 1);
            },
            'benefeciary as pwds_count' => function ($query) {
                $query->where('approved_status', 1)->where('benefeciary_type', 2);
            },
            'benefeciary as women_count' => function ($query) {
                $query->where('approved_status', 1)->where('benefeciary_type', 3);
            },
        ])->get();

        return Inertia::render('Staff/Pages/Dashboard', compact(
            'totalSeniors',
            'totalPWDs',
            'totalWomen',
            'totalBeneficiaries',
            'aicsData',
            'ongoingPrograms',
            'barangays'
        ));
    }

    // Render Programs vue
    public function programs(Request $request)
    {
        $searchInput = $request->input('searchInput');
        $searchInputRequest = $request->input('searchInputRequest');
        $program_requests = Program::whereIn('status', [0, 4])
            ->when($searchInput, function ($query, $search) {
                $query
                    ->where('title', 'LIKE', '%' . $search . '%')
                    ->orWhere('purpose', 'LIKE', '%' . $search . '%')
                    ->orWhere('venue', 'LIKE', '%' . $search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->get();
        $approved_programs = Program::with('program_beneficiary')
            ->whereIn('status', [1, 2, 3])
            ->when($searchInputRequest, function ($query, $search) {
                $query
                    ->where('title', 'LIKE', '%' . $search . '%')
                    ->orWhere('purpose', 'LIKE', '%' . $search . '%')
                    ->orWhere('venue', 'LIKE', '%' . $search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->get();
        return Inertia::render('Staff/Pages/Programs', ['program_requests' => $program_requests, 'approved_programs' => $approved_programs]);
    }

    // Render Program Info

    public function program_info(Request $request, $id)
    {
        $searchInput = $request->input('searchInput');
        $barangay_filter = $request->input('barangay_filter');
        $beneficiary_filter = $request->input('beneficiary_filter');
        $program = Program::where('id', $id)->first();
        $program_beneficiaries = ProgramBeneficiary::with(['beneficiary', 'beneficiary.pwd', 'beneficiary.women', 'beneficiary.senior', 'beneficiary.barangay'])
            ->where('program_id', $id)
            ->whereHas('beneficiary', function ($query) use ($searchInput) {
                $query->when($searchInput, function ($query) use ($searchInput) {
                    $query
                        ->where('first_name', 'LIKE', '%' . $searchInput . '%')
                        ->orWhere('last_name', 'LIKE', '%' . $searchInput . '%')
                        ->orWhere('middle_name', 'LIKE', '%' . $searchInput . '%')
                        ->orWhere('age', 'LIKE', '%' . $searchInput . '%')
                        ->orWhere('sex', 'LIKE', '%' . $searchInput . '%')
                        ->orWhere('civil_status', 'LIKE', '%' . $searchInput . '%')
                        ->orWhere('educational_attainment', 'LIKE', '%' . $searchInput . '%')
                        ->orWhere('contact_number', 'LIKE', '%' . $searchInput . '%');
                });
            })
            ->get();

        $selected_ids = $program_beneficiaries->pluck('benefeciary_id')->toArray();

        $beneficiaries = Benefeciary::with(['pwd', 'women', 'senior', 'barangay'])
            ->whereNotIn('id', $selected_ids)
            ->when($beneficiary_filter ?? null, function ($query, $beneficiary_filter) {
                $query->whereIn('benefeciary_type', $beneficiary_filter);
            })
            ->when($barangay_filter ?? null, function ($query, $barangay_filter) {
                $query->whereIn('barangay_id', $barangay_filter);
            })
            ->get();
        $barangays = Barangay::get();
        $monthly_income = MonthlyIncome::get();
        return Inertia::render('Staff/Pages/ProgramInfo', ['program' => $program, 'beneficiaries' => $beneficiaries, 'program_beneficiaries' => $program_beneficiaries, 'barangays' => $barangays, 'monthly_income' => $monthly_income]);
    }

    // Beneficiaries Group

    // Render Aics vue
    public function aics(Request $request)
    {
        $searchInputRequest = $request->input('searchInputRequest');
        $aics_staff = AicsStaff::whereNot('role', 0)->get();
        $aics_mayor = AicsStaff::where('role', 0)->get();
        $assistance_types = AssistanceType::get();
        $requested_beneficiaries = AicsGis::with('aics_compositions')
            ->where('status', 0)
            ->get();
        $beneficiaries = AicsGis::with('aics_compositions')
            ->where('status', 1)
            ->when($searchInputRequest, function ($query, $searchInputRequest) {
                $query
                    ->where('name', 'LIKE', '%' . $searchInputRequest . '%')
                    ->orWhere('age', 'LIKE', '%' . $searchInputRequest . '%')
                    ->orWhere('sex', 'LIKE', '%' . $searchInputRequest . '%');
            })
            ->get();
        return Inertia::render('Staff/Pages/Beneficiaries/Aics', ['aics_staff' => $aics_staff, 'assistance_types' => $assistance_types, 'requested_beneficiaries' => $requested_beneficiaries, 'beneficiaries' => $beneficiaries, 'aics_mayor' => $aics_mayor]);
    }

    public function aics_form(Request $request)
    {
        $assistance_types = AssistanceType::get();
        $aics_staff = AicsStaff::whereNot('role', 0)->get();
        $mayor = AicsStaff::where('role', 0)->orderBy('created_at', 'desc')->first();
        return Inertia::render('Staff/Pages/Beneficiaries/AicsForm', ['assistance_types' => $assistance_types, 'aics_staff' => $aics_staff, 'mayor' => $mayor]);
    }

    public function aics_edit_form($id)
    {
        $assistance_types = AssistanceType::get();
        $aics_staff = AicsStaff::whereNot('role', 0)->get();
        $mayor = AicsStaff::where('role', 0)->orderBy('created_at', 'desc')->first();
        $beneficiary = AicsGis::with('aics_compositions')->where('id', $id)->first();
        return Inertia::render('Staff/Pages/Beneficiaries/AicsEditForm', ['assistance_types' => $assistance_types, 'aics_staff' => $aics_staff, 'mayor' => $mayor, 'beneficiary' => $beneficiary]);
    }

    // Beneficiaries

    public function beneficiary_report(Request $request)
    {
        $searchInput = $request->input('searchInput');
        $barangay_filter = $request->input('barangay_filter');
        $beneficiary_filter = $request->input('beneficiary_filter');
        $ages = $request->input('ages');

        $beneficiaries = Benefeciary::with(['pwd', 'women', 'senior', 'barangay'])
            ->whereIn('approved_status', [1, 2])
            ->when($beneficiary_filter ?? null, function ($query, $beneficiary_filter) {
                $query->whereIn('benefeciary_type', $beneficiary_filter);
            })
            ->when($barangay_filter ?? null, function ($query, $barangay_filter) {
                $query->whereIn('barangay_id', $barangay_filter);
            })
            ->when($ages ?? null, function ($query, $ages) {
                $query->whereIn('age', $ages);
            })
            ->when($searchInput, function ($query) use ($searchInput) {
                $query
                    ->where('first_name', 'LIKE', '%' . $searchInput . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $searchInput . '%')
                    ->orWhere('middle_name', 'LIKE', '%' . $searchInput . '%')
                    ->orWhere('age', 'LIKE', '%' . $searchInput . '%')
                    ->orWhere('sex', 'LIKE', '%' . $searchInput . '%')
                    ->orWhere('civil_status', 'LIKE', '%' . $searchInput . '%')
                    ->orWhere('educational_attainment', 'LIKE', '%' . $searchInput . '%')
                    ->orWhere('contact_number', 'LIKE', '%' . $searchInput . '%');
            })
            ->orderBy('first_name', 'desc')
            ->get();
        $barangays = Barangay::get();
        $monthly_income = MonthlyIncome::get();
        return Inertia::render('Staff/Pages/Beneficiaries/Beneficiaries', ['beneficiaries' => $beneficiaries, 'barangays' => $barangays, 'monthly_income' => $monthly_income]);
    }

    // End Beneficiaries Group

    // Requests Group

    // Render New Requests vue
    public function new_beneficiaries(Request $request)
    {
        $searchInputRequest = $request->input('searchInputRequest');
        $filterStatus = $request->input('filterStatus');
        $barangay_id = auth()->user()->barangay_id;
        $monthly_income = MonthlyIncome::get();
        $barangays = Barangay::get();
        $made_requests = Requests::with(['user', 'benefeciary.pwd', 'benefeciary.senior', 'benefeciary.women'])
            ->orderBy('created_at', 'asc')
            ->when($searchInputRequest, function ($query) use ($searchInputRequest) {
                $query->whereHas('benefeciary', function ($q) use ($searchInputRequest) {
                    $q
                        ->where('first_name', 'LIKE', '%' . $searchInputRequest . '%')
                        ->orWhere('last_name', 'LIKE', '%' . $searchInputRequest . '%');
                });
            })
            ->when($filterStatus != "", function ($query) use ($filterStatus) {
                $query->where(function ($subQuery) use ($filterStatus) {
                    if ($filterStatus == 4) {
                        $subQuery
                            ->where('status', 4)
                            ->whereIn('rejected_by', ['Staff', 'Admin']);
                    } else {
                        $subQuery->where('status', $filterStatus);
                    }
                });
            }, function ($query) {
                // Default condition when no filterStatus is provided
                $query->where(function ($subQuery) {
                    $subQuery
                        ->whereIn('status', [1, 2, 3])
                        ->orWhere(function ($orQuery) {
                            $orQuery
                                ->where('status', 4)
                                ->whereIn('rejected_by', ['Staff', 'Admin']);
                        });
                });
            })
            ->get();

        return Inertia::render('Staff/Pages/Requests/NewBeneficiaries', ['made_requests' => $made_requests, 'monthly_income' => $monthly_income, 'barangays' => $barangays, 'filterStatus' => $filterStatus]);
    }

    // End Requests Group

    public function respondRequest(Request $request)
    {
        try {
            $dateNow = Carbon::now()->format('Y/m/d');
            $message = '';

            $request_info = Requests::where('id', $request->id)->first(['user_id', 'benefeciary_id', 'barangay_id']);

            $endorsed_benefeciary = Benefeciary::where('id', $request_info->benefeciary_id)->first(['user_id', 'benefeciary_type', 'first_name']);

            $focals = User::where('focal_role', $endorsed_benefeciary->benefeciary_type)->where('barangay_id', $request_info->barangay_id)->get();

            $benefeciary_user = User::where('id', $endorsed_benefeciary->user_id)->first();

            $admins = User::where('role', 3)->get();
            $barangay_personnel = User::where('role', 1)->where('barangay_id', $request_info->barangay_id)->get();

            switch ($request->status) {
                case 2:
                    $message = 'Request Verified!';
                    Requests::where('id', $request->id)->update(['status' => 2, 'date_verified' => $dateNow]);

                    $details = [
                        'type' => 0,
                        'from' => auth()->user()->name,
                        'image' => auth()->user()->user_image,
                        'message' => 'Verified the beneficiary endorsement request',
                        'url' => route('focal.new_beneficiary')
                    ];

                    $details_for_admin = [
                        'type' => 0,
                        'from' => auth()->user()->name,
                        'image' => auth()->user()->user_image,
                        'message' => 'Verified the beneficiary endorsement request',
                        'url' => route('admin.request.beneficiaries')
                    ];

                    $details_for_barangay = [
                        'type' => 0,
                        'from' => auth()->user()->name,
                        'image' => auth()->user()->user_image,
                        'message' => 'Verified the beneficiary endorsement request',
                        'url' => route('barangay.new_beneficiary')
                    ];

                    $details_for_beneficiary = [
                        'type' => 0,
                        'from' => auth()->user()->name,
                        'image' => auth()->user()->user_image,
                        'message' => 'Verified your endorsement request',
                        'url' => route('beneficiary.profile')
                    ];
                    if ($benefeciary_user) {
                        $this->notificationService->sendNotification($benefeciary_user, $details_for_beneficiary);
                    }
                    $this->notificationService->sendNotification($focals, $details);
                    $this->notificationService->sendNotification($barangay_personnel, $details_for_barangay);
                    $this->notificationService->sendNotification($admins, $details_for_admin);

                    break;
                case 1:
                    $message = 'Verification Cancelled!';
                    Requests::where('id', $request->id)->update(['status' => 1, 'date_verified' => null, 'date_rejected' => null, 'rejected_by' => null, 'reason' => null]);

                    $details = [
                        'type' => 0,
                        'from' => auth()->user()->name,
                        'image' => auth()->user()->user_image,
                        'message' => 'Cancelled the Verification for beneficiary endorsement request',
                        'url' => route('focal.new_beneficiary')
                    ];

                    $details_for_admin = [
                        'type' => 0,
                        'from' => auth()->user()->name,
                        'image' => auth()->user()->user_image,
                        'message' => 'Cancelled the Verification for the beneficiary endorsement request',
                        'url' => route('admin.request.beneficiaries')
                    ];

                    $details_for_barangay = [
                        'type' => 0,
                        'from' => auth()->user()->name,
                        'image' => auth()->user()->user_image,
                        'message' => 'Cancelled the Verification for the beneficiary endorsement request',
                        'url' => route('barangay.new_beneficiary')
                    ];

                    $details_for_beneficiary = [
                        'type' => 0,
                        'from' => auth()->user()->name,
                        'image' => auth()->user()->user_image,
                        'message' => 'Cancelled the Verification of your endorsement request',
                        'url' => route('beneficiary.profile')
                    ];
                    if ($benefeciary_user) {
                        $this->notificationService->sendNotification($benefeciary_user, $details_for_beneficiary);
                    }

                    $this->notificationService->sendNotification($focals, $details);
                    $this->notificationService->sendNotification($barangay_personnel, $details_for_barangay);
                    $this->notificationService->sendNotification($admins, $details_for_admin);
                    break;
                case 4:
                    $message = 'Request Rejected!';
                    Requests::where('id', $request->id)->update(['status' => 4, 'date_rejected' => $dateNow, 'rejected_by' => 'Staff', 'reason' => $request->reason]);

                    $details = [
                        'type' => 0,
                        'from' => auth()->user()->name,
                        'image' => auth()->user()->user_image,
                        'message' => 'Rejected new beneficiary request',
                        'url' => route('focal.new_beneficiary')
                    ];

                    $details_for_barangay = [
                        'type' => 0,
                        'from' => auth()->user()->name,
                        'image' => auth()->user()->user_image,
                        'message' => 'Rejected the new beneficiary request',
                        'url' => route('barangay.new_beneficiary')
                    ];

                    $details_for_beneficiary = [
                        'type' => 0,
                        'from' => auth()->user()->name,
                        'image' => auth()->user()->user_image,
                        'message' => 'Rejected your endorsement request',
                        'url' => route('beneficiary.profile')
                    ];
                    if ($benefeciary_user) {
                        $this->notificationService->sendNotification($benefeciary_user, $details_for_beneficiary);
                    }

                    $this->notificationService->sendNotification($focals, $details);
                    $this->notificationService->sendNotification($barangay_personnel, $details_for_barangay);
                    break;
                case 5:
                        $message = 'Rejection Cancelled!';
                        Requests::where('id', $request->id)->update(['status' => 1, 'date_verified' => null, 'date_rejected' => null, 'rejected_by' => null, 'reason' => null]);
    
                        $details = [
                            'type' => 0,
                            'from' => auth()->user()->name,
                            'image' => auth()->user()->user_image,
                            'message' => 'Cancelled the Rejection for beneficiary endorsement request',
                            'url' => route('focal.new_beneficiary')
                        ];
    
                        $details_for_admin = [
                            'type' => 0,
                            'from' => auth()->user()->name,
                            'image' => auth()->user()->user_image,
                            'message' => 'Cancelled the Rejection for the beneficiary endorsement request',
                            'url' => route('admin.request.beneficiaries')
                        ];
    
                        $details_for_barangay = [
                            'type' => 0,
                            'from' => auth()->user()->name,
                            'image' => auth()->user()->user_image,
                            'message' => 'Cancelled the Rejection for the beneficiary endorsement request',
                            'url' => route('barangay.new_beneficiary')
                        ];
    
                        $details_for_beneficiary = [
                            'type' => 0,
                            'from' => auth()->user()->name,
                            'image' => auth()->user()->user_image,
                            'message' => 'Cancelled the Rejection of your endorsement request',
                            'url' => route('beneficiary.profile')
                        ];
                        if ($benefeciary_user) {
                            $this->notificationService->sendNotification($benefeciary_user, $details_for_beneficiary);
                        }
    
                        $this->notificationService->sendNotification($focals, $details);
                        $this->notificationService->sendNotification($barangay_personnel, $details_for_barangay);
                        $this->notificationService->sendNotification($admins, $details_for_admin);
                    break;
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function createProgram(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'title' => 'required',
            'purpose' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'venue' => 'required',
        ]);

        try {
            $values = [
                'title' => $request->title,
                'purpose' => $request->purpose,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'venue' => $request->venue,
                'status' => 0
            ];
            Program::create($values);

            $details = [
                'type' => 2,
                'from' => auth()->user()->name,
                'image' => auth()->user()->user_image,
                'message' => 'Created a new pogram.',
                'url' => route('admin.request.programs')
            ];

            $admins = User::where('role', 3)->get();
            $this->notificationService->sendNotification($admins, $details);

            return redirect()->back()->with('success', 'Program Request Created!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function updateProgram(Request $request, $id)
    {
        // dd($request->all());

        $request->validate([
            'title' => 'required',
            'purpose' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'venue' => 'required',
        ]);

        try {
            $program_title = Program::where('id', $id)->first('title')->title;

            $values = [
                'title' => $request->title,
                'purpose' => $request->purpose,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'venue' => $request->venue
            ];

            Program::where('id', $id)->update($values);

            $details = [
                'type' => 2,
                'from' => auth()->user()->name,
                'image' => auth()->user()->user_image,
                'message' => 'Updated the pogram ' . $request->title,
                'url' => route('admin.request.programs')
            ];

            $admins = User::where('role', 3)->get();
            $this->notificationService->sendNotification($admins, $details);

            return redirect()->back()->with('success', 'Program Request Updated!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function deleteProgram(Request $request, $id)
    {
        try {
            $request->validate([
                'reason' => 'required'
            ]);

            $program_title = Program::where('id', $id)->first('title')->title;
            Program::where('id', $id)->delete();

            $details = [
                'type' => 2,
                'from' => auth()->user()->name,
                'image' => auth()->user()->user_image,
                'message' => 'Deleted the pogram ' . $program_title . ' for a reason ' . $request->reason,
                'url' => route('admin.request.programs')
            ];

            $admins = User::where('role', 3)->get();
            $this->notificationService->sendNotification($admins, $details);

            return redirect()->back()->with('success', 'Program Request Deleted!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function addProgramBeneficiary(Request $request)
    {
        try {
            $beneficiaries = explode(',', $request->beneficiaries);

            foreach ($beneficiaries as $id) {
                ProgramBeneficiary::create(['benefeciary_id' => $id, 'program_id' => $request->program_id]);
            }
            return redirect()->back()->with('success', 'Beneficiaries Selected!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function removeProgramBeneficiary($id)
    {
        try {
            ProgramBeneficiary::where('id', $id)->delete();
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function submitProgramBeneficiary($id)
    {
        try {
            Program::where('id', $id)->update(['status' => 2]);

            $program = Program::where('id', $id)->first('title');

            $details = [
                'type' => 2,
                'from' => auth()->user()->name,
                'image' => auth()->user()->user_image,
                'message' => 'Submitted Beneficiaries for the pogram ' . $program->title,
                'url' => route('admin.program_info', $id)
            ];

            $admins = User::where('role', 3)->get();
            $this->notificationService->sendNotification($admins, $details);

            return redirect()->back()->with('success', 'List Submitted!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function cancelSubmission($id)
    {
        try {
            Program::where('id', $id)->update(['status' => 1]);
            return redirect()->back()->with('success', 'Submission Cancelled!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function createAicsBeneficiary(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'age' => 'required',
            'address' => 'required',
            'birthdate' => 'required',
            'sex' => 'required',
            'civil_status' => 'required',
            'interviewee' => 'required',
            'reviewee' => 'required'
        ]);

        $aics_gis_values = [
            'date' => $request->date,
            'name' => $request->name,
            'birthdate' => $request->birthdate,
            'age' => $request->age,
            'sex' => $request->sex,
            'address' => $request->address,
            'civil_status' => $request->civil_status,
            'occupation' => $request->occupation,
            'education' => $request->education,
            'monthly_income' => $request->monthly_income,
            'mobile_number' => $request->mobile_number,
            'ik_name' => $request->ik_name,
            'ik_birthdate' => $request->ik_birthdate,
            'ik_age' => $request->ik_age,
            'ik_sex' => $request->ik_sex,
            'ik_address' => $request->ik_address,
            'ik_civil_status' => $request->ik_civil_status,
            'ik_occupation' => $request->ik_occupation,
            'ik_education' => $request->ik_education,
            'relation_to_beneficiary' => $request->relation_to_beneficiary,
            'ik_monthly_income' => $request->ik_monthly_income,
            'ik_mobile_number' => $request->ik_mobile_number,
            'type_of_assistance' => $request->type_of_assistance,
            'amount_provided' => $request->amount_provided,
            'social_worker_assessment' => $request->social_worker_assessment,
            'interviewee' => $request->interviewee,
            'reviewee' => $request->reviewee,
            'status' => 1
        ];

        try {
            $aics_gis = AicsGis::create($aics_gis_values);

            foreach ($request->compositions as $composition) {
                // dd($composition['comp_monthly_income']);

                if ($composition['comp_name'] != null || $composition['comp_name'] != '') {
                    $composition_values = [
                        'aics_gis_id' => $aics_gis['id'],
                        'comp_name' => $composition['comp_name'],
                        'comp_age' => $composition['comp_age'],
                        'comp_relation_to_client' => $composition['comp_relation_to_client'],
                        'comp_education' => $composition['comp_education'],
                        'comp_occupation' => $composition['comp_occupation'],
                        'comp_monthly_income' => $composition['comp_monthly_income']
                    ];

                    AicsComposition::create($composition_values);
                }
            }

            $details = [
                'type' => 4,
                'from' => auth()->user()->name,
                'image' => auth()->user()->user_image,
                'message' => 'Created an AICS Beneficiaries.',
                'url' => route('admin.beneficiaries.aics')
            ];

            $admins = User::where('role', 3)->get();
            $this->notificationService->sendNotification($admins, $details);

            return redirect()->back()->with('success', 'Beneficiary Added!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function updateAicsBeneficiary(Request $request, $id)
    {
        // dd($request->compositions);

        $request->validate([
            'name' => 'required',
            'age' => 'required',
            'address' => 'required',
            'birthdate' => 'required',
            'sex' => 'required',
            'civil_status' => 'required'
        ]);

        $aics_gis_values = [
            'date' => $request->date,
            'name' => $request->name,
            'birthdate' => $request->birthdate,
            'age' => $request->age,
            'sex' => $request->sex,
            'address' => $request->address,
            'civil_status' => $request->civil_status,
            'occupation' => $request->occupation,
            'education' => $request->education,
            'monthly_income' => $request->monthly_income,
            'mobile_number' => $request->mobile_number,
            'ik_name' => $request->ik_name,
            'ik_birthdate' => $request->ik_birthdate,
            'ik_age' => $request->ik_age,
            'ik_sex' => $request->ik_sex,
            'ik_address' => $request->ik_address,
            'ik_civil_status' => $request->ik_civil_status,
            'ik_occupation' => $request->ik_occupation,
            'ik_education' => $request->ik_education,
            'relation_to_beneficiary' => $request->relation_to_beneficiary,
            'ik_monthly_income' => $request->ik_monthly_income,
            'ik_mobile_number' => $request->ik_mobile_number,
            'type_of_assistance' => $request->type_of_assistance,
            'amount_provided' => $request->amount_provided,
            'social_worker_assessment' => $request->social_worker_assessment,
            'interviewee' => $request->interviewee,
            'reviewee' => $request->reviewee,
        ];

        try {
            $aics_gis = AicsGis::where('id', $id)->update($aics_gis_values);

            foreach ($request->compositions as $composition) {
                if ($composition['id'] != null) {
                    $composition_values = [
                        'comp_name' => $composition['comp_name'],
                        'comp_age' => $composition['comp_age'],
                        'comp_relation_to_client' => $composition['comp_relation_to_client'],
                        'comp_education' => $composition['comp_education'],
                        'comp_occupation' => $composition['comp_occupation'],
                        'comp_monthly_income' => $composition['comp_monthly_income']
                    ];

                    AicsComposition::where('id', $composition['id'])->update($composition_values);
                } else {
                    $new_composition_values = [
                        'aics_gis_id' => $id,
                        'comp_name' => $composition['comp_name'],
                        'comp_age' => $composition['comp_age'],
                        'comp_relation_to_client' => $composition['comp_relation_to_client'],
                        'comp_education' => $composition['comp_education'],
                        'comp_occupation' => $composition['comp_occupation'],
                        'comp_monthly_income' => $composition['comp_monthly_income']
                    ];

                    AicsComposition::create($new_composition_values);
                }
            }

            foreach ($request->compositionIds as $id) {
                AicsComposition::where('id', $id)->delete();
            }

            return redirect()->back()->with('success', 'Beneficiary Updated!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function deleteAicsBeneficiary($id)
    {
        try {
            AicsGis::where('id', $id)->delete();
            AicsComposition::where('aics_gis_id', $id)->delete();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function editStaffList(Request $request)
    {
        // dd($request->all());

        try {
            foreach ($request->aics_staff as $staff) {
                $staff_values = [
                    'name' => $staff['name'],
                    'position' => $staff['position']
                ];

                AicsStaff::where('id', $staff['id'])->update($staff_values);
            }

            foreach ($request->aics_mayor as $mayor) {
                $mayor_values = [
                    'name' => $mayor['name'],
                    'position' => $mayor['position']
                ];
                AicsStaff::where('id', $mayor['id'])->update($mayor_values);
            }

            return redirect()->back()->with('success', 'Staff Edited!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function archiveAicsStaff($id, $status)
    {
        try {
            AicsStaff::where('id', $id)->update(['status' => $status]);
            $message = $status == 1 ? 'Personnel Archived!' : 'Personnel Unarchived!';
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function addAicsStaff(Request $request)
    {
        $validated_request = $request->validate([
            'name' => 'required',
            'position' => 'required',
            'role' => 'required'
        ]);

        try {
            AicsStaff::create($validated_request);
            return redirect()->back()->with('success', 'Personnel Added!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
