@extends('layouts.homeApp')

@section('content')
<div id="homeContainer" class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="row" style="text-align: center;">
                <div style="text-align: center;">
                    <h1 class="homeBanner">
                        Find Work 
                        <span class="homeBannerDivider">|</span> 
                        Get Work
                    </h1>
                </div>
            </div>
            <div class="row" style="text-align: center; padding-top: 100px">
                <a href="/browseArt"><img src="../assets/browse-art-btn.png"></a>
            </div>
        </div>
        <div id="loginForm" class="col-md-3">
            <div class="row"><div class="col-md-offset-1"><h2>Login</h2></div></div>
                <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <div class="row">
                            <div class="col-md-offset-1 col-md-11"><label for="email" class="control-label">E-Mail Address</label></div>
                        </div>

                        <div class="row">
                            <div class="col-md-offset-1 col-md-11">
                                <input id="email" type="email" class="form-control smoothForm" name="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <div class="row">
                            <div class="col-md-offset-1 col-md-11"><label for="password" class="control-label">Password</label></div>
                        </div>
                            
                        <div class="row">
                            <div class="col-md-offset-1 col-md-11">
                                <input id="password" type="password" class="form-control smoothForm" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-offset-1 col-md-11">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> Remember Me
                                    </label>
                                </div>
                            </div>

                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif
                            @if (session('warning'))
                                <div class="alert alert-warning">
                                    {{ session('warning') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-offset-1">
                            <div class="form-group" style="text-align: center;">
                                <div class="col-md-6"><input type="image" src="../assets/login-btn.png"/></div>
                                <div class="col-md-6"><a href="redirect"><img id="fbLoginBtn" src="../assets/fb-login-btn.png"></a></div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="text-align: center;">
                        <a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Password?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
