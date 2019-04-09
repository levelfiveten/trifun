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
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Name</th>
                <th>Region</th>
                <th>Type</th>
                <th>Email</th>
                <th>Redeem Instructions</th>
                <th>Redeem Description</th>
                <th>Passcode</th>
                <th>Max Pass Use</th>
            </tr>
        </thead>
        <tbody>
        @foreach($vendors as $vendor)
            <tr>
                <td>{{$vendor->name}}</td>
                <td>{{$vendor->passType->region->name}}</td>
                <td>{{$vendor->passType->name}}</td>
                <td>{{$vendor->email}}</td>
                <td>{{$vendor->redeem_txt}}</td>
                <td>{{$vendor->offer_desc}}</td>
                <td>{{$vendor->pass_code}}</td>
                <td>{{$vendor->max_pass_use}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>