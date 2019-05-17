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
            font-size: 12px;
        }

        /* Style the break */
        .break {
            background-color: #f1f1f1;
            padding: 0px;
            text-align: left;
            font-size: 10px;
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
        <h2><font color="blue">{{$jobSheet['job']['job_type']['name']}}, Job Order Number {{$jobSheet['job']['job_number']}},Customer: {{$jobSheet['job']['company']['name']}}
        Asset: {{$jobSheet['job']['asset']['name']}}</font></h2>
    </div>

    <div class="row">
        <div class="column">
            <div style="overflow-x:auto;">
                <table>
                    <tr>
                        <th bgcolor="#f1f1f1" colspan="2">
                            <h4>Allocated Job Information</h4>
                        </th>
                        <th bgcolor="#f1f1f1" colspan="2">
                            <h4>Job Sheet Information</h4>
                        </th>
                    </tr>
                    </tr>
                    <tr>
                        <td><font size="2">Asset Serial:<font color="blue"> {{$jobSheet['job']['asset']['serial']}}</font></font></td>
                        <td></td>
                        <td></td>
                        <td><font size="2">Asset BMB: <font color="blue"> {{$jobSheet['job']['asset']['bmb']}}</font></font></td>
                    </tr>
                    <tr>
                        <td><font size="2">Job Status: {{$jobSheet['job']['job_stat']['type']}} </font> </td>
                        <td></td>
                        <td></td>
                        <td><font size="2">SWMC No: {{$jobSheet['swmc']}}</font></td>
                    </tr>
                    <tr>
                        <td><font size="2">Job Date:<b> {{$jobSheet['job']['job_raised_date']}}</font></b></td>
                        <td></td>
                        <td></td>
                        <td><font size="2">Job Completed: {{$jobSheet['is_complete'] == 1 ? "Yes": "No" }}</font></td>
                    </tr>
                    <tr>
                        <td><font size="2">Job Date:<b> {{$jobSheet['job']['job_raised_date']}}</font></b></td>
                        <td></td>
                        <td></td>
                        <td><font size="2">Has Second Job Sheet: {{$jobSheet['second_trip'] == 1 ? "Yes": "No" }}</font></td>
                    </tr>
                    <tr>
                        <td><font size="2">Location & Distributor:{{$jobSheet['job']['location']['description']}}, {{$jobSheet['job']['location']['address']}}</font>,<font color="blue">{{$jobSheet['job']['location']['agent_id']}}</font>
                        </td>
                        <td></td>
                        <td></td>
                        <td><font size="2">Charge To:<font color="blue"> {{$jobSheet['job']['charge_to']}}</font></font></td>
                    </tr>
                    <tr>
                        <td colspan="2"><font size="2"><b>Job Description:</b>{!! $jobSheet['job']['description'] !!}</font></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="3"><font size="2"><b>Contractor :</b><font color="blue"> {{$jobSheet['job']['contractor']['name']}}</font></font></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="3"><font size="2"><b>Technician Comment :</b> {!! $jobSheet['comment'] !!}</font></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="3"><font size="2"><b>Customer Name :</b> <font color="blue"> {!! $jobSheet['customer_signature'] !!}</font></font></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <font size="2"><b>Technician Name:</b><font color="blue">{{$jobSheet['created_user']['first_name']}} {{$jobSheet['created_user']['last_name']}}</font></b></font>
                        </td>
                    </tr>
                    <tr>
                        <td>Additional Information</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><font size="2">Time In: {{$jobSheet['timein']}} Hr:Mins </font></td>
                        <td></td>
                        <td></td>
                        <td><font size="2">Run Flush:{{$jobSheet['run_flush'] == 1 ? "Yes": "No" }}</td>
                    </tr>
                    <tr>
                        <td><font size="2">Time Out: {{$jobSheet['timeout']}} Hr:Mins</font></td>
                        <td></td>
                        <td></td>
                        <td><font size="2">Product Checked: {{$jobSheet['product_checked'] == 1 ? "Yes": "No" }}</td>
                    </tr>
                    <tr>
                        <td><font size="2">On Site:<font color="blue"> {{$jobSheet['onstie']}}</font> Hr:Mins</font></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><font size="2">Travel Hours: <font color="blue"> {{$jobSheet['travel_hours']}} </font> Hr:Mins</font></td>
                        <td></td>
                        <td></td>
                        <td><font size="2">Total Kms:<b> <font color="blue"> {{$jobSheet['total_kms']}}</font></b></font></td>
                    </tr>
                    <tr>
                        <td><font size="2">Waiting Time:<b> {{$jobSheet['waiting_time']}} Hr:Mins</b></font></td>
                        <td></td>
                        <td></td>
                        <td><font size="2">Waiting Reason:<b> {{$jobSheet['waiting_reason']}}</b></td>
                    </tr>
                </table>
            </div>
            <div class="row">
                <div class="column">
                    <div style="overflow-x:auto;">
                       
                    </div>
                    <div class="column-three">
                        <p>
                            <font size="1">Returning machine for repair or return?</font>
                        </p>
                        <p><font size="1">If job involves extraction of machine and return to Nestle either for repair or return;
                            Please ensure machine is empty of all product and water before packaging for transport. Phone 0800 372 800 to notify once ready for pickup.</font>
                        </p>
                        <p> <font color="red" size="1">This JOB SHEET MUST BE FAXED TO 09275 7422 WITHIN 24 HOURS OF COMPLETION OF JOB, 
                            OR,EMAILED TO technical.support@nz.nestle.com INVOICE FOR THIS JOB MUST MATCH THIS JOB SHEET.</font>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>