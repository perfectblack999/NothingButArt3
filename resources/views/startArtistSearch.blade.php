@extends('layouts.app')
<link rel="stylesheet" href="css/demo.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/flexslider.css" type="text/css" media="screen" />

<link rel="stylesheet" href="css/custom_styles.css">
<script src="js/modernizr.js"></script>

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h1 style="text-align: center; padding-bottom: 25px">What are you looking for?</h1>
            <form id="categoryForm" action = "chooseArt" method = "post">
                <div id="tag-group">
                    <div class="btn-group art-checkboxes" data-toggle="buttons">
                        <label id="business_cards_label" class="btn btn-primary">
                          <input id="business_cards" name="business_cards" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> Business Cards
                        </label>
                        <label id="logos_label" class="btn btn-primary">
                          <input id="logos" name="logos" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> Logos
                        </label>
                        <label id="letterhead_label" class="btn btn-primary">
                          <input id="letterhead" name="letterhead" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> Letterhead
                        </label>
                        <label id="brochures_label" class="btn btn-primary">
                          <input id="brochures" name="brochures" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> Brochures
                        </label>
                        <label id="flyers_label" class="btn btn-primary">
                          <input id="flyers" name="flyers" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> Flyers
                        </label>
                        <label id="postcards_label" class="btn btn-primary">
                          <input id="postcards" name="postcards" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> Postcards
                        </label>
                    </div>
                    <div class="btn-group art-checkboxes" data-toggle="buttons">
                        <label id="posters_label" class="btn btn-primary">
                          <input id="posters" name="posters" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> Posters
                        </label>
                        <label id="magazines_label" class="btn btn-primary">
                          <input id="magazines" name="magazines" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> Magazines
                        </label>
                        <label id="books_label" class="btn btn-primary">
                          <input id="books" name="books" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> Books
                        </label>
                        <label id="catalogs_label" class="btn btn-primary">
                          <input id="catalogs" name="catalogs" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> Catalogs
                        </label>
                        <label id="packaging_label" class="btn btn-primary">
                          <input id="packaging" name="packaging" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> Packaging
                        </label>
                        <label id="presentations_label" class="btn btn-primary">
                          <input id="presentations" name="presentations" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> Presentation
                        </label>
                    </div>
                    <div class="btn-group art-checkboxes" data-toggle="buttons">
                        <label id="illustrations_label" class="btn btn-primary">
                          <input id="illustrations" name="illustrations" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> Illustrations
                        </label>
                        <label id="website_label" class="btn btn-primary">
                          <input id="website" name="website" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> Website
                        </label>
                        <label id="mobile_app_label" class="btn btn-primary">
                          <input id="mobile_app" name="mobile_app" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> Mobile App
                        </label>
                        <label id="web_app_label" class="btn btn-primary">
                          <input id="web_app" name="web_app" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> Web App
                        </label>
                        <label id="3d_label" class="btn btn-primary">
                          <input id="3d" name="3d" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> 3D
                        </label>
                        <label id="desktop_app_label" class="btn btn-primary">
                          <input id="desktop_app" name="desktop_app" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> Desktop App
                        </label>
                    </div>
                </div>
                <div style="padding-top:20px;" class="row">
                    <div class="col-md-12" style="text-align: center;">
                        <label>Zip</label>
                    </div>
                </div>
                <div class="row">    
                    <div class="col-md-12" style="text-align: center;">
                        <input id="zip" name="zip" type="text" class="smoothForm">
                    </div>
                </div>
                @if ($errors->has('zip'))
                    <div class="col-md-12">
                        <div class="col-md-12">
                            <span class="help-block" style="text-align: center;"><strong>{{ $errors->first('zip') }}</strong></span>
                        </div>
                    </div>
                @endif
                <div style="padding-top:20px;" class="row">
                    <div class="col-md-12" style="text-align: center;">
                        <label>Within how many miles?</label>
                    </div>
                </div>
                <div class="row">    
                    <div class="col-md-12" style="text-align: center;">
                        <input id="distance" name="distance" type="text" class="smoothForm">
                    </div>
                </div>
                @if ($errors->has('distance'))
                    <div class="col-md-12">
                        <div class="col-md-12">
                            <span class="help-block" style="text-align: center;"><strong>{{ $errors->first('distance') }}</strong></span>
                        </div>
                    </div>
                @endif
                <div style="text-align: center; padding:15px;">
                    <input id="searchBtn" name="next" type="image" src="../assets/search.png"/>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection