@extends('layouts.app')

@section('content')
<div class="row" style="text-align: center; padding-bottom: 25px">
    <h3>Thanks for signing up. Click below to complete your registration:</h3>
</div>

<form action = "activateProfile" method = "post">
    <input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
    <?php if(!isset($user->type)){ ?>
        <div class="form-group">
            <div class="row" style="text-align: center;">
                <div class="col-md-4"></div>
                <div class="col-md-4" style="text-align: center;">
                    <h3>Who are you?</h3>
                </div>
                <div class="col-md-4"></div>
            </div>
            <div class="row">
                <div class="col-md-6"></div>
                <div class="col-md-6">
                    <?php if($user->type == 'recruiter') { ?>
                        <input type="radio" id="name" name="type" value="artist">Artist &nbsp;
                        <input type="radio" name="type" value="recruiter" checked="true">Recruiter</br>
                    <?php } else { ?>
                        <input type="radio" id="name" name="type" value="artist" checked="true">Artist &nbsp;
                        <input type="radio" name="type" value="recruiter">Recruiter</br>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
    <input type = "hidden" name = "activation" value = "activate">
    <div class="row" style="text-align: center;">
        <input name="complete_registration" type="image" src="../assets/complete-registration-btn.png"/>
    </div>
</form>
@endsection