<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Barangay;
use App\Models\MonthlyIncome;
use App\Models\Benefeciary;
use App\Models\TransitionData;
use App\Models\Program;
use App\Models\ProgramBeneficiary;
use App\Models\Requests;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Hash;
use Carbon\Carbon;
use App\Services\NotificationService;

class BarangayController extends Controller
{

    protected $notificationService;

    public function __construct(){
        $this->notificationService = new NotificationService();
    }

    public function index(){
        $user = auth()->user();
        $total_senior = Benefeciary::where('barangay_id', $user->barangay_id)->where('approved_status', 1)->where('benefeciary_type', 1)->count();

        $total_pwd = Benefeciary::where('barangay_id', $user->barangay_id)->where('approved_status', 1)->where('benefeciary_type', 2)->count();

        $total_women = Benefeciary::where('barangay_id', $user->barangay_id)->where('approved_status', 1)->where('benefeciary_type', 3)->count();

        $beneficiaries = [$total_senior, $total_pwd, $total_women];

        $latest_beneficiaries = Benefeciary::where('barangay_id', $user->barangay_id)
                        ->where('approved_status', 1)
                        ->latest()
                        ->take(5) // or use limit(5)
                        ->get();

        $dateNow  = Carbon::now();
        $programs = Program::with(['program_beneficiary.beneficiary.barangay'])->withCount      ('program_beneficiary as beneficiary_count')
                        ->where('status', 3)
                        ->where('end_date', '>=', $dateNow)
                        ->whereHas('program_beneficiary.beneficiary.barangay', function ($query) use ($user) {
                            $query->where('barangay_id', $user->barangay_id);
                        })
                        ->orderBy('created_at', 'desc')
                        ->get();
        

        return Inertia::render('Barangay/Pages/Dashboard', ['beneficiaries' => $beneficiaries, 'latest_beneficiaries' => $latest_beneficiaries, 'programs' => $programs]);
    }

    //Render Focals
    public function focals(Request $request){
        $searchInput = $request->input('searchInput');
        $user = auth()->user();
        $barangays = Barangay::get();
        

        $users = User::with('barangay')->where('barangay_id', $user->barangay_id)->whereNotIn('role', [2, 3])
        ->when($searchInput, function($query, $search) {
            $query->search('name', $search)
            ->search('username', $search);
        })->get();

        //dd($users);
        return Inertia::render('Barangay/Pages/Focals', ['users' => $users, 'barangays' => $barangays]);
    }


    //Render Programs
    public function programs(Request $request){
        $user = auth()->user();
        $searchInput = $request->input('searchInput');

        $programs = Program::with(['program_beneficiary.beneficiary.barangay'])
            ->where('status', 3)
            ->whereHas('program_beneficiary.beneficiary.barangay', function ($query) use ($user) {
                $query->where('barangay_id', $user->barangay_id);
            })
            ->when($searchInput, function ($query) use ($searchInput) {
                $query->where('title', 'LIKE', '%'.$searchInput.'')
                    ->orWhere('purpose', 'LIKE', '%'.$searchInput.'')
                    ->orWhere('venue', 'LIKE', '%'.$searchInput.'');
            })->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Barangay/Pages/Programs', ['programs' => $programs]);
    }

    public function program_info($id){
        $user = auth()->user();
        $program = Program::with('program_beneficiary')->where('id', $id)->first();
        $program_beneficiaries = ProgramBeneficiary::with('beneficiary')
        ->whereHas('beneficiary', function ($query) use ($user) {
            $query->where('barangay_id', $user->barangay_id);
        })
        ->where('program_id', $id)->get();
        $selected_ids = $program_beneficiaries->pluck('benefeciary_id')->toArray();
        $beneficiaries = Benefeciary::with(['pwd', 'women', 'senior', 'barangay'])
                        ->whereIn('id', $selected_ids)->get();
        $barangays = Barangay::get();
        $monhtly_income = MonthlyIncome::get();
        return Inertia::render('Barangay/Pages/ProgramInfo', ['program' => $program, 'beneficiaries' => $beneficiaries, 'barangays' => $barangays, 'monhtly_income' => $monhtly_income]);
    }

    //Beneficiaries Group

    //Render Pwd
    public function pwd(Request $request){
        $monthly_income = MonthlyIncome::get();
        $barangays = Barangay::get();
        $searchInput = $request->input('searchInput');
        $user = auth()->user();

        $benefeciaries = Benefeciary::with(['pwd', 'barangay'])
                            ->when($searchInput, function($query, $search) {
                                $query->search('id', $search)
                                ->search('first_name', $search)
                                ->search('last_name', $search)
                                ->search('middle_name', $search);
        })
        ->where('benefeciary_type', 2)
        ->where('barangay_id', $user->barangay_id)
        ->whereIn('approved_status', [1,2])
        ->paginate(15);
        return Inertia::render('Barangay/Pages/Beneficiaries/Pwd', ['benefeciaries' => $benefeciaries, 'barangays' => $barangays, 'monthly_income' => $monthly_income]);
    }

    //Render Senior
    public function senior(Request $request){
        $monthly_income = MonthlyIncome::get();
        $barangays = Barangay::get();
        $searchInput = $request->input('searchInput');
        $user = auth()->user();

        $benefeciaries = Benefeciary::with(['senior', 'barangay'])
                            ->when($searchInput, function($query, $search) {
                                $query->search('id', $search)
                                ->search('first_name', $search)
                                ->search('last_name', $search)
                                ->search('middle_name', $search);
        })
        ->where('benefeciary_type', 1)
        ->where('barangay_id', $user->barangay_id)
        ->whereIn('approved_status', [1,2])
        ->paginate(15);
        return Inertia::render('Barangay/Pages/Beneficiaries/Senior', ['benefeciaries' => $benefeciaries, 'barangays' => $barangays, 'monthly_income' => $monthly_income]);
    }

    //Render Women
    public function women(Request $request){
        $monthly_income = MonthlyIncome::get();
        $barangays = Barangay::get();
        $searchInput = $request->input('searchInput');
        $user = auth()->user();

        $benefeciaries = Benefeciary::with(['women', 'barangay'])
                            ->when($searchInput, function($query, $search) {
                                $query->search('id', $search)
                                ->search('first_name', $search)
                                ->search('last_name', $search)
                                ->search('middle_name', $search);
        })
        ->where('benefeciary_type', 3)
        ->where('barangay_id', $user->barangay_id)
        ->whereIn('approved_status', [1,2])
        ->paginate(15);
        return Inertia::render('Barangay/Pages/Beneficiaries/Women', ['benefeciaries' => $benefeciaries, 'barangays' => $barangays, 'monthly_income' => $monthly_income]);
    }

    //End Beneficiaries Group


    //Requests Group

    //Render NewBeneficiary
    public function new_beneficiary(Request $request){
        $searchInputRequest = $request->input('searchInputRequest');
        $filterStatus = $request->input('filterStatus');
        $barangay_id = auth()->user()->barangay_id;
        $monthly_income = MonthlyIncome::get();
        $barangays = Barangay::get();
        $made_requests = Requests::with(['user', 'benefeciary' ,'benefeciary.pwd', 'benefeciary.senior', 'benefeciary.women'])
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
        ->where('barangay_id', $barangay_id)->get();
        return Inertia::render('Barangay/Pages/Requests/NewBeneficiary', ['made_requests' => $made_requests, 'monthly_income' => $monthly_income, 'barangays' => $barangays,'filterStatus' => $filterStatus]);
    }


    //Render Transitioning
    public function transitioning(Request $request){
        $monthly_income = MonthlyIncome::get();
        $barangays = Barangay::get();
        $searchInput = $request->input('searchInput');
        $user = auth()->user();

        $focal_barangay = $user->barangay->barangay_name;

        $endorsed_benefeciaries = TransitionData::with(['benefeciary', 'benefeciary.senior', 'benefeciary.women', 'benefeciary.pwd'])->where('barangay_id', $user->barangay_id)->whereIn('status', [1,3])->get();

        
        $transitioned_benefeciaries = TransitionData::with(['benefeciary', 'benefeciary.senior', 'benefeciary.women', 'benefeciary.pwd'])->where('barangay_id', $user->barangay_id)->where('status', 2)->get();

        return Inertia::render('Barangay/Pages/Requests/Transitioning', ['endorsed_benefeciaries' => $endorsed_benefeciaries, 'transitioned_benefeciaries' => $transitioned_benefeciaries, 'monthly_income' => $monthly_income, 'barangays' => $barangays]);
    }

    //End Requests Group


    //Reports Group

    //Render Program
    public function report_program(){
        $role = auth()->user()->role;
        return Inertia::render('Barangay/Pages/Reports/Program');
    }

    //Render Program
    public function report_beneficiary(){
        $role = auth()->user()->role;
        return Inertia::render('Barangay/Pages/Reports/Beneficiaries');
    }

    public function createUsers(Request $request){
        //dd($request->all());

        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username|min:6',
            'password' => 'required',
            'barangay_id' => 'required',
            'role' => 'required|in:0,1',
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
    
            return redirect()->back()->with('success', 'New User Added!');
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function updateUsers(Request $request, $id){
        //dd($request->all());
        $request->validate(
            [
                'name' => 'required',
                'username' => 'required|min:6|unique:users,username,' . $id,
                'barangay_id' => 'required',
                'role' => 'required|in:0,1',
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

        return redirect()->back()->with('success', 'User Updated!');
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function updatePassword(Request $request, $id){
        $request->validate([
            'password' => 'required|min:8',
        ]);

        try{
            User::where('id', $id)->update(['password' => Hash::make($request->password)]);
            return redirect()->back()->with('success', 'Password Update!');
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'Something went wrong!');
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

        return redirect()->back()->with('success', 'User Deleted!');
       }catch(\Exception $e){
        return redirect()->back()->with('error', 'Something went wrong!');
       }
    }

    public function respondRequest(Request $request){
        try{
            $dateNow = Carbon::now()->format('Y/m/d');
            $message = '';
            $endorsed_user = Requests::where('id', $request->id)->first(['user_id', 'benefeciary_id']);
            $user = User::where('id', $endorsed_user->user_id)->first();
            $endorsed_benefeciary = Benefeciary::where('id', $endorsed_user->benefeciary_id)->first('user_id');
            $benefeciary_user = User::where('id', $endorsed_benefeciary->user_id)->first();
            $staffs = User::where('role', 2)->get();
            $focals = User::where('focal_role', $user->focal_role)->where('barangay_id', $user->barangay_id)->get();

            switch($request->status){
                case 0:
                    $message = 'Action Undone!';
                    Requests::where('id', $request->id)->update(['status' => 0, 'date_checked' => null, 'date_rejected' => null, 'rejected_by' => null, 'reason' => null]);

                    $details = [
                        'type' => 0,
                        'from' => auth()->user()->name,
                        'image' => auth()->user()->user_image,
                        'message' => 'Cancelled checking the new beneficiary request',
                        'url' => route('focal.new_beneficiary')
                    ];

                    $details_for_staff = [
                        'type' => 0,
                        'from' => auth()->user()->name,
                        'image' => auth()->user()->user_image,
                        'message' => 'Cancelled checking the new beneficiary request',
                        'url' => route('staff.new_beneficiaries')
                    ];

                    $details_for_beneficiary = [
                        'type' => 0,
                        'from' => auth()->user()->name,
                        'image' => auth()->user()->user_image,
                        'message' => 'Undo the response to your endorsement',
                        'url' => route('beneficiary.profile')
                    ];

                    $this->notificationService->sendNotification($staffs, $details_for_staff);

                    $this->notificationService->sendNotification($focals, $details);
                    
                    if($benefeciary_user != null){
                        $this->notificationService->sendNotification($benefeciary_user, $details_for_beneficiary);
                    }
                    break;
                case 1:
                    $message = 'Request Checked!';
                    Requests::where('id', $request->id)->update(['status' => 1, 'date_checked' => $dateNow, ]);

                    $details = [
                        'type' => 0,
                        'from' => auth()->user()->name,
                        'image' => auth()->user()->user_image,
                        'message' => 'Successfully checked your new beneficiary request',
                        'url' => route('focal.new_beneficiary')
                    ];

                    $details_for_staff = [
                        'type' => 0,
                        'from' => auth()->user()->name,
                        'image' => auth()->user()->user_image,
                        'message' => 'Successfully checked your new beneficiary request',
                        'url' => route('staff.new_beneficiaries')
                    ];

                    $details_for_beneficiary = [
                        'type' => 0,
                        'from' => auth()->user()->name,
                        'image' => auth()->user()->user_image,
                        'message' => 'Checked your endorsement',
                        'url' => route('beneficiary.profile')
                    ];

                    if($benefeciary_user != null){
                        $this->notificationService->sendNotification($benefeciary_user, $details_for_beneficiary);
                    }

                    $this->notificationService->sendNotification($staffs, $details_for_staff);

                    $this->notificationService->sendNotification($focals, $details);
                    
                    
                    break;
                case 4:
                    $message = 'Request Rejected!';
                    Requests::where('id', $request->id)->update(['status' => 4, 'date_rejected' => $dateNow, 'rejected_by' => "Barangay", 'reason' => $request->reason]);

                    $details = [
                        'type' => 0,
                        'from' => auth()->user()->name,
                        'image' => auth()->user()->user_image,
                        'message' => 'Rejected your new beneficiary request',
                        'url' => route('focal.new_beneficiary')
                    ];

                    $details_for_beneficiary = [
                        'type' => 0,
                        'from' => auth()->user()->name,
                        'image' => auth()->user()->user_image,
                        'message' => 'Rejected your endorsement',
                        'url' => route('beneficiary.profile')
                    ];

                    if($benefeciary_user != null){
                        $this->notificationService->sendNotification($benefeciary_user, $details_for_beneficiary);
                    }

                    $this->notificationService->sendNotification($focals, $details);
        
                    break;

            }

            return redirect()->back()->with('success', $message);
            

        }catch(\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function respondTransition($id, $status){
        try{
            $dateNow = Carbon::now()->format('Y/m/d');
            $message = '';
            $beneficiary_id = TransitionData::where('id', $id)->first(['benefeciary_id'])->benefeciary_id;
            $user_id = TransitionData::where('id', $id)->first('user_id')->user_id;

            $focal = User::where('id', $user_id)->first(['barangay_id', 'focal_role']);
            $ids = [$focal->focal_role, 1];
            $focal_personnel = User::whereIn('focal_role', $ids)->where('barangay_id', $focal->barangay_id)->get();

            
            switch($status){
                case 2:
                    $message = 'Request Approved!';
                    TransitionData::where('id', $id)->update(['status' => $status, 'date_approved' => $dateNow]);
                    Benefeciary::where('id', $beneficiary_id)->update(['benefeciary_type' => 1]);

                    $for_focal_details = [
                        'type' => 1,
                        'from' => auth()->user()->name,
                        'image' => auth()->user()->user_image,
                        'message' => 'Approved the request beneficiary for transitioning',
                        'url' => route('focal.transitioning')
                    ];

                    break;
                case 3:
                    $message = 'Request Rejected!';
                    TransitionData::where('id', $id)->update(['status' => $status, 'date_approved' => $dateNow]);

                    $for_focal_details = [
                        'type' => 1,
                        'from' => auth()->user()->name,
                        'image' => auth()->user()->user_image,
                        'message' => 'Rejected the request beneficiary for transitioning',
                        'url' => route('focal.transitioning')
                    ];
                    break;

            }

            $this->notificationService->sendNotification($focal_personnel, $for_focal_details);

            return redirect()->back()->with('success', $message);

        }catch(\Exception $e){
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    

}
