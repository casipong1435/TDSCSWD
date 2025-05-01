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
        .header,
        .footer {
            width: 100%;
            position: fixed;
        }
        .header {
            top: 0px;
        }
        .img-left{
            position: absolute;
            top: 20;
            left:10;
        }
        .img-left-1{
            position: absolute;
            top: 5;
            left: 70;
        }
        .img-right{
            position: absolute;
            top: 20;
            right:20;
        }
        .header-content{
            text-align: center;
        }
        .tcgc{
            font-weight: bold;
        }
        .accession{
            color: red;
        }
        .title{
            margin-top: 5rem;
            margin-bottom: 2rem;
            font-size: 35px;
            font-weight: bold;
            text-align: center;
        }
        table{
            border-collapse: collapse;
            width: 100%;
            
        }
        th, td, tr{
            border: 1px solid black;
        }
        th,td{
            padding: 8px;
            text-align: center;
            font-family: sans-serif;
        }
        th{
            font-size: 12px;
            background: yellow;
        }
        td{
            font-size: 10px;
            color: #302f2f;
        }
    </style>
</head>

@foreach ($programs as $program)
<body>
    
    <div class="header">
        <div class="header-content">
            <div class="img-left">
                <img src="assets/images/tangubcity.png" class="bg" width="90" height="90">
            </div>
            <div class="img-left-1">
                <img src="assets/images/bagongpilinas.png" class="bg" width="120" height="120">
            </div>
            <div class="center">
                <div class="tcgc">Republic of the Philippines</div>
                <div class="lrc">CITY OF TANGUB</div>
                <div class="maloro"><i>God-Centered CIty</i></div>
                <div class="tcgc">City Social Welfare and Development Office</div>
                <div class="lrc">www.tangubcity.gov.ph</div>
                <div class="lrc">Tel # 322 0332</div>
                <div class="lrc"><a href="cswd.tangub@yahoo.com">cswd.tangub@yahoo.com</a></div>
            </div>
            <div class="img-right">
                <img src="assets/images/cswd.png" class="bg" width="90" height="90">
            </div>
        </div>
        
    </div>
    
    <div class="content">
        <div class="title">Program Report</div>
        <div style="text-align: left; margin-bottom: 1rem">
            <div>As of: {{ $date_from.' - '.$date_to }}</div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>
                        Title:
                    </th>
                    <th colspan="5" style="text-align: left">
                        {{ $program->title }}
                    </th>
                </tr>  
                <tr>
                    <th>
                        Purpose:
                    </th>
                    <th colspan="5" style="text-align: left">
                        {{ $program->purpose }}
                    </th>
                </tr>  
                <tr>
                    <th>
                        Venue:
                    </th>
                    <th colspan="5" style="text-align: left">
                        {{ $program->venue }}
                    </th>
                </tr>
                <tr>
                    <th colspan="6" style="text-align: center">Beneficiaries</th>
                </tr>
                <tr>
                    <th>No.</th>
                    <th>Name</th>
                    <th>Birthdate</th>
                    <th>Age</th>
                    <th>Sex</th>
                    <th>Barangay</th>
                </tr>
            </thead> 
            <tbody>
                {{ $i = 1; }}
                @foreach ($program->program_beneficiary as $beneficiary_data)
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $beneficiary_data->beneficiary->first_name.' '.$beneficiary_data->beneficiary->middle_name.' '.$beneficiary_data->beneficiary->last_name }}</td>
                    <td>{{ $beneficiary_data->beneficiary->date_of_birth }}</td>
                    <td>{{ $beneficiary_data->beneficiary->age }}</td>
                    <td>{{ $beneficiary_data->beneficiary->sex }}</td>
                    <td>{{ $beneficiary_data->beneficiary->barangay->barangay_name }}</td>
                </tr>
                {{ $i++; }}
                @endforeach
            </tbody>
        </table>
    </div>
    
</body>
@endforeach
</html>