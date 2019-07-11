<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{csrf_token()}}">
        <title>Админка</title>
        <link href=" {{ mix('css/adminlte.css') }}" rel="stylesheet">
        <link href=" {{ asset('css/custom.css') }}" rel="stylesheet">
    </head>
    <body class="hold-transition sidebar-mini">
        <div id="app"></div>
        <script src="{{ mix('js/app.js') }}"></script>
    </body>
</html>