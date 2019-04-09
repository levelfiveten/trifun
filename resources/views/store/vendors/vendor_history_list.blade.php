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
    <h5>{{$vendorPassUses[0]->vendor_name}} ({{$vendorPassUses[0]->region_name}})</h5>
    @if ($vendorPassUses->count() === 0)
    <p>No passes found for this venue.</p>
    @else
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Location</th>
                    <th>Pass Type</th>
                    <th>Qty. Used</th>
                    <th>Order#</th>
                    <th>Conf#</th>
                    <th>Redeemed On</th>
                </tr>
            </thead>
            <tbody>
            @foreach($vendorPassUses as $vendorPassUse)
                <?php 
                    $passUse = \App\PassUsage::where('conf_code', $vendorPassUse->conf_code)->get();
                ?>
                <tr>
                    <td>{{$vendorPassUse->name}} ({{$vendorPassUse->email}}) </td>
                    <td>{{$vendorPassUse->vendor_location_name}}</td>
                    <td>{{$vendorPassUse->pass_type_name}}</td>
                    <td>{{$passUse->count()}}</td>
                    <td>{{ ($vendorPassUse->order_number == 0 || empty($vendorPassUse->order_number)) ? 'N/A' : $vendorPassUse->order_number }}</td>
                    <td>{{$vendorPassUse->conf_code}}</td>
                    <td>{{ \App\Helpers\Helper::convertDateTimeToApp($vendorPassUse->pass_used_dt) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>