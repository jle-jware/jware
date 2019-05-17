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
        <h3><font color="blue">{{$job['job_type']['name']}} Job Order Number {{$job['job_number']}} </font> </h3>
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
                        <td>Customer Name:<font size="4"><b><font color="blue">{{$job['company']['name']}}</font><b></font>
                        </td>
                        <td></td>
                        <td></td>
                        <td><b><font size="2">Asset Model:<font color="blue"> {{$job['asset']['asset_model']['name']}} {{$job['asset_model']['name']}}</font></font></b>
                        </td>
                    </tr>
                    <tr>
                        <td><font size="2">Address:<b>{{$job['location']['description']}} - {{$job['location']['address']}}</font></b></td>
                        <td></td>
                        <td></td>
                        <td><font size="2"><b>Asset Name: {{$job['asset']['name']}}</b></td>
                    </tr>
                    <tr>
                        <td><font size="2">Specific Location: {{$job['asset']['specific_location']}}</font></td>
                        <td></td>
                        <td></td>
                        <td><font size="2">Serial:<font color="blue"> {{$job['asset']['serial']}}</font> - BMB: <font color="blue"> {{$job['asset']['bmb']}}</font></font></td>
                    </tr>
                    <tr>
                        <td><font size="2">Contact Person:<b> {{$job['contact_person']}}</font></b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><font size="2">Contact Number:<b> {{$job['contact_number']}}
                                {{$job['asset']['location']['phone_number']}}</font></b></td>
                        <td></td>
                        <td></td>
                        <td></td>
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
                            <tr>
                                <td><font size="2">Distributor:{{$job['location']['agent_id']}}</font></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><font size="2">Contractor:<font color="blue">{{$job['contractor']['name']}}</font></font></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><font size="2">Job Raised Date:
                                    {{\Carbon\Carbon::parse($job['job_raised_date'])->format('d-m-Y H:i:s A')}}</font></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><font size="2">Required Finishing Date:
                                    {{\Carbon\Carbon::parse($job['required_date'])->format('d-m-Y H:i:s A')}}</font>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><font size="2">Priority:{{$job['priority']['name']}}</font></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><font size="2">Job Status: {{$job['job_stat']['type']}}</font></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="border:2px solid red;"><font size="2">Job Description:<b><font color="blue"> {!! $job['description'] !!}</font></font></b></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                    <div class="column-three">
                        {{-- <table>
                            <tr>
                                @foreach($job['machineOptions'] as $key => $rows)
                                <td>
                                    {{$key}}
                        <table>
                            @foreach($rows as $k => $machineOption)
                            <tr>
                                <td>{{$machineOption['value']}} </td>
                            </tr>
                            @endforeach
                        </table>
                        </td>
                        @endforeach
                        </tr>
                        </table> --}}

                        @if(array_key_exists('Cup', $job['machineOptions']))
                        <table>
                            <tr>
                                <td>
                                    <u>Cup</u>
                                    <table>
                                        @foreach($job['machineOptions']['Cup'] as $k => $machineOption)
                                        <tr>
                                            <td><font size="2">{{$machineOption['value']}}</font> </td>
                                        </tr>
                                        @endforeach
                                    </table>
                                </td>

                                @if(array_key_exists('Product', $job['machineOptions']))
                                <td>
                                    <u>Product</u>
                                    <table>
                                        @foreach($job['machineOptions']['Product'] as $k => $machineOption)
                                        <tr>
                                            <td><font size="2">{{$machineOption['value']}} </font></td>
                                        </tr>
                                        @endforeach
                                    </table>
                                </td>
                                @endif

                                @if(array_key_exists('Drink', $job['machineOptions']))
                                <td>
                                    <u>Drink</u>
                                    <table>
                                        @foreach($job['machineOptions']['Drink'] as $k => $machineOption)
                                        <tr>
                                            <td><font size="2">{{$machineOption['value']}} </font></td>
                                        </tr>
                                        @endforeach
                                    </table>
                                </td>
                                @endif
                            </tr>
                        </table>
                        @endif

                        <p>
                            <font size="1">Returning machine for repair or return?</font>
                        </p>
                        <p> <font size="1">If job involves extraction of machine and return to Nestle either for repair or return;
                            Please
                            ensure machine is empty of all product and water before packaging for
                            transport. Phone 0800 372 800 to notify
                            once ready for pickup.</font>
                        </p>
                        <p>
                            <font size="1" color="red">This JOB SHEET MUST BE FAXED TO 09275 7422 WITHIN 24 HOURS OF COMPLETION
                                OF JOB,
                                OR,EMAILED TO technical.support@nz.nestle.com INVOICE FOR THIS JOB MUST MATCH THIS JOB
                                SHEET.</font>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>