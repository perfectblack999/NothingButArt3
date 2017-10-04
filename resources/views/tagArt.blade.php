@extends('layouts.app')

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="stylesheet" href="css/image-picker.css">
<link rel="stylesheet" href="css/custom_styles.css">
<link rel="stylesheet" href="css/demo.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/flexslider.css" type="text/css" media="screen" />

<script src="js/modernizr.js"></script>

@section('content')
<div id="saved" style="text-align: center;"><img src="assets/save-confirmation.png"></div>

<div class="container">
    <div class="row" id="tagArtContainer">
        <div class="col-md-8">
            <div id="imageContainer" class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10 grid">
                    <select id="artHolder" class="image-picker masonry">    
                        @for ($i = ($screenNumber - 1) * 8; $i < (($screenNumber - 1) * 8) + 8; $i++)
                            @if (isset($imageDisplayLines[$i]))
                                <?php echo $imageDisplayLines[$i] ?>
                            @endif
                        @endfor
                    </select>
                </div>
            </div>
            <div class="row" style="text-align: center;">
                <input type="image" src="../assets/more-pics-btn.png" name="next" 
                    onclick="nextBrowsePage(<?php echo $numberOfScreens ?>, 
                    <?php echo htmlspecialchars(json_encode($gridArtIDs)) ?>, 
                    <?php echo htmlspecialchars(json_encode($imagePaths)) ?>,
                    <?php echo htmlspecialchars(json_encode($view)) ?>)" 
                    style="text-align: center; display: inline-block;">
            </div>
        </div>
        <div class="col-md-4 tagPanel" id="tagPanel">
            <div class='row' style="text-align: center;">Image Story</div>
            <div class='row' style="text-align: center; padding-bottom: 10px">
                <textarea id="story_text" style="width: 100%" placeholder="Who was your client? What was your goal and inspiration with this design? Let us know to give some background."></textarea>
            </div>
            <p style="text-align: center">What kind of image is this?</p>
            <div id="tag-group" class="row" style="text-align: center;">
                <div class="btn-group art-checkboxes" data-toggle="buttons">
                    <label id="business_cards_label" class="btn btn-primary">
                      <input id="business_cards" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> Business Cards
                    </label>
                    <label id="logos_label" class="btn btn-primary">
                      <input id="logos" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> Logos
                    </label>
                    <label id="letterhead_label" class="btn btn-primary">
                      <input id="letterhead" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> Letterhead
                    </label>
                    <label id="brochures_label" class="btn btn-primary">
                      <input id="brochures" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> Brochures
                    </label>
                </div>
                <div class="btn-group art-checkboxes" data-toggle="buttons">   
                    <label id="flyers_label" class="btn btn-primary">
                      <input id="flyers" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> Flyers
                    </label>
                    <label id="postcards_label" class="btn btn-primary">
                      <input id="postcards" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> Postcards
                    </label>
                    <label id="posters_label" class="btn btn-primary">
                      <input id="posters" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> Posters
                    </label>
                    <label id="magazines_label" class="btn btn-primary">
                      <input id="magazines" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> Magazines
                    </label>
                </div>
                <div class="btn-group art-checkboxes" data-toggle="buttons">
                    <label id="books_label" class="btn btn-primary">
                      <input id="books" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> Books
                    </label>
                    <label id="catalogs_label" class="btn btn-primary">
                      <input id="catalogs" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> Catalogs
                    </label>
                    <label id="packaging_label" class="btn btn-primary">
                      <input id="packaging" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> Packaging
                    </label>
                    <label id="presentations_label" class="btn btn-primary">
                      <input id="presentations" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> Presentation
                    </label>
                </div>
                <div class="btn-group art-checkboxes" data-toggle="buttons">
                    <label id="illustrations_label" class="btn btn-primary">
                      <input id="illustrations" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> Illustrations
                    </label>
                    <label id="website_label" class="btn btn-primary">
                      <input id="website" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> Website
                    </label>
                    <label id="mobile_app_label" class="btn btn-primary">
                      <input id="mobile_app" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> Mobile App
                    </label>
                    <label id="web_app_label" class="btn btn-primary">
                      <input id="web_app" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> Web App
                    </label>
                </div>
                <div class="btn-group art-checkboxes" data-toggle="buttons">
                    <label id="3d_label" class="btn btn-primary">
                      <input id="3d" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> 3D
                    </label>
                    <label id="desktop_app_label" class="btn btn-primary">
                      <input id="desktop_app" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> Desktop App
                    </label>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6" style="text-align: center;">
                    <a href="/artistBio" style="display: inline-block"><img src="../assets/done.png"></a>
                </div>
                <div class="col-md-6" style="text-align: center;">
                    <input type="image" name="deletePic" src="../assets/delete-image.png" onclick="deletePicClick()">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection