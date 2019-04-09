<html>
<head>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body>
    @foreach($regions as $region)
    <h4>{{$region->name}}</h4>
        @if ($region->passTypes->count() === 0)
        <p>No passes found for this region.</p>
        @else
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>Pass Type</th>
                        <th>Validity</th>
                        <th>Use Per Vendor</th>
                        <th>Max # of Uses</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($region->passTypes as $passType)
                    <?php $validPeriod = ($passType->days_valid == 365) ? '1 year' : $passType->days_valid . ' days'; ?>
                    <tr>
                        <td>{{$passType->name}}</td>
                        <td>{{$validPeriod}}</td>
                        <td>{{ ($passType->type == 'dining') ? 'varies' : $passType->use_per_vendor}}</td>
                        <td>{{ ($passType->type == 'experience') ? 'varies' : $passType->usage_limit}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    @endforeach
</body>
</html>