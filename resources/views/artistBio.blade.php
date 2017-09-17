@extends('layouts.app')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="css/custom_styles.css">

@section('content')
<div class="row" style="text-align: center;"><h1>Add Your Bio</h1></div>
<div class="row" style="text-align: center;"><p>One more step! Add a bio to tell employers a little more about you:</p></div>
{!! Form::open(['url' => 'artistBio/save', 'method' => 'post']) !!}
    <div class="bio-form">
        <div class="row" style="text-align: center;">
            <div class="col-md-4"></div>
            <div class="col-md-4" style="text-align: center;">{!! Form::textarea('bio', "", ['style' => 'width:100%', 'class' => 'smoothForm']) !!}</div>
            <div class="col-md-4"></div>
        </div>
        <div class="row" style="padding-top: 20px;">
            <div class="col-md-4"></div>
            <div class="col-md-4" style="text-align: center;"><input name="update" type="image" src="../assets/update-btn.png"/></div>
            <div class="col-md-4"></div>
        </div>
    </div>
{!! Form::close() !!}

@endsection
