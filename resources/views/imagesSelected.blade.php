@extends('layouts.app')

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="stylesheet" href="css/image-picker.css">
<link rel="stylesheet" href="css/custom_styles.css">
<link rel="stylesheet" href="css/demo.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/flexslider.css" type="text/css" media="screen" />

<script src="js/modernizr.js"></script>

@section('content')

<div class="container">
    <div class="row" style="padding-bottom: 25px;">
        <?php $imageCount = count($imagePathStories);?>
        <?php $i = 0; ?>
        @foreach($imagePathStories as $imagePathStory)
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4" style="text-align: center">
                    <img src="art/<?php echo $imagePathStory[0]->path ?>">
                </div>
                <div class="col-md-4"></div>
            </div>
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4" style="text-align: center">
                    <h4>What was the motivation?</h4>
                </div>
                <div class="col-md-4"></div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6"  style="text-align: center><?php echo $imagePathStory[0]->story ?></div>
                <div class="col-md-3"></div>
            </div>
            @if($i < $imageCount-1)
                <hr>
            @endif
            <?php $i++; ?>
        @endforeach
    </div>
    <div class="row" style="text-align: center">
        <a href="searchResults?search_id=<?php echo $searchID ?>"><img src="assets/back-to-artists.png"></a>
    </div>
</div>
@endsection