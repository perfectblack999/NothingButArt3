@extends('layouts.app')
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
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4" style="text-align: center;"><a href="/tagArt">Don't forget to tag your work!</a></div>
                    <div class="col-md-4"></div>
                </div>
                <div class="row">
                    <div class="col-md-1"></div><div class="col-md-10" style="text-align: center"><h3>Add some more art:</h3></div><div class="col-md-1"></div>
                </div>
                <div class="row">
                    <div class="col-md-1"></div><div class="col-md-10" style="text-align: center"><h4>Import from Behance</h4></div><div class="col-md-1"></div>
                </div>
                <div class="row">
                    <div class="col-md-4"></div><div class="col-md-4" style="text-align: center"><div><a href="/behance"><img src="assets/behance_icon.png"></a></div><div class="col-md-4"></div>
                </div>
                <div class="row">
                    <div class="col-md-1"></div><div class="col-md-10" style="text-align: center"><h4>or, upload from computer:</h4></div><div class="col-md-1"></div>
                </div>
                <div class="panel-body">                     
                    {!! Form::open(array('url' => '/editArt/uploadArt', 
                    'enctype' => 'multipart/form-data', 'method' => 'POST',
                    'files' => true)) !!}
                        {!! csrf_field() !!}
                        <div class="col-md-12">
                            <div class="col-md-3">{!! Form::file('art_upload[]', array('multiple' => true)) !!}</div>
                        </div><br><br><br>

                        <div class="col-md-12">
                            <div class="col-md-3">{!! Form::submit('Add designs') !!}</div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
