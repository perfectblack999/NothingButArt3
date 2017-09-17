@extends('layouts.app')

@section('content')

<div class="row" style="text-align: center; padding-bottom: 100px;"><p>Do you REALLY want to delete your profile? You can't get it back.</p></div>

<div class='row' style="text-align: center;">
    <div class="col-md-3"></div>
    <div class="col-md-3"><a href="confirmDeleteProfile" class='btn'>Yep, I'm out</a></div>
    <div class="col-md-3"><a href="home" class='btn'>No, I love you guys</a></div>
    <div class="col-md-3"></div>
</div>

@endsection
