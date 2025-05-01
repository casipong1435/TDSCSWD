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
            left: 110;
        }
        .img-left-1{
            position: absolute;
            top: 5;
            left: 190;
        }
        .img-right{
            position: absolute;
            top: 20;
            right: 200;
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
        <div class="title">Women Beneficiaries</div>
        <table>
            <thead>
                <tr>
                    <th colspan="25">WOMEN BENEFICIARIES</th>
                </tr>
               <tr>
                    <th></th>
                    <th colspan="4">Name</th>
                    <th rowspan="2">Birthdate (MM/DD/YY)</th>
                    <th rowspan="2">Age</th>
                    <th rowspan="2">Civil Status</th>
                    <th rowspan="2">Educational Attainment</th>
                    <th rowspan="2">Religion</th>
                    <th rowspan="2">Occupaption/Work</th>
                    <th rowspan="2">Monthly Income</th>
                    <th rowspan="2">SIC</th>
                    <th rowspan="2">Date SIC Issued</th>
                    <th rowspan="2">Status</th>
                    <th colspan="6">Address</th>
                    <th colspan="4">is the Senior Citizen?</th>
                    <th rowspan="2">Remarks</th>
               </tr>
               <tr>
                <th>No.</th>
                <th>Last Name</th>
                <th>First Name</th>
                <th>Middle Name</th>
                <th>Extension Name</th>
                <th>No. Street</th>
                <th>Barangay</th>
                <th>Municipality/City</th>
                <th>Province</th>
                <th>Region</th>
                <th>SOCPEN Beneficiary (Y/N)</th>
                <th>Pantawid Beneficiary (Y/N)</th>
                <th>Indigenous Person (Y/N)</th>
                <th>LGBTQ+ (Y/N)</th>
               </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Casipong</td>
                    <td>Christopher</td>
                    <td>Matos</td>
                    <td>Jr.</td>
                    <td>04/12/01</td>
                    <td>23</td>
                    <td>Single</td>
                    <td>College Level</td>
                    <td>Roman Catholic</td>
                    <td>Teacher</td>
                    <td>14000</td>
                    <td>1234-5678-90</td>
                    <td>12/12/20</td>
                    <td>Renewal</td>
                    <td>P-Madanihon</td>
                    <td>Tinago</td>
                    <td>Ozamiz City</td>
                    <td>Misamis Occidental</td>
                    <td>Region 10</td>
                    <td>Y</td>
                    <td>Y</td>
                    <td>Y</td>
                    <td>Y</td>
                    <td>Y</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>