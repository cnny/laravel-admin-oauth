<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{config('admin.title')}} | {{ trans('admin.login') }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="{{ admin_asset("vendor/laravel-admin/AdminLTE/bootstrap/css/bootstrap.min.css") }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ admin_asset("vendor/laravel-admin/font-awesome/css/font-awesome.min.css") }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ admin_asset("vendor/laravel-admin/AdminLTE/dist/css/AdminLTE.min.css") }}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="hold-transition login-page" style="height: 100vh; display: flex; align-items: center; background: url({{config('admin.login_background_image') ?: '/vendor/laravel-admin-oauth/img/background.jpg'}}) no-repeat;background-size: cover;">

    <div class="login-box" style="opacity: 0.8;">

        <div class="login-logo">
            <b style="color:white; font-size: 20px; ">{{config('admin.name')}}</b>
        </div>

        <div class="login-box-body" style="border-radius: 10px">
            <p class="login-box-msg">{{ $sourceName }}绑定本地账号</p>

            <form action="{{ admin_base_path('oauth/bind-account') }}" method="post">
                <div class="form-group has-feedback {!! !$errors->has('username') ?: 'has-error' !!}">

                    @if($errors->has('username'))
                        @foreach($errors->get('username') as $message)
                            <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i>{{$message}}</label><br>
                        @endforeach
                    @endif

                    <input type="text" class="form-control" placeholder="{{ trans('admin.username') }}" name="username" value="{{ old('username') }}">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback {!! !$errors->has('password') ?: 'has-error' !!}">

                    @if($errors->has('password'))
                        @foreach($errors->get('password') as $message)
                            <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i>{{$message}}</label><br>
                        @endforeach
                    @endif

                    <input type="password" class="form-control" placeholder="{{ trans('admin.password') }}" name="password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="row" style="align-content: center;">
                    <!-- /.col -->
                    <div class="col-xs-4">
                    </div>
                    <div class="col-xs-4">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="submit" class="btn btn-primary btn-block btn-flat" style="border-radius: 5px">绑定</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
        </div>
    </div>
</body>
</html>
