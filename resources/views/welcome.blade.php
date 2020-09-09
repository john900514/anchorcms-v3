<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="description" content="AnchorCMS">
    <meta name="author" content="Cape&Bay \\ capeandbay.com">
    <meta name="keywords" content="anchor, anchorcms, cms, clients, capeandbay">

    <meta property="og:title" content="Anchor CMS">
    <meta property="og:description" content="AnchorCMS">

    <meta property="og:image" content="https://capeandbay.com/wp-content/uploads/2019/11/cropped-favicon-180x180.png">
    <meta property="og:url" content="{!! env('APP_URL') !!}">
    <meta property="og:video" content="https://i.vimeocdn.com/video/945480185.webp" />
    <meta property="og:video:type" content="video" />
    <meta property="og:video:width" content="1280" />
    <meta property="og:video:height" content="720" />

    <link rel="icon" href="https://capeandbay.com/wp-content/uploads/2019/11/cropped-favicon-32x32.png" sizes="32x32">
    <link rel="icon" href="https://capeandbay.com/wp-content/uploads/2019/11/cropped-favicon-192x192.png" sizes="192x192">
    <link rel="apple-touch-icon-precomposed" href="https://capeandbay.com/wp-content/uploads/2019/11/cropped-favicon-180x180.png">

    <title>{!! env('APP_NAME') !!}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link href="{!! asset('css/app.css') !!}" rel="stylesheet" />
    <!-- Styles -->
    <style>
        html, body {
            background-color: gray;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
            height: 95%;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        img {
            width: 75%;
        }

        .links > a {
            color: white;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji" !important;
        }

        .m-b-md {
            margin-bottom: 30px;
        }

        footer {
            width: 100%;
            height: 5%;
        }

        .inner-footer {
            text-align: center;
        }

        footer small {
            color: #fff;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji" !important;
            font-weight: 700;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">
    @if (Route::has('backpack.auth.login'))
        <div class="top-right links">
            @if(backpack_user() != null)
                <a href="{{ url('/dashboard') }}">Home</a>
            @else
                <a href="{{ route('backpack.auth.login') }}">Login</a>

                @if (Route::has('backpack.auth.register'))
                <!-- <a href="{{ route('backpack.auth.register') }}">Register</a> -->
                @endif
            @endauth
        </div>
    @endif

    <div class="content">
        <div class="title m-b-md">
            <img src="https://amchorcms-assets.s3.amazonaws.com/anchorCMSLogo.png">
        </div>

        <div class="links">
            <a href="https://capeandbay.com">Cape&Bay</a>
        </div>
    </div>
</div>

<footer>
    <div class="inner-footer">
        <small><i class="fal fa-copyright"></i>2020. Cape & Bay. All Rights Reserved. </small>
        <br />
        <small>v.{!! env('APP_VERSION') !!}| Build {!! env('APP_BUILD') !!}</small>
    </div>
</footer>
</body>
</html>
