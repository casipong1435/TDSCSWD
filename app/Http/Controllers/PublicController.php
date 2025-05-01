<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use App\Models\Benefeciary;
use App\Models\MonthlyIncome;
use App\Models\Program;
use App\Models\ProgramBeneficiary;
use App\Models\Pwd;
use App\Models\Senior;
use App\Models\User;
use App\Models\Women;
use App\Models\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Hash;
use App\Services\NotificationService;

class PublicController extends Controller
{

    protected $notificationService;

    public function __construct(){
        $this->notificationService = new NotificationService();
    }

    public function index(Request $request)
    {
        $searchInput = $request->input('searchInput');
        $beneficiaries = Benefeciary::with('barangay')->where('approved_status', 1)->get();
        $programs = Program::with('program_beneficiary.beneficiary.barangay')->where('status', 3)->paginate(20);

        return Inertia::render('Public/public', compact('beneficiaries', 'programs'));
    }

    public function application(Request $request)
    {
        $barangays = Barangay::get();
        $monthly_income = MonthlyIncome::get();

        return Inertia::render('Public/Application', compact('barangays', 'monthly_income'));
    }

    public function validateApplicationDetails(Request $request)
    {
        try {
            $request->validate([
                'benefeciary_type' => 'required',
                'first_name' => 'required',
                'last_name' => 'required',
                'date_of_birth' => 'required',
                'civil_status' => 'required',
                'sex' => 'required',
                'religion' => 'required',
                'educational_attainment' => 'required',
                'monthly_income' => 'required',
                'contact_number' => 'required',
                'barangay_id' => 'required',
                'street' => 'required',
                'image' => 'image|mimes:jpg,jpeg,png,gif|max:2048',
            ]);

            $duplicate = Benefeciary::whereRaw('LOWER(first_name) = ?', [strtolower($request->first_name)])
                ->whereRaw('LOWER(last_name) = ?', [strtolower($request->last_name)])
                ->where('date_of_birth', $request->date_of_birth)
                ->first();

            if ($duplicate) {
                return redirect()->back()->with('error', 'Beneficiary already exists.');
            }

            return redirect()->back()->with('success', 'Details Completed!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function apply(Request $request)
    {
        try {

            $request->validate([
                'username' => 'required|min:6|unique:users,username',
                'password' => ['required', 'confirmed', Rules\Password::defaults()]
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '-' . Str::random(5) . '.' . $file->getClientOriginalExtension();
                $path = $file->move('profile_images', $filename);
            } else {
                $filename = '';
            }

            $user_values = [
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role' => 4,
                'focal_role' => 0,
                'barangay_id' => $request->barangay_id,
                'name' => $request->first_name.' '.$request->last_name
            ];

            $user = User::create($user_values);

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
                'benefeciary_type' => $request->benefeciary_type,
                'user_id' => $user->id,
                'approved_status' => 3
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

            $details = [
                'type' => 0,
                'from' => $request->first_name.' '.$request->last_name,
                'image' => $filename,
                'message' => 'Applied to be beneficiary',
                'url' => route('focal.application')
            ];

            $focal = User::where('focal_role', $request->benefeciary_type)->where('barangay_id', $request->barangay_id)->get();
            $this->notificationService->sendNotification($focal, $details);

            return redirect()->back()->with('success', 'Application Completed!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function dashboard(){
        $benefeciary = Benefeciary::where('user_id', auth()->user()->id)->first(['id', 'date_rejected', 'date_approved', 'reason', 'approved_status']);
        /* There are the attributes of Requests
        'status', 'benefeciary_id', 'date_checked', 'date_verified', 'date_approved', 'date_rejected, 'rejected_by', 'reason'*/
        $request = Requests::where('benefeciary_id', $benefeciary->id)->first();
        return Inertia::render('Public/Pages/Dashboard', compact('benefeciary', 'request'));
    }

    public function program(){
        $benefeciary_id = Benefeciary::where('user_id', auth()->user()->id)->first('id')->id;
        $programs = ProgramBeneficiary::with(['program.program_beneficiary.beneficiary.barangay'])->where('benefeciary_id', $benefeciary_id)->paginate(20);

        //dd($programs);
        return Inertia::render('Public/Pages/Program', compact('programs'));
    }

    public function profile(){

        $barangays = Barangay::get();
        $monthly_income = MonthlyIncome::get();
        $benefeciary = Benefeciary::with(['pwd', 'senior', 'women'])->where('user_id', auth()->user()->id)->first();
        $request_info = Requests::where('benefeciary_id', $benefeciary->id)->first();
        return Inertia::render('Public/Pages/Profile', ['benefeciary' => $benefeciary, 'barangays' => $barangays, 'monthly_income' => $monthly_income, 'request_info' => $request_info]);
    }
}
