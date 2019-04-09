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
    <h5>{{ $monthName }}</h5>
    @if ($monthlyPassUses->count() == 0)
        <p style="text-align:center"><strong>No pass use data found.</strong></p>
    @else
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Venue</th>
                <th>Region</th>
                <th>Type</th>                
                <th>Pass Qty Used</th>
            </tr>
        </thead>
        <tbody>
        @foreach($monthlyPassUses as $monthlyPassUse)
            <tr>
                <td>{{ $monthlyPassUse->name }}</td>
                <td>{{ $monthlyPassUse->region_name }}</td>
                <td>{{ $monthlyPassUse->pass_type_name }}</td>                
                <td>{{ $monthlyPassUse->qty }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @endif
</body>
</html>