@extends('layouts.app')

@section('content')

<p>Thanks for registering. Click below to complete your registration:</p>

<form action = "activateProfile" method = "post">
    <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
    <input type = "hidden" name = "activation" value = "activate">
    <input type="submit" value="Complete Registration" name="complete_registration" />
</form>
@endsection