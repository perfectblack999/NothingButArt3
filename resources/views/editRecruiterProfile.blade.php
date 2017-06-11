@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">{{$user->first_name}} {{$user->last_name}}</div>

                <div class="panel-body">
                    <h1>Complete Your Profile</h1>
                    <form action = "/recruiter/update" method = "post" style="display: inline;">
                      <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
                        
                        
                        <div class="col-md-12"> 
                            <div class="col-md-3">Email</div><br>
                            <div class="col-md-3">{{$user->email}}</div>
                        </div>

                        <div class="col-md-12">
                            <div class="col-md-3">Phone</div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-3"><input type='text' name='phone' /></div>
                        </div>
                        @if ($errors->has('phone'))
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <span class="help-block"><strong>{{ $errors->first('phone') }}</strong></span>
                                </div>
                            </div>
                        @endif

                        <div class="col-md-12">
                            <div class="col-md-3">Company</div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-3"><input type='text' name='company' /></div>
                        </div>
                        @if ($errors->has('company'))
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <span class="help-block"><strong>{{ $errors->first('company') }}</strong></span>
                                </div>
                            </div>
                        @endif
                        

                        <div class="col-md-12">
                            <div class="col-md-3">Street Address</div>
                            <div class="col-md-3">Street Address 2</div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-3"><input type='text' name='street_address1' /></div>
                            <div class="col-md-3"><input type='text' name='street_address2' /></div>
                        </div>

                        <div class="col-md-12">
                            <div class="col-md-3">City</div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-3"><input type='text' name='city' /></div>
                        </div>

                        <div class="col-md-12">
                            <div class="col-md-3">State</div>
                            <div class="col-md-3">Zip Code</div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-3"><input type='text' name='state' /></div>
                            <div class="col-md-3"><input type='text' name='zip_code' /></div>
                        </div>
                        <br>
                        <div class="col-md-12">
                            <div class="col-md-3"><input type = 'submit' value = "Update"/></div>
                        </div>
                   </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection