<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\AddBenefeciaryRequest;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use App\Models\Benefeciary;
use App\Models\Pwd;
use App\Models\Senior;
use App\Models\Women;
use App\Models\User;
use App\Models\Barangay;
use App\Models\MonthlyIncome;
use App\Models\BirthdayData;
use App\Models\Requests;
use App\Models\Program;
use App\Models\ProgramBeneficiary;
use App\Models\TransitionData;
use App\Models\AicsGis;
use App\Models\AicsComposition;
use App\Models\AicsStaff;
use App\Models\AssistanceType;
use Illuminate\Support\Facades\File;
use Hash;
use Carbon\Carbon;
use App\Services\NotificationService;

class AdminController extends Controller
{

    protected $notificationService;

    public function __construct(){
        $this->notificationService = new NotificationService();
    }

    //Render Dashboard Vue
    public function index(){
        $user = auth()->user();

        // Totals
        $totalSeniors = Benefeciary::
            where('approved_status', 1)
            ->where('benefeciary_type', 1)
            ->count();

        $totalPWDs = Benefeciary::
        where('approved_status', 1)
            ->where('benefeciary_type', 2)
            ->count();

        $totalWomen = Benefeciary::
        where('approved_status', 1)
            ->where('benefeciary_type', 3)
            ->count();

        $totalBeneficiaries = Benefeciary::
        where('approved_status', 1)
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
            ->where('status', 3) // Active status
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

            return Inertia::render('Admin/Pages/Dashboard', compact(
            'totalSeniors',
            'totalPWDs',
            'totalWomen',
            'totalBeneficiaries',
            'aicsData',
            'ongoingPrograms',
            'barangays'
        ));
    }

    //Render Users Vue
    public function users(Request $request){

        $searchInput = $request->input('searchInput');

        $users = User::with('barangay')
            ->when($searchInput, function($query, $search) {
                $query->search('name', $search);
            })->paginate(15);
        $barangays = Barangay::get();
        return Inertia::render('Admin/Pages/Users', ['users' => $users, 'barangays' => $barangays]);
    }

    //Requests Group

    //Render Program Vue
    public function program(Request $request){
        $searchInput = $request->input('searchInput');
        $searchInputRequest = $request->input('searchInputRequest');
        $program_requests = Program::with('program_beneficiary')
        ->whereIn('status', [0 ,4])
        ->when($searchInput, function($query, $search){
            $query->where('title', 'LIKE' , '%'.$search.'%')
            ->orWhere('purpose', 'LIKE' , '%'.$search.'%')
            ->orWhere('venue', 'LIKE' , '%'.$search.'%');
        })->orderBy('created_at', 'desc')->get();

        $approved_programs = Program::with('program_beneficiary')
        ->whereIn('status', [1,2,3])
        ->when($searchInputRequest, function($query, $search){
            $query->where('title', 'LIKE' , '%'.$search.'%')
            ->orWhere('purpose', 'LIKE' , '%'.$search.'%')
            ->orWhere('venue', 'LIKE' , '%'.$search.'%');
        })->orderBy('created_at', 'desc')->get();
        return Inertia::render('Admin/Pages/Requests/Program', ['program_requests' => $program_requests, 'approved_programs' => $approved_programs]);
    }

    public function program_info(Request $request, $id){
        $searchInput = $request->input('searchInput');
        $program = Program::where('id', $id)->first();
        $program_beneficiaries = ProgramBeneficiary::where('program_id', $id)->get();
        
        $selected_ids = $program_beneficiaries->pluck('benefeciary_id')->toArray();

        $beneficiaries = Benefeciary::with(['pwd', 'women', 'senior', 'barangay'])
                        ->whereIn('id', $selected_ids)
                        ->when($searchInput, function($query, $search){
                            $query->search('first_name', $search)
                            ->search('last_name', $search)
                            ->search('middle_name', $search)
                            ->search('age', $search)
                            ->search('sex', $search)
                            ->search('civil_status', $search)
                            ->search('educational_attainment', $search)
                            ->search('contact_number', $search);
                        })
                        ->orderBy('last_name', 'asc')->get();
        $barangays = Barangay::get();
        $monhtly_income = MonthlyIncome::get();
        return Inertia::render('Admin/Pages/Requests/ProgramInfo', ['program' => $program, 'beneficiaries' => $beneficiaries, 'barangays' => $barangays, 'monhtly_income' => $monhtly_income]);
    }
    
    //Render New Beneficiary Vue
    public function new_beneficiary(Request $request){
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
                            ->whereIn('rejected_by', ['Admin']);
                    } else {
                        $subQuery->where('status', $filterStatus);
                    }
                });
            }, function ($query) {
                // Default condition when no filterStatus is provided
                $query->where(function ($subQuery) {
                    $subQuery
                        ->whereIn('status', [2, 3])
                        ->orWhere(function ($orQuery) {
                            $orQuery
                                ->where('status', 4)
                                ->whereIn('rejected_by', ['Admin']);
                        });
                });
            })
            ->get();
        return Inertia::render('Admin/Pages/Requests/NewBeneficiary', ['made_requests' => $made_requests, 'filterStatus' => $filterStatus, 'monthly_income' => $monthly_income, 'barangays' => $barangays]);
    }

    public function request_info(Request $request, $id){
        $request_info = Requests::with('user')->where('id', $id)->first();
        $beneficiary_ids = json_decode($request_info->beneficiaries);
        //dd($beneficiary_ids);
        $monthly_income = MonthlyIncome::get();
        $barangays = Barangay::get();
        $searchInput = $request->input('searchInput');

        $benefeciaries = Benefeciary::with(['women', 'senior', 'pwd', 'barangay'])
                        ->whereIn('id', $beneficiary_ids)
                        ->when($searchInput, function($query, $searchInput){
                            $query->where('first_name', 'LIKE', '%'.$searchInput.'%')
                            ->orWhere('last_name', 'LIKE', '%'.$searchInput.'%')
                            ->orWhere('middle_name', 'LIKE', '%'.$searchInput.'%')
                            ->orWhere('age', 'LIKE', '%'.$searchInput.'%')
                            ->orWhere('sex', 'LIKE', '%'.$searchInput.'%')
                            ->orWhere('civil_status', 'LIKE', '%'.$searchInput.'%')
                            ->orWhere('educational_attainment', 'LIKE', '%'.$searchInput.'%')
                            ->orWhere('contact_number', 'LIKE', '%'.$searchInput.'%');
                        })
                    ->get();

        return Inertia::render('Admin/Pages/Requests/RequestInfo', ['benefeciaries' => $benefeciaries, 'barangays' => $barangays, 'monthly_income' => $monthly_income, 'request_info' => $request_info, 'requestID' => $id]);
    }

    //Render Birthday Vue
    public function birthday(Request $request){
        $searchInputRequest = $request->input('searchInputRequest');
        $searchInput = $request->input('searchInput');
        $user = auth()->user();
        //dd($focal_barangay);
        $monthNow = Carbon::now()->format('m');
        $yearNow = Carbon::now()->format('Y');

        $endorsed_benefeciaries = BirthdayData::with(['benefeciary', 'benefeciary.barangay'])
                        ->where('month', $monthNow)->where('year', $yearNow)->whereIn('status', [0,2])
                        ->whereHas('benefeciary', function($query) use ($searchInput){
                            $query->when($searchInput, function($query) use ($searchInput){
                                $query->search('first_name', $searchInput)
                                ->search('last_name', $searchInput)
                                ->search('middle_name', $searchInput)
                                ->search('age', $searchInput)
                                ->search('sex', $searchInput)
                                ->search('civil_status', $searchInput)
                                ->search('educational_attainment', $searchInput)
                                ->search('contact_number', $searchInput);
                            });
                        })
                    ->orderBy('created_at', 'desc')->get();

                    

        $received_gifts_benefeciaries = BirthdayData::with('benefeciary', 'benefeciary.barangay')
                    ->where('status', 1)->where('year', $yearNow)
                    ->whereHas('benefeciary', function($query) use ($searchInputRequest){
                        $query->when($searchInputRequest, function($query) use ($searchInputRequest){
                            $query->where('first_name', 'LIKE', '%'.$searchInputRequest.'%')
                                    ->orWhere('last_name', 'LIKE', '%'.$searchInputRequest.'%')
                                    ->orWhere('middle_name', 'LIKE', '%'.$searchInputRequest.'%')
                                    ->orWhere('age', 'LIKE', '%'.$searchInputRequest.'%')
                                    ->orWhere('sex', 'LIKE', '%'.$searchInputRequest.'%')
                                    ->orWhere('civil_status', 'LIKE', '%'.$searchInputRequest.'%')
                                    ->orWhere('educational_attainment', 'LIKE', '%'.$searchInputRequest.'%')
                                    ->orWhere('contact_number', 'LIKE', '%'.$searchInputRequest.'%');
                        });
                    })
                    ->orderBy('created_at', 'desc')->get();
      
        return Inertia::render('Admin/Pages/Requests/Birthday', ['endorsed_benefeciaries' => $endorsed_benefeciaries, 'received_gifts_benefeciaries' => $received_gifts_benefeciaries]);
    }

    //Render AICS Vue
    public function request_aics(){
        $aics_staff = AicsStaff::get();
        $assistance_types = AssistanceType::get();
        $requested_beneficiaries = AicsGis::with('aics_compositions')->where('status', 0)->get();
        
        return Inertia::render('Admin/Pages/Requests/Aics', ['aics_staff' => $aics_staff, 'assistance_types' => $assistance_types, 'requested_beneficiaries' => $requested_beneficiaries]);
    }

    public function aics_edit_form($id){
        $assistance_types = AssistanceType::get();
        $aics_staff = AicsStaff::get();
        $mayor = AicsStaff::where('role', 0)->orderBy('created_at', 'desc')->first();
        $beneficiary = AicsGis::with('aics_compositions')->where('id', $id)->first();
        return Inertia::render('Admin/Pages/Requests/AicsEditForm', ['assistance_types' => $assistance_types, 'aics_staff' => $aics_staff, 'mayor' => $mayor, 'beneficiary' => $beneficiary]);
    }

    // End Requests Group


    // Beneficiaries Group

    public function beneficiary_report(Request $request){
        $searchInput = $request->input('searchInput');
        $barangay_filter = $request->input('barangay_filter');
        $beneficiary_filter = $request->input('beneficiary_filter');
        $ages = $request->input('ages');
        $beneficiaries = Benefeciary::with(['pwd', 'women', 'senior', 'barangay'])
        ->where('approved_status', 1)
        ->when($beneficiary_filter ?? null, function($query, $beneficiary_filter) {
            $query->whereIn('benefeciary_type', $beneficiary_filter);
        })
        ->when($barangay_filter ?? null, function($query, $barangay_filter) {
            $query->whereIn('barangay_id', $barangay_filter);
        })
        ->when($ages ?? null, function($query, $ages) {
            $query->whereIn('age', $ages);
        })
        ->when($searchInput, function($query) use ($searchInput){
            $query->where('first_name', 'LIKE', '%'.$searchInput.'%')
            ->orWhere('last_name', 'LIKE', '%'.$searchInput.'%')
            ->orWhere('middle_name', 'LIKE', '%'.$searchInput.'%')
            ->orWhere('age', 'LIKE', '%'.$searchInput.'%')
            ->orWhere('sex', 'LIKE', '%'.$searchInput.'%')
            ->orWhere('civil_status', 'LIKE', '%'.$searchInput.'%')
            ->orWhere('educational_attainment', 'LIKE', '%'.$searchInput.'%')
            ->orWhere('contact_number', 'LIKE', '%'.$searchInput.'%');
        })
        ->orderBy('first_name', 'desc')->get();

        $archived_beneficiaries = Benefeciary::with(['pwd', 'women', 'senior', 'barangay'])
        ->where('approved_status', 2)
        ->orderBy('first_name', 'desc')->get();
        $barangays = Barangay::get();
        $monthly_income = MonthlyIncome::get();
        return Inertia::render('Admin/Pages/Beneficiaries/Beneficiaries', ['beneficiaries' => $beneficiaries, 'barangays' => $barangays, 'monthly_income' => $monthly_income, 'archived_beneficiaries' => $archived_beneficiaries]);
    }

    //Render aics Vue
    public function beneficiary_aics(Request $request){
        $searchInput = $request->input('searchInput');
        $beneficiaries = AicsGis::with('aics_compositions')
        ->where('status', 1)
        ->when($searchInput, function($query, $search){
            $query->where('name', 'LIKE', '%'.$search.'%')
            ->orWhere('age', 'LIKE', '%'.$search.'%')
            ->orWhere('sex', 'LIKE', '%'.$search.'%');
        })
        ->orderBy('created_at', 'desc')->get();
        return Inertia::render('Admin/Pages/Beneficiaries/Aics', ['beneficiaries' => $beneficiaries]);
    }

    //Render Mapping Vue
    public function beneficiary_mapping(){
        $barangays = Barangay::withCount([
            'benefeciary as senior_count' => function ($query) {
                $query->where('benefeciary_type', 1)
                ->where('approved_status', 1);
            },
            'benefeciary as pwd_count' => function ($query) {
                $query->where('benefeciary_type', 2)
                ->where('approved_status', 1);
            },
            'benefeciary as women_count' => function ($query) {
                $query->where('benefeciary_type', 3)
                ->where('approved_status', 1);
            }
        ])->get();
        return Inertia::render('Admin/Pages/Beneficiaries/Mapping', ['barangays' => $barangays]);
    }

    public function beneficiary_barangay(Request $request, $barangay_id){
        $barangay = Barangay::where('id', $barangay_id)->first();
        $beneficiary_filter = $request->input('beneficiary_filter');
        $beneficiaries = Benefeciary::with(['pwd', 'women', 'senior', 'barangay', 'program_beneficiary.program', 'birthday_data'])
        ->where('approved_status', 1)
        ->where('barangay_id', $barangay_id)
        ->when($beneficiary_filter ?? null, function($query, $beneficiary_filter) {
            $query->whereIn('benefeciary_type', $beneficiary_filter);
        })
        ->orderBy('created_at', 'desc')->get();
        $barangays = Barangay::get();
        $monthly_income = MonthlyIncome::get();
        return Inertia::render('Admin/Pages/Beneficiaries/BeneficiariesBarangay', ['beneficiaries' => $beneficiaries, 'barangays' => $barangays, 'monthly_income' => $monthly_income, 'barangay' => $barangay]);
    }

    public function application(Request $request){

        $beneficiary_filter = $request->input('beneficiary_filter');
        $beneficiaries = Benefeciary::with(['pwd', 'women', 'senior', 'barangay'])
        ->where('approved_status', 3)
        ->when($beneficiary_filter ?? null, function($query, $beneficiary_filter) {
            $query->whereIn('benefeciary_type', $beneficiary_filter);
        })
        ->orderBy('created_at', 'asc')->get();
        $barangays = Barangay::get();
        $monthly_income = MonthlyIncome::get();
        return Inertia::render('Admin/Pages/Beneficiaries/Application', ['beneficiaries' => $beneficiaries, 'barangays' => $barangays, 'monthly_income' => $monthly_income]);
    }

    //End Beneficiaries Group


    //End Reports Group


    public function barangays(){
        //$role = auth()->user()->role;

        $barangays = Barangay::withCount([
            'benefeciary as senior_count' => function ($query) {
                $query->where('benefeciary_type', 1);
            },
            'benefeciary as pwd_count' => function ($query) {
                $query->where('benefeciary_type', 2);
            },
            'benefeciary as women_count' => function ($query) {
                $query->where('benefeciary_type', 3);
            }
        ])->get();
        return Inertia::render('Admin/Pages/BarangayList', ['barangays' => $barangays]);
    }


    public function redirectToLogin(){
        if (auth()->check()){
            switch(auth()->user()->role){
                case 'admin':
                        return redirect()->intended(route('admin.dashboard', absolute: false));
                    break;
                case 'barangay':
                        return redirect()->intended(route('barangay.dashboard', absolute: false));
                    break;
                case 'staff':
                        return redirect()->intended(route('staff.dashboard', absolute: false));
                    break;
                case 'focal':
                        return redirect()->intended(route('focal.dashboard', absolute: false));
                    break;
                case 'beneficiary':
                        return redirect()->intended(route('beneficiary.dashboard', absolute: false));
                    break;
                 
            }  
        }else{
            return redirect()->route('login');
        }

        
    }

    //Database Interaction

    public function createUsers(Request $request){

        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username|min:6',
            'password' => 'required',
            'barangay_id' => 'required',
            'role' => 'required',
            'focal_role' => 'required'
        ],
            [
                'barangay_id.required' => 'Choose a barangay',
                'role.required' => 'Choose a role',
                'focal_role.required' => 'Choose a focal type',
            ]

        );

        try{
            if ($request->hasFile('user_image')) {

                $file = $request->file('user_image');
                
                $filename = time().'-'.Str::random(5).'.' . $file->getClientOriginalExtension();
                
                $file->move('profile_images', $filename);
            }else{
                $filename = '';
            }
    
            $user_values = [
                'barangay_id' => $request->barangay_id,
                'name' => $request->name,
                'role' => $request->role,
                'focal_role' => $request->focal_role,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_image' => $filename
            ];
            
            //dd($user_values);
            User::create($user_values);
    
            return redirect()->route('admin.users')->with('success', 'New User Added!');
        }catch(\Exception $e){
            return redirect()->route('admin.users')->with('error', 'Something went wrong!');
        }
    }

    public function updateUsers(Request $request, $id){
        //dd($request->all());
        $request->validate(
            [
                'name' => 'required',
                'username' => 'required|min:6|unique:users,username,' . $id,
                'barangay_id' => 'required',
                'role' => 'required',
                'focal_role' => 'required',
            ],
            [
                'barangay_id.required' => 'Choose a barangay',
                'role.required' => 'Choose a role',
                'focal_role.required' => 'Choose a focal type',
            ]
        );

        try{
            $old_user_image = User::where('id', $id)->first('user_image')->user_image;

        if ($request->hasFile('user_image')) {

            $file = $request->file('user_image');
            
            $filename = time().'-'.Str::random(5).'.' . $file->getClientOriginalExtension();
            
            $file->move('profile_images', $filename);

            $oldFilePath = public_path('profile_images/' . $old_user_image);
            if (File::exists($oldFilePath)) {
                File::delete($oldFilePath);
            }
        }else{
            $filename = $old_user_image;
        }

        $user_values = [
            'barangay_id' => $request->barangay_id,
            'name' => $request->name,
            'role' => $request->role,
            'focal_role' => $request->focal_role,
            'username' => $request->username,
            'email' => $request->email,
            'user_image' => $filename
        ];

        User::where('id', $id)->update($user_values);

        return redirect()->route('admin.users')->with('success', 'User Updated!');
        }catch(\Exception $e){
            return redirect()->route('admin.users')->with('error', 'Something went wrong!');
        }
    }

    public function updatePassword(Request $request, $id){
        $request->validate([
            'password' => 'required|min:8',
        ]);

        try{
            User::where('id', $id)->update(['password' => Hash::make($request->password)]);
            return redirect()->route('admin.users')->with('success', 'Password Update!');
        }catch(\Exception $e){
            return redirect()->route('admin.users')->with('error', 'Something went wrong!');
        }
        
    }

    public function deleteUsers($id){
       try{
        $old_user_image = User::where('id', $id)->first('user_image')->user_image;
        $oldFilePath = public_path('profile_images/' . $old_user_image);
            if (File::exists($oldFilePath)) {
                File::delete($oldFilePath);
            }
            
        User::where('id', $id)->delete();

        return redirect()->route('admin.users')->with('success', 'User Deleted!');
       }catch(\Exception $e){
        return redirect()->route('admin.users')->with('error', 'Something went wrong!');
       }
    }

    public function respondRequest(Request $request){
        try{
            $dateNow = Carbon::now()->format('Y/m/d');
            $message = '';

            $request_info = Requests::where('id', $request->id)->first(['user_id', 'benefeciary_id', 'barangay_id']);

            
            $endorsed_benefeciary = Benefeciary::where('id', $request_info->benefeciary_id)->first(['user_id', 'benefeciary_type', 'first_name']);

            $focals = User::where('focal_role', $endorsed_benefeciary->benefeciary_type)->where('barangay_id', $request_info->barangay_id)->get();
            
            $staffs = User::where('role', 2)->get();
            
            $benefeciary_user = User::where('id', $endorsed_benefeciary->user_id)->first();

            $barangay_personnel = User::where('role', 1)->where('barangay_id', $request_info->barangay_id)->get();

            switch($request->status){
                case 2:
                    $message = 'Rejection Cancelled!';
                    Requests::where('id', $request->id)->update(['status' => 2, 'date_approved' => null, 'reason' => null, 'rejected_by' => null]);
                    
                    $details = [
                        'type' => 0,
                        'from' => auth()->user()->name,
                        'image' => auth()->user()->user_image,
                        'message' => 'Cancel the rejection of beneficiary endorsement request',
                        'url' => route('focal.new_beneficiary')
                    ];


                    $details_for_barangay = [
                        'type' => 0,
                        'from' => auth()->user()->name,
                        'image' => auth()->user()->user_image,
                        'message' => 'Cancel the rejection of beneficiary endorsement request',
                        'url' => route('barangay.new_beneficiary')
                    ];

                    $details_for_beneficiary = [
                        'type' => 0,
                        'from' => auth()->user()->name,
                        'image' => auth()->user()->user_image,
                        'message' => 'Cancel the rejection of your endorsement request',
                        'url' => route('beneficiary.profile')
                    ];

                    $details_for_staff = [
                        'type' => 0,
                        'from' => auth()->user()->name,
                        'image' => auth()->user()->user_image,
                        'message' => 'Cancel the rejection of the endorsement request',
                        'url' => route('staff.new_beneficiaries')
                    ];

                    $this->notificationService->sendNotification($staffs, $details_for_staff);

                    if($benefeciary_user){
                        $this->notificationService->sendNotification($benefeciary_user, $details_for_beneficiary);
                    }
                    $this->notificationService->sendNotification($focals, $details);
                    $this->notificationService->sendNotification($barangay_personnel, $details_for_barangay);

                    break;
                case 3:
                    $message = 'Request Approved!';
                    Requests::where('id', $request->id)->update(['status' => 3, 'date_approved' => $dateNow]);
                    Benefeciary::where('id', $request_info->benefeciary_id)->update(['approved_status' => 1, 'date_approved' => $dateNow]);
                    $details = [
                        'type' => 0,
                        'from' => auth()->user()->name,
                        'image' => auth()->user()->user_image,
                        'message' => 'Approved the beneficiary endorsement request',
                        'url' => route('focal.new_beneficiary')
                    ];


                    $details_for_barangay = [
                        'type' => 0,
                        'from' => auth()->user()->name,
                        'image' => auth()->user()->user_image,
                        'message' => 'Approved the beneficiary endorsement request',
                        'url' => route('barangay.new_beneficiary')
                    ];

                    $details_for_beneficiary = [
                        'type' => 0,
                        'from' => auth()->user()->name,
                        'image' => auth()->user()->user_image,
                        'message' => 'Approved your endorsement request',
                        'url' => route('beneficiary.profile')
                    ];

                    $details_for_staff = [
                        'type' => 0,
                        'from' => auth()->user()->name,
                        'image' => auth()->user()->user_image,
                        'message' => 'Approved the endorsement request',
                        'url' => route('staff.new_beneficiaries')
                    ];

                    $this->notificationService->sendNotification($staffs, $details_for_staff);

                    if($benefeciary_user){
                        $this->notificationService->sendNotification($benefeciary_user, $details_for_beneficiary);
                    }
                    $this->notificationService->sendNotification($focals, $details);
                    $this->notificationService->sendNotification($barangay_personnel, $details_for_barangay);

                    break;
                case 4:
                    $message = 'Request Rejected!';
                    Requests::where('id', $request->id)->update(['status' => 4, 'date_rejected' => $dateNow, 'rejected_by' => "Admin", 'reason' => $request->reason]);

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

                    $details_for_staff = [
                        'type' => 0,
                        'from' => auth()->user()->name,
                        'image' => auth()->user()->user_image,
                        'message' => 'Rejected the endorsement request',
                        'url' => route('staff.new_beneficiaries')
                    ];

                    $this->notificationService->sendNotification($staffs, $details_for_staff);

                    if($benefeciary_user){
                        $this->notificationService->sendNotification($benefeciary_user, $details_for_beneficiary);
                    }
                    
                    $this->notificationService->sendNotification($focals, $details);
                    $this->notificationService->sendNotification($barangay_personnel, $details_for_barangay);
                    break;

            }

            return redirect()->back()->with('success', $message);
            

        }catch(\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function respondBirthdayRequest(Request $request){
        try{
            $dateNow = Carbon::now()->format('Y/m/d');

            foreach ($request->beneficiaries as $id){
                BirthdayData::where('id', $id)->update(['status' => 1, 'date_approved' => $dateNow]);
            }

            $birthdays = BirthdayData::whereIn('id', $request->beneficiaries)->get();
            $sender = User::where('id', $birthdays[0]->user_id)->first();
            $recepient = User::where('focal_role', $sender->focal_role)->where('barangay_id', $sender->barangay_id)->get();
            
            $details = [
                'type' => 3,
                'from' => auth()->user()->name,
                'image' => auth()->user()->user_image,
                'message' => 'Approved birthday endorsement request for this month.',
                'url' => route('focal.birthday')
            ];

            $this->notificationService->sendNotification($recepient, $details);

            return redirect()->back()->with('success', 'Request Approved!');

        }catch(\Exception $e){
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function respondProgramRequest(Request $request){
        try{
           
            $program = Program::where('id', $request->id)->first('title');
            $staffs = User::where('role', 2)->get();
            $message = '';
            
            switch($request->status){
                case 1:
                    $message = 'Program Reviewed!';
                    Program::where('id', $request->id)->update(['status' => 1]);

                    $details = [
                        'type' => 2,
                        'from' => auth()->user()->name,
                        'image' => auth()->user()->user_image,
                        'message' => 'Reviewed the program '.$program->title.' you requested.',
                        'url' => route('staff.program_info', $request->id)
                    ];



                    break;
                case 4:

                    if($request->reason == null || $request->reason == ''){
                        return redirect()->back()->with('error', 'Reason Required');
                    }

                    $message = 'Program Rejected!';
                    Program::where('id', $request->id)->update(['status' => 4, 'reason' => $request->reason, 'date_rejected' => now()]);

                    $details = [
                        'type' => 2,
                        'from' => auth()->user()->name,
                        'image' => auth()->user()->user_image,
                        'message' => 'Rejected the program '.$program->title.' you requested.',
                        'url' => route('staff.program_info', $request->id)
                    ];

                    
                    break;
                case 0:

                    $message = 'Rejection Cancelled!';
                    Program::where('id', $request->id)->update(['status' => 0, 'reason' => null, 'date_rejected' => null]);

                    $details = [
                        'type' => 2,
                        'from' => auth()->user()->name,
                        'image' => auth()->user()->user_image,
                        'message' => 'Cancelled the rejected og the program '.$program->title.' you requested.',
                        'url' => route('staff.program_info', $request->id)
                    ];

                    break;
            }

            $this->notificationService->sendNotification($staffs, $details);
            return redirect()->back()->with('success', $message);
        }catch(Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function approveProgram($id){
        try{
            Program::where('id', $id)->update(['status' => 3]);
            $program_benefeciaries = ProgramBeneficiary::where('program_id', $id)->get();

            $program = Program::where('id', $id)->first('title');
            $staffs = User::where('role', 2)->get();
  
            $details = [
                'type' => 2,
                'from' => auth()->user()->name,
                'image' => auth()->user()->user_image,
                'message' => 'Approved the program '.$program->title.' you requested.',
                'url' => route('staff.program_info', $id)
            ];

            $this->notificationService->sendNotification($staffs, $details);

            return redirect()->back()->with('success', 'Request Approved!');
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function accept_aics(Request $request){
        try{
            //dd($request->gis_ids);

            AicsGis::whereIn('id', $request->gis_ids)->update(['status' => 1]);

            $staffs = User::where('role', 2)->get();

            $details = [
                'type' => 4,
                'from' => auth()->user()->name,
                'image' => auth()->user()->user_image,
                'message' => 'Accepted the AICS beneficiaries you created.',
                'url' => route('staff.aics')
            ];

            $this->notificationService->sendNotification($staffs, $details);

            return redirect()->back()->with('success', 'AISC Beneficiary Approved!');
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function archiveBeneficiary($id, $status){
        try{
           
            Benefeciary::where('id', $id)->update(['approved_status' => $status]);
            $beneficiary = Benefeciary::where('id', $id)->first(['barangay_id', 'benefeciary_type', 'first_name', 'last_name']);
            $focals = User::where('barangay_id', $beneficiary->barangay_id)->where('focal_role', $beneficiary->benefeciary_type)->get();
            $barangay_personnel = User::where('barangay_id', $beneficiary->barangay_id)->where('role', 1)->get();
            $staffs = User::where('role', 2)->get();
            $message = $status == 2 ? 'Personnel Archived!' : 'Personnel Unarchived!';

            if($status == 1){
                $barangay_details = [
                    'type' => 5,
                    'from' => auth()->user()->name,
                    'image' => auth()->user()->user_image,
                    'message' => 'Unarchived '.$beneficiary->first_name.' '.$beneficiary->last_name.' from the beneficiary list',
                    'url' => null
                ];
    
                $details = [
                    'type' => 5,
                    'from' => auth()->user()->name,
                    'image' => auth()->user()->user_image,
                    'message' => 'Unarchived '.$beneficiary->first_name.' '.$beneficiary->last_name.' from the beneficiary list',
                    'url' => route('focal.beneficiaries')
                ];
            }else{
                $barangay_details = [
                    'type' => 5,
                    'from' => auth()->user()->name,
                    'image' => auth()->user()->user_image,
                    'message' => 'Archived '.$beneficiary->first_name.' '.$beneficiary->last_name.' from the beneficiary list',
                    'url' => null
                ];
    
                $details = [
                    'type' => 5,
                    'from' => auth()->user()->name,
                    'image' => auth()->user()->user_image,
                    'message' => 'Archived '.$beneficiary->first_name.' '.$beneficiary->last_name.' from the beneficiary list',
                    'url' => route('focal.beneficiaries')
                ];
            }

            $this->notificationService->sendNotification($focals, $details);
            $this->notificationService->sendNotification($barangay_personnel, $barangay_details);
            $this->notificationService->sendNotification($staffs, $barangay_details);
            
            return redirect()->back()->with('success', $message);
        }catch(\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }



}
