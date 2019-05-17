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
        <h4><font color="blue">{{$job['job_type']['name']}}, Job Order Number {{$job['job_number']}},
        Customer: {{$job['company']['name']}},Asset: {{$job['asset']['name']}},Location: {{$job['location']['address']}}</font></h4>
    </div>

    <div class="row">
        <div class="column">
            <div style="overflow-x:auto;">
                <table>
                    <tr>
                        <th bgcolor="#f1f1f1" colspan="2">
                            <h3>Job Sheet Information</h3>
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
                        <td>Asset Serial:<font color="blue"> {{$job['asset']['serial']}}</font></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Asset BMB: <font color="blue"> {{$job['asset']['bmb']}}</font></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>SWMC: {{$swmc}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Date: {{$job_date}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Time In: {{$timein}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Time Out: {{$timeout}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>On Site: <font color="blue"> {{$onstie}}</font></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Travel Hours: {{$travel_hours}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Total Kms:<font color="blue"> {{$total_kms}}</font></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Waiting Time: {{$waiting_time}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Waiting Reason: {{$waiting_reason}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Technician Comment: {!! $comment !!}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><b>Customer Name:  {{$customer_signature}}</b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><b>Technician Name: <font color="blue"> {{$created_user['first_name']}} {{$created_user['last_name']}}</font></b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><b>Contractor Name: <font color="blue"> {{$job['contractor']['name']}}</font></b></td>
                    </tr>
                    <tr>
                        <td>Run Flush:{{$run_flush == 1 ? "Yes": "No" }}, Work Area Tied:{{$work_area == 1 ? "Yes": "No" }},Product Checked:{{$product_checked == 1 ? "Yes": "No" }} </td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>

</html>