@extends('layouts.app')

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="stylesheet" href="css/image_slider.css">
<link rel="stylesheet" href="css/custom_styles.css">
<link rel="stylesheet" href="css/demo.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/flexslider.css" type="text/css" media="screen" />

<script src="js/modernizr.js"></script>

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>
                <section class="slider">
                    <div id="slider" class="flexslider">
                        <ul class="slides">
                            @foreach($images as $image)
                                <li id="slide-list">
                                    <?php echo $image[1] ?>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div id="carousel-holder">
                        <div id="carousel" class="flexslider">
                            <ul class="slides" id="carousel-list">
                                @foreach($images as $image)
                                    <li>
                                        <?php echo $image[1] ?>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </section>
                <div id="tag-group">
                    <div class="btn-group" data-toggle="buttons">
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
                        <label id="flyers_label" class="btn btn-primary">
                          <input id="flyers" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> Flyers
                        </label>
                        <label id="postcards_label" class="btn btn-primary">
                          <input id="postcards" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> Postcards
                        </label>
                    </div>
                    <div class="btn-group" data-toggle="buttons">
                        <label id="posters_label" class="btn btn-primary">
                          <input id="posters" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> Posters
                        </label>
                        <label id="magazines_label" class="btn btn-primary">
                          <input id="magazines" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> Magazines
                        </label>
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
                    <div class="btn-group" data-toggle="buttons">
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
                        <label id="3d_label" class="btn btn-primary">
                          <input id="3d" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> 3D
                        </label>
                        <label id="desktop_app_label" class="btn btn-primary">
                          <input id="desktop_app" type="checkbox" autocomplete="off" onchange="tagClick(this.id)"> Desktop App
                        </label>
                    </div>
                </div>
                <div style="padding-top:50px;text-align: center; ">
                    <a class="btn btn-primary" href="/artistBio" style="display: inline-block">Done</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection