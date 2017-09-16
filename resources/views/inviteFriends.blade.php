@extends('layouts.app')

@section('content')

<h3 style="text-align: center;">Get someone hired today by inviting a friend!</h3><br>
    {!! Form::open(array('url' => '/emailInvites', 'method' => 'post')) !!}
        {!! csrf_field() !!}
        <p style="text-align: center;">Enter valid email addresses separated by commas.</p>
        <div class="row">
            <div class="col-md-12" style="text-align: center">{!! Form::text('emails', '', array('class' => 'smoothForm')) !!}</div>
        </div><br>
        <div class="row">
            <div class="col-md-12" style="text-align: center"><input id="invite" name="invite" type="image" src="../assets/invite.png"/></div>
        </div>
    {!! Form::close() !!}
@endsection
