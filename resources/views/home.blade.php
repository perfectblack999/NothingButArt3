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

                <div class="panel-body">                   
                    @if($user->type == "artist")
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
                        <div class="col-md-12">
                            <div><a href="/newSearch">Search for your next designer</a></div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
