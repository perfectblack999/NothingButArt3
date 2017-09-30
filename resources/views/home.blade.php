@extends('layouts.app')
<link rel="stylesheet" href="css/demo.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/flexslider.css" type="text/css" media="screen" />

<script src="js/modernizr.js"></script>
<link rel="stylesheet" href="css/image-picker.css">

@section('content')
<div class="container">
    @if($user->type == "artist")
    <div class="row" style="text-align: center; padding-bottom: 25px"><h1>Your Portfolio</h1></div>
        <div id="imageContainer" class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10 grid">
                <select id="artHolder" multiple="multiple" class="image-picker masonry">    
                    @for ($i = ($screenNumber - 1) * 8; $i < (($screenNumber - 1) * 8) + 8; $i++)
                        @if (isset($imageDisplayLines[$i]))
                            <?php echo $imageDisplayLines[$i] ?>
                        @endif
                    @endfor
                </select>
            </div>
        </div>

        <div class="row" style="padding-bottom: 35px; vertical-align: middle;">
            <div class="col-md-2"></div>
            <div class="col-md-2" style="text-align: center;">
                {!! Form::open(array('url' => '/home/downloadResume', 'enctype' => 'multipart/form-data')) !!}
                    <input type="image" src="../assets/download-resume-btn.png"/>
                {!! Form::close() !!}
            </div>
            <div class="col-md-4" style="text-align: center;">

                <input type="image" src="../assets/more-pics-btn.png" name="next" 
                    onclick="nextBrowsePage(<?php echo $numberOfScreens ?>, 
                    <?php echo htmlspecialchars(json_encode($gridArtIDs)) ?>, 
                    <?php echo htmlspecialchars(json_encode($imagePaths)) ?>,
                    <?php echo htmlspecialchars(json_encode($homeView)) ?>)" 
                    style="text-align: center; display: inline-block;">
            </div>
            <div class="col-md-2" style="text-align: center;">
                <a href="/editArt"><input type="image" src="../assets/add-edit-art-btn.png"/></a>
            </div>
            <div class="col-md-2"></div>
        </div>
    @endif

    @if($user->type == "recruiter")
        <div class="row">
            <div class="col-md-12" style="text-align: center"><h1>Search for your next designer</h1></div>
        </div>
        <div class="col-md-12">
            <div class="col-md-12" style="text-align: center; padding-top: 100px;">
                <a href="/newSearch"><img src="../assets/start-search-btn.png"></a>
            </div>
        </div>
    @endif
</div>
@endsection
