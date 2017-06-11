@extends('layouts.app')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="css/custom_styles.css">

@section('content')

{!! Form::open(['url' => 'artistBio/save', 'method' => 'post']) !!}
    <div class="bio-form">
        <div class="row">
            <div class="col-md-1"></div><div class="col-md-3">{!! Form::textarea('bio', "", ['style' => 'width:100%']) !!}</div><div class="col-md-8"></div>
        </div>
        <div class="row">
            <div class="col-md-1"></div><div class="col-md-3" style="text-align: right;">{!! Form::submit('Update') !!}</div><div class="col-md-8"></div>
        </div>
    </div>
{!! Form::close() !!}

@endsection
