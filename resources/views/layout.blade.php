<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('page_title')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .icon-bold {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .vertical-line {
            border-left: 2px solid red;
            height: 1.5rem;
            margin-right: 0.5rem;
            align-self: center;
        }

        .d-none {
            display: none;
        }

        .taskbtn {
            float: right;
        }

        .no-data-row td {
            text-align: center;
        }

        .delete_data {
            float: left;
        }

        .modalView {
            width: 30%;
        }
    </style>
</head>

<body>
    <div class="container">
        @yield('content')
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    @yield('script')
</body>

</html>
