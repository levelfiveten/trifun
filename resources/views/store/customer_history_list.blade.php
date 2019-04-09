<html>
<head>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <style>
        body {
            margin:10px;
        }
    </style>
</head>
<body>
    <h5>{{$allPassUses[0]->name}} ({{$allPassUses[0]->email}})</h5>
    @if ($allPassUses->count() === 0)
    <p>No passes found for this customer.</p>
    @else
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th>Region</th>
                    <th>Pass Type</th>
                    <th>Venue</th>
                    <th>Location</th>
                    <th>Qty. Used</th>
                    <th>Order#</th>
                    <th>Conf#</th>
                    <th>Redeemed On</th>
                </tr>
            </thead>
            <tbody>
            @foreach($allPassUses as $passUsage)
                <?php 
                    $passUse = \App\PassUsage::where('conf_code', $passUsage->conf_code)->get();
                ?>
                <tr>
                    <td>{{$passUsage->region_name}}</td>
                    <td>{{$passUsage->pass_type_name}}</td>
                    <td>{{$passUsage->vendor_name}}</td>
                    <td>{{$passUsage->vendor_location_name}}</td>
                    <td>{{$passUse->count()}}</td>
                    <td>{{ ($passUsage->order_number == 0 || empty($passUsage->order_number)) ? 'N/A' : $passUsage->order_number }}</td>
                    <td>{{$passUsage->conf_code}}</td>
                    <td>{{ \App\Helpers\Helper::convertDateTimeToApp($passUsage->pass_used_dt) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>