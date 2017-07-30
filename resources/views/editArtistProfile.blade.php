@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">{{$user->first_name}} {{$user->last_name}}</div>

                <div class="panel-body">
                    <h1>Complete Your Profile</h1>
                    {!! Form::open(array('url' => '/artist/update', 'enctype' => 'multipart/form-data', 'method' => 'post')) !!}
                        {!! csrf_field() !!}
                        <div class="col-md-6"> 
                            <div class="col-md-3">First Name</div><br>
                            <div class="col-md-3">{!! Form::text('first_name', $user->first_name) !!}</div>
                        </div>
                        <div class="col-md-6"> 
                            <div class="col-md-3">Last Name</div><br>
                            <div class="col-md-3">{!! Form::text('last_name', $user->last_name) !!}</div>
                        </div>
                        <div class="col-md-12"> 
                                <div class="col-md-3">Email</div><br>
                                <div class="col-md-3">{{$user->email}}</div>
                        </div><br>
                        
                        <div class="col-md-12">
                            <div class="col-md-3">Phone</div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-3">{!! Form::text('phone',$user->phone) !!}</div>
                        </div>
                        @if ($errors->has('phone'))
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <span class="help-block"><strong>{{ $errors->first('phone') }}</strong></span>
                                </div>
                            </div>
                        @endif
                        
                        <div class="col-md-12">
                            <div class="col-md-3">City</div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-3">{!! Form::text('city',$user->city) !!}</div>
                        </div>
                        @if ($errors->has('city'))
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <span class="help-block"><strong>{{ $errors->first('city') }}</strong></span>
                                </div>
                            </div>
                        @endif
                        
                        <div class="col-md-12">
                            <div class="col-md-3">State</div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-3">{!! Form::text('state',$user->state) !!}</div>
                        </div>
                        @if ($errors->has('state'))
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <span class="help-block"><strong>{{ $errors->first('state') }}</strong></span>
                                </div>
                            </div>
                        @endif
                        
                        <div class="col-md-12">
                            <div class="col-md-3">Zip Code</div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-3">{!! Form::text('zip_code',$user->zip_code) !!}</div>
                        </div>
                        @if ($errors->has('zip_code'))
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <span class="help-block"><strong>{{ $errors->first('zip_code') }}</strong></span>
                                </div>
                            </div>
                        @endif
                        
                        <div class="col-md-12">
                            <div class="col-md-3">Resume</div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-3">{!! Form::file('resume') !!}</div>
                        </div>
<!--                        @if ($errors->has('resume'))
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <span class="help-block"><strong>{{ $errors->first('resume') }}</strong></span>
                                </div>
                            </div>
                        @endif-->
                        
                        <div class="col-md-12">
                            <div class="col-md-3">Portfolio</div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-3">{!! Form::text('portfolio', $user->portfolio) !!}</div>
                        </div>
                        @if ($errors->has('portfolio'))
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <span class="help-block"><strong>{{ $errors->first('portfolio') }}</strong></span>
                                </div>
                            </div>
                        @endif

                        <div class="col-md-12">
                            <div class="col-md-3">{!! Form::submit('Update') !!}</div>
                            <div class="col-md-3"><a href='home'>Cancel</a></div>
                        </div>
                        
                        @if (isset($dOption))
                            <div class="col-md-12">
                                <a href="deleteAccount">Delete Account</a>
                            </div>
                        @endif
                    {!! Form::close() !!}
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection