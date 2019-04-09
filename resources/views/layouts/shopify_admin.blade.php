<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--Embedded App SDK js-->
    <script src="https://cdn.shopify.com/s/assets/external/app.js"></script>
    @yield('head')
    <!-- <script
        src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous">
    </script> -->
    <script src="http://kendo.cdn.telerik.com/2018.3.1017/js/jquery.min.js"></script>
    <script src="https://kendo.cdn.telerik.com/2018.3.911/js/kendo.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>    
    <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2018.3.911/styles/kendo.common-material.min.css" />
    <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2018.3.911/styles/kendo.material.min.css" />
    <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2018.3.911/styles/kendo.material.mobile.min.css" />

    
</head>
<body>
    @yield('content')
</body>
</html>
