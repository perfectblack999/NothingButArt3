@extends('layouts.app')
<link rel="stylesheet" href="css/demo.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/flexslider.css" type="text/css" media="screen" />

<script src="js/modernizr.js"></script>
<link rel="stylesheet" href="css/image-picker.css">

@section('content')
<div class="container">
    @if($user->type == "artist")
        <div id="imageContainer" class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8 grid" style="text-align: center;">
                <select id="artHolder" multiple="multiple" class="image-picker show-html masonry">    
                    @for ($i = ($screenNumber - 1) * 9; $i < (($screenNumber - 1) * 9) + 9; $i++)
                        @if (isset($imageDisplayLines[$i]))
                            <?php echo $imageDisplayLines[$i] ?>
                        @endif
                    @endfor
                </select>
            </div>
            <div class="col-md-2"></div>
        </div>

        <div class="row">
            <div class="col-md-12" style="text-align: center;">

                <input class="btn btn-primary" type="submit" value="More Pics" name="next" 
                    onclick="nextBrowsePage(<?php echo $numberOfScreens ?>, 
                    <?php echo htmlspecialchars(json_encode($gridArtIDs)) ?>, 
                    <?php echo htmlspecialchars(json_encode($imagePaths)) ?>,
                    <?php echo htmlspecialchars(json_encode($homeView)) ?>)" 
                    style="text-align: center; display: inline-block;">
            </div>
        </div>

        {!! Form::open(array('url' => '/home/downloadResume', 'enctype' => 'multipart/form-data')) !!}
            <div class="col-md-12">
                <div class="col-md-3">{!! Form::submit('Download Resume') !!}</div>
            </div>
        {!! Form::close() !!}

        <div class="col-md-12">    
            <div class="col-md-3"><a href="/editArt">Add or edit art!</a></div>
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
