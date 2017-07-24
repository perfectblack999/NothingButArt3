@extends('layouts.app')

@section('content')

<p>Thanks for signing up. Click below to complete your registration:</p>

<form action = "activateProfile" method = "post">
    <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
    <?php if(!isset($user->type)){ ?>
        <div class="form-group">
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <h3>Who are you?</h3>
                </div>
                <div class="col-md-4"></div>
            </div>
            <div class="row">
                <div class="col-md-6"></div>
                <div class="col-md-6">
                    <input type="radio" id="name" name="type" value="artist" checked="true">Artist &nbsp;
                    <input type="radio" name="type" value="recruiter">Recruiter</br>
                </div>
            </div>
        </div>
    <?php } ?>
    <input type = "hidden" name = "activation" value = "activate">
    <input type="submit" value="Complete Registration" name="complete_registration" />
</form>
@endsection