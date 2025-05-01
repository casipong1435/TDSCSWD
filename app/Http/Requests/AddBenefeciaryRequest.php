<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddBenefeciaryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */

    public function authorize(): bool
    {
        return auth()->check();
    }

    

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        switch(auth()->user()->focal_role){
            case 1:
                return [
                    'benefeciary_type' => 'required',
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'date_of_birth' => 'required',
                    'civil_status' => 'required',
                    'sex' => 'required',
                    'religion' => 'required',
                    'educational_attainment' => 'required',
                    'monthly_income' => 'required',
                    'senior_id_number' => 'required|numeric',
                    'date_sic_issued' => 'required',
                    'sic_status' => 'required',
                    'contact_number' => 'required',
                    'barangay_id' => 'required',
                    'street' => 'required',
                    'socpen_benefeciary' => 'required',
                    'pantawid_benefeciary' => 'required',
                    'indigenous_person' => 'required',
                    'lgbtq' => 'required',
                    'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
                ];
          
            case 2:
                return [
                    'benefeciary_type' => 'required',
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'date_of_birth' => 'required',
                    'civil_status' => 'required',
                    'sex' => 'required',
                    'religion' => 'required',
                    'educational_attainment' => 'required',
                    'monthly_income' => 'required',
                    'pwd_id_number' => 'required|numeric',
                    'date_pic_issued' => 'required',
                    'pic_status' => 'required',
                    'contact_number' => 'required',
                    'barangay_id' => 'required',
                    'street' => 'required',
                    'pantawid_benefeciary' => 'required',
                    'indigenous_person' => 'required',
                    'lgbtq' => 'required',
                    'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
                ];
           
            case 3:
                return [
                    'benefeciary_type' => 'required',
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'date_of_birth' => 'required',
                    'civil_status' => 'required',
                    'sex' => 'required',
                    'religion' => 'required',
                    'educational_attainment' => 'required',
                    'monthly_income' => 'required',
                    'kalipi_id_number' => 'required|numeric',
                    'date_kic_issued' => 'required',
                    'kic_status' => 'required',
                    'contact_number' => 'required',
                    'barangay_id' => 'required',
                    'street' => 'required',
                    'pantawid_benefeciary' => 'required',
                    'indigenous_person' => 'required',
                    'lgbtq' => 'required',
                    'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
                ];
            default:
                return [];
        }
        
    }

    public function messages()
    {

        switch(auth()->user()->focal_role){
            case 1:
                return [
                    'benefeciary_type.required' => ' Benefeciary Type is required',
                    'first_name.required' => 'First Name is required',
                    'last_name.required' => 'Last name is required',
                    'date_of_birth.required' => 'Date of Birth required',
                    'civil_status.required' => 'Civil Status is required',
                    'sex.required' => 'Sex required',
                    'religion.required' => 'Religion is required',
                    'educational_attainment.required' => 'Select Educational Attainment',
                    'monthly_income.required' => 'Monthly Income required',
                    'senior_id_number.required' => 'Senior ID Card Number is required',
                    'date_sic_issued.required' => 'ID Card Date Issues is required',
                    'sic_status.required' => ' ID Status is required',
                    'contact_number.required' => 'Contact Number is required',
                    'barangay_id.required' => 'Barangay is required',
                    'street.required' => 'Street is required',
                    'socpen_benefeciary.required' => 'Tick checkbox for SOCPEN Benefeciary',
                    'pantawid_benefeciary.required' => 'Tick checkbox for Pantawid Benefeciary',
                    'indigenous_person.required' => 'Tick checkbox for Indigenous Person',
                    'lgbtq.required' => 'Tick checkbox for LGBTQ',
                    'image.mimes' => 'The image must be a file of type: jpg, jpeg, png, gif',
                    'image.max' => 'The image size must not exceed 2MB',
                ];
     
            case 2:
                return [
                    'benefeciary_type.required' => ' Benefeciary Type is required',
                    'first_name.required' => 'First Name is required',
                    'last_name.required' => 'Last name is required',
                    'date_of_birth.required' => 'Date of Birth required',
                    'civil_status.required' => 'Civil Status is required',
                    'sex.required' => 'Sex required',
                    'religion.required' => 'Religion is required',
                    'educational_attainment.required' => 'Select Educational Attainment',
                    'monthly_income.required' => 'Monthly Income required',
                    'pwd_id_number.required' => 'PWD ID Card Number is required',
                    'date_pic_issued.required' => 'ID Card Date Issues is required',
                    'pic_status.required' => ' ID Status is required',
                    'contact_number.required' => 'Contact Number is required',
                    'barangay_id.required' => 'Barangay is required',
                    'street.required' => 'Street is required',
                    'pantawid_benefeciary.required' => 'Tick checkbox for Pantawid Benefeciary',
                    'indigenous_person.required' => 'Tick checkbox for Indigenous Person',
                    'lgbtq.required' => 'Tick checkbox for LGBTQ',
                    'image.mimes' => 'The image must be a file of type: jpg, jpeg, png, gif',
                    'image.max' => 'The image size must not exceed 2MB',
                ];
      
            case 3:
                return [
                    'benefeciary_type.required' => ' Benefeciary Type is required',
                    'first_name.required' => 'First Name is required',
                    'last_name.required' => 'Last name is required',
                    'date_of_birth.required' => 'Date of Birth required',
                    'civil_status.required' => 'Civil Status is required',
                    'sex.required' => 'Sex required',
                    'religion.required' => 'Religion is required',
                    'educational_attainment.required' => 'Select Educational Attainment',
                    'monthly_income.required' => 'Monthly Income required',
                    'kalipi_id_number.required' => 'Kalipi ID Card Number is required',
                    'date_kic_issued.required' => 'ID Card Date Issues is required',
                    'kic_status.required' => ' ID Status is required',
                    'contact_number.required' => 'Contact Number is required',
                    'barangay_id.required' => 'Barangay is required',
                    'street.required' => 'Street is required',
                    'pantawid_benefeciary.required' => 'Tick checkbox for Pantawid Benefeciary',
                    'indigenous_person.required' => 'Tick checkbox for Indigenous Person',
                    'lgbtq.required' => 'Tick checkbox for LGBTQ',
                    'image.mimes' => 'The image must be a file of type: jpg, jpeg, png, gif',
                    'image.max' => 'The image size must not exceed 2MB',
                ];

            default:
                return [];
        }
        
    }
}
