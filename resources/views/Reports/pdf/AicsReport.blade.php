<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>General Intake Sheet</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 700px;
            margin: 0 auto;
            padding: 10px;
            border: 1px solid #000;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            line-height: 5px;
        }

        .header img {
            height: 50px;
            margin-right: 10px;
        }

        .title {
            font-size: 14px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table, th, td {
            border: 1px solid black;
        }

        td, th {
            padding: 5px;
            text-align: left;
        }

        .section-title {
            font-weight: bold;
            background-color: #f0f0f0;
            padding: 5px;
        }

        .signature-section {
            margin-top: 20px;
        }

        .signature-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }

        .signature-box {
            flex: 1;
            border-top: 1px solid black;
            text-align: center;
            margin-right: 20px;
            font-size: 12px;
        }

        .thumb-box {
            width: 100px;
            height: 100px;
            border: 1px solid black;
            text-align: center;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
@foreach ($beneficiaries as $beneficiary)
<body>
    <div class="container">
        <div class="header">
            <img src="assets/images/tangubcity.png" alt="TANGUB CITY" style="position: absolute; left: 50px; top: 50px; width: 70; height: 65">
            <img src="assets/images/bagongpilinas.png" alt="Bagong Pilipans" style="position: absolute; left: 140px; top: 40px; width: 80; height: 80">
            <img src="assets/images/cswd.png" alt="CSWD" style="position: absolute; right: 100px; top: 65px; width: 60; height: 55">
            <h1 class="title">Republic of the Philippines</h1>
            <p>CITY OF TANGUB</p>
            <i>God-Centered City</i>
            <p>City Social Welfare and Development Office</p>
            <p>www.tangubcity.gov.ph</p>
            <p>Tel: (123) 322-0332</p>
            <a href="cswd.tangub@yahoo.com">cswd.tangub@yahoo.com</a>
            
        </div>
        <div style="text-align: center">
            <h3>GENERAL INTAKE SHEET</h3>
        </div>
        <table>
            
            <tr>
                <td>GIS No.: {{ $beneficiary->id }}</td>
                <td>Date: {{ $beneficiary->date }}</td>
            </tr>
        </table>

        <p class="section-title">I. IMPORMASYON NG KINATAWAN</p>
        <table>
            <tr>
                <td>Name: {{ $beneficiary->ik_name }}</td>
                <td>Birthdate: {{ $beneficiary->ik_birthdate }}</td>
            </tr>
            <tr>
                <td>Age: {{ $beneficiary->ik_age }}</td>
                <td>Sex: {{ $beneficiary->ik_sex }}</td>
            </tr>
            <tr>
                <td>Address: {{ $beneficiary->ik_address }}</td>
                <td>Civil Status: {{ $beneficiary->ik_civil_status }}</td>
                
            </tr>
            <tr>
                <td>Occupation: {{ $beneficiary->ik_occupation }}</td>
                <td>Education: {{ $beneficiary->ik_education }}</td>
                
            </tr>
            <tr>
                <td>Monthly Income: {{ $beneficiary->ik_monthly_income }}</td>
                <td>Mobile Number: {{ $beneficiary->ik_mobile_number }}</td>
                
            </tr>
            <tr>
                <td>Relation to Beneficiary: {{ $beneficiary->relation_to_beneficiary }}</td>
                <td></td>
            </tr>
        </table>

        <p class="section-title">II. IMPORMASYON NG BENEPISYARYO</p>
        <table>
            <tr>
                <td>Name: {{ $beneficiary->name }}</td>
                <td>Birthdate: {{ $beneficiary->birthdate }}</td>
            </tr>
            <tr>
                <td>Age: {{ $beneficiary->age }}</td>
                <td>Sex: {{ $beneficiary->sex }}</td>
            </tr>
            <tr>
                <td>Address: {{ $beneficiary->address }}</td>
                <td>Civil Status: {{ $beneficiary->civil_status }}</td>
                
            </tr>
            <tr>
                <td>Occupation: {{ $beneficiary->occupation }}</td>
                <td>Education: {{ $beneficiary->education }}</td>
                
            </tr>
            <tr>
                <td>Monthly Income: {{ $beneficiary->monthly_income }}</td>
                <td>Mobile Number: {{ $beneficiary->mobile_number }}</td>
                
            </tr>
        </table>

        <p class="section-title">III. KOMPOSISYON NG PAMILYA</p>
        <table>
            <tr>
                <th>Name</th>
                <th>Age</th>
                <th>Relation to Client</th>
                <th>Education</th>
                <th>Occupation</th>
                <th>Monthly Income</th>
            </tr>
            @foreach ($beneficiary->aics_compositions as $composition)
                <tr>
                    <td>{{ $composition->comp_name }}</td>
                    <td>{{ $composition->comp_age }}</td>
                    <td>{{ $composition->comp_relation_to_client }}</td>
                    <td>{{ $composition->comp_education }}</td>
                    <td>{{ $composition->comp_occupation }}</td>
                    <td>{{ $composition->comp_monthly_income }}</td>
                </tr>
            @endforeach
        </table>

        <p class="section-title">IV. SOCIAL WORKER'S ASSESSMENT</p>
        <table>
            <tr>
                <td style="height: 50px;">{{ $beneficiary->social_worker_assessment }}</td>
            </tr>
        </table>

        <p class="section-title">V. TYPE OF ASSISTANCE</p>
        <table>
            <tr>
                <td>Type of Assistance: {{ $beneficiary->assistance_type->assistance_type }}</td>
                <td>Amount Provided: {{ $beneficiary->amount_provided }}</td>
            </tr>
        </table>

        <table>
            <tr>
                <td>
                    <div style="padding: 1rem; text-align: center">
                        <div style="margin-top: 1rem">
                            <hr>
                            <div style="margin-top: 0.5rem; margin-bottom:0.5rem">Signature Over Printed Name of Client</div>
                            <div class="thumb-box" style="margin-left: 4.5rem">
                                
                            </div>
                            <div style="margin-top: 0.5rem; margin-bottom:0.5rem">
                                <i>Thumb Mark (if unable to sign)</i>
                            </div>
                        </div>
                    </div>
                </td>
                <td colspan="2">
                    <table style="border: none; height: full">
                        <tr style="border: none">
                            <td style="border: 0">
                                <div style="margin-bottom: 0.5rem">Interviewed/Prepared By:</div>
                                <div style="margin-bottom: 0.5rem"><u>{{ $beneficiary->interviewee_staff->name }}</u></div>
                                <div>Postion: <u>{{ $beneficiary->interviewee_staff->position }}</u></div>
                            </td>
                            <td style="border: none">
                                <div style="margin-bottom: 0.5rem">Reviewed/Recommending Approval:</div>
                                <div style="margin-bottom: 0.5rem"><u>{{ $beneficiary->reviewee_staff->name }}</u></div>
                                <div>Postion: <u>{{ $beneficiary->reviewee_staff->position }}</u></div>
                            </td>
                        </tr>
                        <tr style="border: none">
                            <td colspan="2" style="border: none">
                                <div style="margin: 0.5rem 0;">Approved by:</div>
                                <div style="text-align: center">
                                    <h4><u>{{ $mayor->name  }}</u></h4>
                                    <span>{{ $mayor->position }}</span>
                                </div>
                            </td>
                        </tr>
                    </table>
                    
                    
                </td>
            </tr>
        </table>
    
    </div>
</body>
@endforeach
</html>
