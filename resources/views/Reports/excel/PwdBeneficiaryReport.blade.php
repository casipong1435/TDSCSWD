<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Beneficiary Report</title>

    <style>
        body{
            margin-top: 5rem;
        }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th colspan="24" style="font-size: 20px; font-weight:bold; background: green; text-align:center; border: 2px solid black">PWD BENEFICIARIES</th>
            </tr>
           <tr>
                <th rowspan="2" style="font-size: 15px; font-weight:bold; background: green;text-align:center; border: 2px solid black">No.</th>
                <th colspan="4" style="font-size: 15px; font-weight:bold; background: green;text-align:center; border: 2px solid black">Name</th>

                <th rowspan="2" style="font-size: 15px; font-weight:bold; background: green;text-align:center; border: 2px solid black">Birthdate (MM/DD/YY)</th>
                <th rowspan="2" style="font-size: 15px; font-weight:bold; background: green;text-align:center; border: 2px solid black">Age</th>
                <th rowspan="2" style="font-size: 15px; font-weight:bold; background: green;text-align:center; border: 2px solid black">Civil Status</th>
                <th rowspan="2" style="font-size: 15px; font-weight:bold; background: green;text-align:center; border: 2px solid black">Educational Attainment</th>
                <th rowspan="2" style="font-size: 15px; font-weight:bold; background: green;text-align:center; border: 2px solid black">Religion</th>
                <th rowspan="2" style="font-size: 15px; font-weight:bold; background: green;text-align:center; border: 2px solid black">Occupaption/Work</th>
                <th rowspan="2" style="font-size: 15px; font-weight:bold; background: green;text-align:center; border: 2px solid black">Monthly Income</th>
                <th rowspan="2" style="font-size: 15px; font-weight:bold; background: green;text-align:center; border: 2px solid black">PWD Identification Card</th>
                <th rowspan="2" style="font-size: 15px; font-weight:bold; background: green;text-align:center; border: 2px solid black">Date PIC Issued</th>
                <th rowspan="2" style="font-size: 15px; font-weight:bold; background: green;text-align:center; border: 2px solid black">Status</th>

                <th colspan="5" style="font-size: 15px; font-weight:bold; background: green;text-align:center; border: 2px solid black">Address</th>

                <th colspan="3" style="font-size: 15px; font-weight:bold; background: green;text-align:center; border: 2px solid black">is the PWD Member?</th>

                <th rowspan="2" style="font-size: 15px; font-weight:bold; background: green;text-align:center; border: 2px solid black">Remarks</th>
           </tr>
           <tr>
            <th style="font-size: 15px; font-weight:bold; background: green;text-align:center; border: 2px solid black">Last Name</th>
            <th style="font-size: 15px; font-weight:bold; background: green;text-align:center; border: 2px solid black">First Name</th>
            <th style="font-size: 15px; font-weight:bold; background: green;text-align:center; border: 2px solid black">Middle Name</th>
            <th style="font-size: 15px; font-weight:bold; background: green;text-align:center; border: 2px solid black">Extension Name</th>

            <th style="font-size: 15px; font-weight:bold; background: green;text-align:center; border: 2px solid black">No. Street</th>
            <th style="font-size: 15px; font-weight:bold; background: green;text-align:center; border: 2px solid black">Barangay</th>
            <th style="font-size: 15px; font-weight:bold; background: green;text-align:center; border: 2px solid black">Municipality/City</th>
            <th style="font-size: 15px; font-weight:bold; background: green;text-align:center; border: 2px solid black">Province</th>
            <th style="font-size: 15px; font-weight:bold; background: green;text-align:center; border: 2px solid black">Region</th>

            <th style="font-size: 15px; font-weight:bold; background: green;text-align:center; border: 2px solid black">Pantawid Beneficiary (Y/N)</th>
            <th style="font-size: 15px; font-weight:bold; background: green;text-align:center; border: 2px solid black">Indigenous Person (Y/N)</th>
            <th style="font-size: 15px; font-weight:bold; background: green;text-align:center; border: 2px solid black">LGBTQ+ (Y/N)</th>
            
           </tr>
        </thead>
        <tbody>
            {{ $i = 1; }}
            @foreach ($beneficiaries as $beneficiary)
            <tr>
                <td>{{ $i }}</td>
                <td>{{ $beneficiary->last_name }}</td>
                <td>{{ $beneficiary->first_name }}</td>
                <td>{{ $beneficiary->middle_name }}</td>
                <td>{{ $beneficiary->extension_name }}</td>
                <td>{{ $beneficiary->date_of_birth }}</td>
                <td>{{ $beneficiary->age }}</td>
                <td>{{ $beneficiary->civil_status }}</td>
                <td>{{ $beneficiary->educational_attainment }}</td>
                <td>{{ $beneficiary->religion }}</td>
                <td>{{ $beneficiary->occupation }}</td>
                <td>{{ $beneficiary->monthly_income->income_range }}</td>
                <td>{{ $beneficiary->pwd->pwd_id_number }}</td>
                <td>{{ $beneficiary->pwd->date_pic_issued }}</td>
                <td>{{ $beneficiary->pwd->pic_status }}</td>
                <td>{{ $beneficiary->street }}</td>
                <td>{{ $beneficiary->barangay->barangay_name }}</td>
                <td>{{ $beneficiary->city }}</td>
                <td>{{ $beneficiary->province }}</td>
                <td>{{ $beneficiary->region }}</td>
                <td>{{ $beneficiary->pantawid_benefeciary == 0 ? 'N' : 'Y'}}</td>
                <td>{{ $beneficiary->indigenous_person == 0 ? 'N' : 'Y'}}</td>
                <td>{{ $beneficiary->lgbtq == 0 ? 'N' : 'Y'}}</td>
                <td>{{ $beneficiary->remarks }}</td>
            </tr>
            {{ $i++; }}
            @endforeach
        </tbody>
    </table>
</body>
</html>