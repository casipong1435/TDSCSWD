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
                <th colspan="14" style="font-size: 20px; font-weight:bold; background: orange; text-align:center; border: 2px solid black">AICS BENEFICIARIES</th>
            </tr>
           <tr>
            <th style="font-size: 15px; font-weight:bold; background: orange;text-align:center; border: 2px solid black">Gis No.</th>
            <th style="font-size: 15px; font-weight:bold; background: orange;text-align:center; border: 2px solid black">Name</th>
            <th style="font-size: 15px; font-weight:bold; background: orange;text-align:center; border: 2px solid black">Date of Birth</th>
            <th style="font-size: 15px; font-weight:bold; background: orange;text-align:center; border: 2px solid black">Age</th>
            <th style="font-size: 15px; font-weight:bold; background: orange;text-align:center; border: 2px solid black">Sex</th>

            <th style="font-size: 15px; font-weight:bold; background: orange;text-align:center; border: 2px solid black">Address</th>
            <th style="font-size: 15px; font-weight:bold; background: orange;text-align:center; border: 2px solid black">Civil Status</th>
            <th style="font-size: 15px; font-weight:bold; background: orange;text-align:center; border: 2px solid black">Occupation</th>
            <th style="font-size: 15px; font-weight:bold; background: orange;text-align:center; border: 2px solid black">Education</th>
            <th style="font-size: 15px; font-weight:bold; background: orange;text-align:center; border: 2px solid black">Monthly Income</th>
            <th style="font-size: 15px; font-weight:bold; background: orange;text-align:center; border: 2px solid black">Type of Assistance</th>
            <th style="font-size: 15px; font-weight:bold; background: orange;text-align:center; border: 2px solid black">Amount Provided</th>
            <th style="font-size: 15px; font-weight:bold; background: orange;text-align:center; border: 2px solid black">Mobile Number</th>
            <th style="font-size: 15px; font-weight:bold; background: orange;text-align:center; border: 2px solid black">Provided on</th>
            
           </tr>
        </thead>
        <tbody>
            @foreach ($beneficiaries as $beneficiary)
            <tr>
                <td>{{ $beneficiary->id }}</td>
                <td>{{ $beneficiary->name }}</td>
                <td>{{ $beneficiary->birthdate }}</td>
                <td>{{ $beneficiary->age }}</td>
                <td>{{ $beneficiary->sex }}</td>
                <td>{{ $beneficiary->address }}</td>
                <td>{{ $beneficiary->civil_status }}</td>
                <td>{{ $beneficiary->occupation }}</td>
                <td>{{ $beneficiary->education }}</td>
                <td>{{ $beneficiary->monthly_income }}</td>
                <td>{{ $beneficiary->assistance_type->assistance_type }}</td>
                <td>{{ $beneficiary->amount_provided }}</td>
                <td>{{ $beneficiary->mobile_number }}</td>
                <td>{{ $beneficiary->date }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>