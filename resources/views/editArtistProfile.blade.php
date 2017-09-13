@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row" style="text-align: center;"><h1>Complete Your Profile</h1></div>
    <div class="row">
        {!! Form::open(array('url' => '/artist/update', 'enctype' => 'multipart/form-data', 'method' => 'post')) !!}
            {!! csrf_field() !!}
            
            <div class="row spaceForm">
                <div class="col-md-4"></div>
                <div class="col-md-2">First Name</div>
                <div class="col-md-2">Last Name</div>
                <div class="col-md-4"></div>
            </div>
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-2">{!! Form::text('first_name', $user->first_name) !!}</div>
                <div class="col-md-2">{!! Form::text('last_name', $user->last_name) !!}</div>
                <div class="col-md-4"></div>
            </div>
            
            <div class="row spaceForm">
                <div class="col-md-4"></div>
                <div class="col-md-2">Email</div> 
                <div class="col-md-2"></div>
                <div class="col-md-4"></div>
            </div>
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-2">{{$user->email}}</div> 
                <div class="col-md-2"></div>
                <div class="col-md-4"></div>
            </div>
            
            <div class="row spaceForm">
                <div class="col-md-4"></div>
                <div class="col-md-2">Phone</div> 
                <div class="col-md-2"></div>
                <div class="col-md-4"></div>
            </div>
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-2">{!! Form::text('phone',$user->phone) !!}</div> 
                <div class="col-md-2"></div>
                <div class="col-md-4"></div>
            </div>
            @if ($errors->has('phone'))
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-2">
                        <span class="help-block"><strong>{{ $errors->first('phone') }}</strong></span>
                    </div> 
                    <div class="col-md-2"></div>
                    <div class="col-md-4"></div>
                </div>
            @endif
            
            <div class="row spaceForm">
                <div class="col-md-4"></div>
                <div class="col-md-2">City</div> 
                <div class="col-md-2">State</div>
                <div class="col-md-4"></div>
            </div>
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-2">{!! Form::text('city',$user->city) !!}</div> 
                <div class="col-md-2">{!! Form::text('state',$user->state) !!}</div>
                <div class="col-md-4"></div>
            </div>
            @if ($errors->has('city') && $errors->has('state'))
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-2">
                        <span class="help-block"><strong>{{ $errors->first('city') }}</strong></span>
                    </div> 
                    <div class="col-md-2">
                        <span class="help-block"><strong>{{ $errors->first('state') }}</strong></span>
                    </div>
                    <div class="col-md-4"></div>
                </div>
            @elseif ($errors->has('city'))
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-2">
                        <span class="help-block"><strong>{{ $errors->first('city') }}</strong></span>
                    </div> 
                    <div class="col-md-2"></div>
                    <div class="col-md-4"></div>
                </div>
            @elseif ($errors->has('state'))
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-2"></div> 
                    <div class="col-md-2">
                        <span class="help-block"><strong>{{ $errors->first('state') }}</strong></span>
                    </div>
                    <div class="col-md-4"></div>
                </div>
            @endif
            
            <div class="row spaceForm">
                <div class="col-md-4"></div>
                <div class="col-md-2">Zip Code</div> 
                <div class="col-md-2"></div>
                <div class="col-md-4"></div>
            </div>
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-2">{!! Form::text('zip_code',$user->zip_code) !!}</div> 
                <div class="col-md-2"></div>
                <div class="col-md-4"></div>
            </div>
            @if ($errors->has('zip_code'))
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-2">
                        <span class="help-block"><strong>{{ $errors->first('zip_code') }}</strong></span>
                    </div> 
                    <div class="col-md-2"></div>
                    <div class="col-md-4"></div>
                </div>
            @endif

            <div class="row spaceForm">
                <div class="col-md-4"></div>
                <div class="col-md-2">Resume</div> 
                <div class="col-md-2"></div>
                <div class="col-md-4"></div>
            </div>
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-2">{!! Form::file('resume') !!}</div> 
                <div class="col-md-2"></div>
                <div class="col-md-4"></div>
            </div>
            @if ($errors->has('resume') && isset($first_time_completing))
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-2">
                        <span class="help-block"><strong>{{ $errors->first('resume') }}</strong></span>
                    </div> 
                    <div class="col-md-2"></div>
                    <div class="col-md-4"></div>
                </div>
            @endif
            
            <div class="row spaceForm">
                <div class="col-md-4"></div>
                <div class="col-md-2">Portfolio</div> 
                <div class="col-md-2"></div>
                <div class="col-md-4"></div>
            </div>
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-2">{!! Form::text('portfolio', $user->portfolio) !!}</div> 
                <div class="col-md-2"></div>
                <div class="col-md-4"></div>
            </div>
            @if ($errors->has('portfolio'))
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-2">
                        <span class="help-block"><strong>{{ $errors->first('portfolio') }}</strong></span>
                    </div> 
                    <div class="col-md-2"></div>
                    <div class="col-md-4"></div>
                </div>
            @endif

            <div class="row spaceForm">
                <div class="col-md-4"></div>
                <div class="col-md-2"><input name="update" type="image" src="../assets/update-btn.png"/></div>
                <div class="col-md-2"><a href='home'><img type="image" src="../assets/cancel.png"></a></div>
                <div class="col-md-4"></div>
            </div>

            @if (isset($dOption))
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                        <a href="deleteAccount">Delete Account</a>
                    </div>
                    <div class="col-md-4"></div>
                </div>
            @endif
        {!! Form::close() !!}
    </div>
</div>
@endsection