@extends('layouts.app')

@section('content')

<p>Do you REALLY want to delete your profile? You can't get it back.</p>

<div class='row'>
    <div class="col-md-6"><a href="confirmDeleteProfile" class='btn'>Yep, I'm out</a></div>
    <div class="col-md-6"><a href="home" class='btn'>No, I love you guys</a></div>
</div>

@endsection
