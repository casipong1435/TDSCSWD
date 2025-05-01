<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Programs Report</title>

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
                <th colspan="7" style="font-size: 20px; font-weight:bold; background: yellow; text-align:center; border: 2px solid black">PROGRAMS</th>
            </tr>
            <tr>
                <th style="font-size: 15px; font-weight:bold; background: yellow; text-align:center; border: 2px solid black">No.</th>
                <th style="font-size: 15px; font-weight:bold; background: yellow; text-align:center; border: 2px solid black">Title</th>
                <th style="font-size: 15px; font-weight:bold; background: yellow; text-align:center; border: 2px solid black">Purpose</th>
                <th style="font-size: 15px; font-weight:bold; background: yellow; text-align:center; border: 2px solid black">Venue</th>
                <th style="font-size: 15px; font-weight:bold; background: yellow; text-align:center; border: 2px solid black">No. of Beneficiaries</th>
                <th style="font-size: 15px; font-weight:bold; background: yellow; text-align:center; border: 2px solid black">Start Date</th>
                <th style="font-size: 15px; font-weight:bold; background: yellow; text-align:center; border: 2px solid black">End Date</th>
            </tr>
        </thead>
        <tbody>
            {{ $i = 1; }}
            @foreach ($data as $program)
            <tr>
                <td>{{ $i }}</td>
                <td>{{ $program->title }}</td>
                <td>{{ $program->purpose }}</td>
                <td>{{ $program->venue }}</td>
                <td>{{ count($program->program_beneficiary) }}</td>
                <td>{{ $program->start_date }}</td>
                <td>{{ $program->end_date }}</td>
            </tr>
            {{ $i++; }}
            @endforeach
        </tbody>
    </table>
</body>
</html>