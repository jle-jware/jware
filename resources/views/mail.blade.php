<!DOCTYPE html>
<html lang="en">

<head>
    <title>Nestle Professional</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        /* Style the header */
        .header {
            padding: 10px;
            text-align: center;
            font-size: 20px;
        }

        /* Style the break */
        .break {
            background-color: #f1f1f1;
            padding: 0px;
            text-align: left;
            font-size: 18px;
        }

        /* Create three equal columns that floats next to each other */
        .column {
            float: left;
            width: 100%;
        }

        .columnsecond {
            float: left;
            width: 100%;
        }

        .column-three {
            float: left;
            width: 100%;
        }

        /* Clear floats after the columns */
        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        /* Style the footer */
        .footer {
            padding: 10px;
            text-align: center;
        }

        /* Responsive layout - makes the three columns stack on top of each other instead of next to each other */
        @media (max-width: 600px) {
            .column {
                width: 100%;
            }
        }

        table {
            border-collapse: collapse;
            border-spacing: 0;
            width: 100%;
        }

        th,
        td {
            text-align: left;
            padding: 3px;
        }

        tr:nth-child(even) {
            
        }
    </style>
</head>

<body>
    <div class="header">
        <h2><font clolor="blue">{{$job_type['name']}} Job Order Number {{$job_number}}</font></h2>
    </div>

    <div class="row">
        <div class="column">
            <div style="overflow-x:auto;">
                <table>
                    <tr>
                        <th bgcolor="#f1f1f1" colspan="2">
                            <h3>Site Information</h3>
                        </th>
                        <th bgcolor="#f1f1f1" colspan="2">
                            <h3>Asset Model</h3>
                        </th>
                    </tr>
                    <tr>
                        <td>Customer Name:<font size="4"><b><font color="blue">{{$company['name']}}</font><b></font>
                        </td>
                        <td></td>
                        <td></td>
                        <td><b>Asset Model:<font color="blue"> {{$asset_model['name']}} {{$asset['asset_model']['name']}}</font></b></td>
                    </tr>
                    <tr>
                        <td>Address:<b>{{$location['description']}} - {{$location['address']}}</b></td>
                        <td></td>
                        <td></td>
                        <td><b>Asset Name: {{$asset['name']}}</b></td>
                    </tr>
                    <tr>
                        <td>Specific Location: {{$asset['specific_location']}}</td>
                        <td></td>
                        <td></td>
                        <td>Serial:<font color="blue"> {{$asset['serial']}} </font> - BMB: <font color="blue"> {{$asset['bmb']}}</font></td>
                    </tr>
                </table>
            </div>
            <div class="row">
                <div class="column">
                    <div style="overflow-x:auto;">
                        <table>
                            <tr>
                                <th bgcolor="#f1f1f1" style="text-align: left;">
                                    <h3>Additional Information</h3>
                                </th>
                            </tr>
                            @if (strlen($mail_description) > 0)
                                <tr>
                                    <td>Mail Description: {{$mail_description}}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endif
                                    <tr>
                                <td>Distributor: {{$location['agent_id']}}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><b>Contractor: <font color="blue"> {{$contractor['name']}}</font></b></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Job Raised Date: {{\Carbon\Carbon::parse($job_raised_date)->format('d-m-Y H:i:s A')}}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Required Finishing Date: {{\Carbon\Carbon::parse($required_date)->format('d-m-Y H:i:s A')}}
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Priority: {{$priority['name']}}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Job Status: {{$job_stat['type']}}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr style="border:2px solid red;">
                                <td>Job Description:<b><font color="blue">{!! $description !!}</font></b></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                    <div class="column-three">
                        <p>
                            <table>
                                <tr>
                                    <th bgcolor="#f1f1f1" style="text-align: left;">
                                        <h3></h3>
                                    </th>
                                </tr>
                            </table>
                        </p>
                        <p>
                            <font size="">Returning machine for repair or return?</font>
                        </p>
                        <p>If job involves extraction of machine and return to Nestle either for repair or return;
                            Please
                            ensure machine is empty of all product and water before packaging for
                            transport. Phone 0800 372 800 to notify
                            once ready for pickup.
                        </p>
                        <p> <font color="red">This JOB SHEET MUST BE FAXED TO 09275 7422 WITHIN 24 HOURS OF COMPLETION OF JOB, 
                            OR,EMAILED TO technical.support@nz.nestle.com INVOICE FOR THIS JOB MUST MATCH THIS JOB SHEET.</font>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>