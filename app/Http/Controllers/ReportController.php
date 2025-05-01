<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PwdBeneficiaryReport;
use App\Exports\ProgramsReport;
use App\Exports\WomenBeneficiaryReport;
use App\Exports\SeniorBeneficiaryReport;
use App\Exports\AicsBeneficiaryReport;
use App\Models\Benefeciary;
use App\Models\Program;
use App\Models\AicsGis;
use App\Models\AicsStaff;
use Carbon\Carbon;
use PDF;

class ReportController extends Controller
{
    


    public function programReport($from, $to){

        $programs = Program::with(['program_beneficiary',
        'program_beneficiary.beneficiary',
        'program_beneficiary.beneficiary.barangay'])
        ->where('status', 3)
        ->whereDate('start_date', '>=', $from)
        ->whereDate('start_date', '<=', $to)
        ->get();

        $date_from = Carbon::parse($from)->format('F j, Y');
        $date_to = Carbon::parse($to)->format('F j, Y');

        $pdf = PDF::loadView('Reports.pdf.ProgramReport', ['programs' => $programs, 'date_from' => $date_from, 'date_to' => $date_to])->setPaper('folio');

        return $pdf->stream('Reports.pdf.ProgramReport');
    }

    public function individualProgramReport($id){
        $program = Program::with(['program_beneficiary',
        'program_beneficiary.beneficiary',
        'program_beneficiary.beneficiary.barangay'])
        ->where('id', $id)
        ->first();

        $pdf = PDF::loadView('Reports.pdf.IndividualProgramReport', ['program' => $program])->setPaper('folio');

        return $pdf->stream('Reports.pdf.IndividualProgramReport');
    }
    
    public function exportToExcelProgram($from, $to){
        $programs = Program::with(['program_beneficiary',
        'program_beneficiary.beneficiary',
        'program_beneficiary.beneficiary.barangay'])
        ->where('status', 3)
        ->whereDate('start_date', '>=', $from)
        ->whereDate('start_date', '<=', $to)
        ->get();
   
        return Excel::download(new ProgramsReport($programs), 'programs'.Carbon::now().'.xlsx');
    }
    
    public function exportToExcelPwd($data){

        $barangay_ids = json_decode($data);

        $beneficiaries = Benefeciary::with(['barangay', 'pwd', 'monthly_income'])->where('approved_status', 1)->where('benefeciary_type', 2)->whereIn('barangay_id', $barangay_ids)->get();
        
        
        return Excel::download(new PwdBeneficiaryReport($beneficiaries), 'pwd_beneficiary'.Carbon::now().'.xlsx');
    }

    public function exportToExcelWomen($data){

        $barangay_ids = json_decode($data);

        $beneficiaries = Benefeciary::with(['barangay', 'women', 'monthly_income'])->where('approved_status', 1)->where('benefeciary_type', 3)->whereIn('barangay_id', $barangay_ids)->get();

        return Excel::download(new WomenBeneficiaryReport($beneficiaries), 'women_beneficiary'.Carbon::now().'.xlsx');
    }

    public function exportToExcelSenior($data){
        $barangay_ids = json_decode($data);

        $beneficiaries = Benefeciary::with(['barangay', 'senior', 'monthly_income'])->where('approved_status', 1)->where('benefeciary_type', 1)->whereIn('barangay_id', $barangay_ids)->get();
        //dd($beneficiaries);
        return Excel::download(new SeniorBeneficiaryReport($beneficiaries), 'senior_beneficiary'.Carbon::now().'.xlsx');
    }

    public function exportToPdfAics($data){

        $data = json_decode($data);

        //dd($data->assistance_ids);

        $assistance_ids = $data->assistance_ids;
        $date_from = $data->date_from;
        $date_to = $data->date_to;

        $beneficiaries = AicsGis::with(['aics_compositions', 'assistance_type', 'interviewee_staff', 'reviewee_staff'])
        ->whereIn('type_of_assistance', $assistance_ids)
        ->where('status', 1)
        ->where('date', '>=', $date_from)
        ->where('date', '<=', $date_to)
        ->get();


        $mayor = AicsStaff::where('role', 0)->orderBy('created_at', 'desc')->first();


        $pdf = PDF::loadView('Reports.pdf.AicsReport', ['beneficiaries' => $beneficiaries, 'mayor' => $mayor])->setPaper('folio');

        return $pdf->stream('Reports.pdf.AicsReport');
    }

    public function exportToExcelAics($data){
        $data = json_decode($data);

        //dd($data->assistance_ids);

        $assistance_ids = $data->assistance_ids;
        $date_from = $data->date_from;
        $date_to = $data->date_to;

        $beneficiaries = AicsGis::with(['aics_compositions', 'assistance_type', 'interviewee_staff', 'reviewee_staff'])
        ->whereIn('type_of_assistance', $assistance_ids)
        ->where('status', 1)
        ->where('date', '>=', $date_from)
        ->where('date', '<=', $date_to)
        ->get();
        return Excel::download(new AicsBeneficiaryReport($beneficiaries), 'aics_beneficiaries'.Carbon::now().'.xlsx');
    }


    public function findPrograms(Request $request){
        $date_from = $request->input('date_from');
        $date_to = $request->input('date_to');

        $programs = Program::with('program_beneficiary')
        ->where('status', 3)
        ->whereDate('start_date', '>=', $date_from)
        ->whereDate('start_date', '<=', $date_to)
        ->get();

        return response()->json($programs);
    }

    public function findBeneficiaries(Request $request){
        $barangay_ids = json_decode($request->input('barangay_ids'));
        $category = $request->input('category');

        $beneficiaries = Benefeciary::where('approved_status', 1)->where('benefeciary_type', $category)->whereIn('barangay_id', $barangay_ids)->count();


        return response()->json($beneficiaries);

    }

    public function findAicsBeneficiaries(Request $request){
        $assistance_ids = json_decode($request->input('assistance_ids'));
        $date_from = $request->input('date_from');
        $date_to = $request->input('date_to');


        $beneficiaries = AicsGis::where('status', 1)
        ->whereIn('type_of_assistance', $assistance_ids)
        ->where('date', '>=', $date_from)
        ->where('date', '<=', $date_to)
        ->count();

        

        return response()->json($beneficiaries);

    }
}
