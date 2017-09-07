@extends('layouts.homeApp')

@section('content')
<div id="homeContainer" class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="row" style="text-align: center;"><h1 class="homeBanner">Find Work. Get Work.</h1></div>
            <div class="row" style="text-align: center;"></div>
            <div class="row" style="text-align: center; padding-top: 75px;">
                <button class="btn btn-primary" style="font-size: 36px;"onclick="window.location='/browseArt'">Browse Art</button>
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
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">

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
                                <input id="password" type="password" class="form-control" name="password">

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
                        <div class="form-group" style="text-align: center;">
                            <div class="col-md-6"><button type="submit" class="btn btn-primary">Login</button></div>
                            <div class="col-md-6"><a href="redirect"><img id="fbLoginBtn" src="../assets/facebook_login.png"></a></div>
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
