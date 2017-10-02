@extends('layouts.app')
<link rel="stylesheet" href="css/demo.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/flexslider.css" type="text/css" media="screen" />

<script src="js/modernizr.js"></script>

@section('content')
<div class="container">
    <div class="row" style="text-align: center; padding-bottom: 50px">
        <h1>Craft Your Portfolio</h1>
        <p>Show the world what you've made.</p>
    </div>
    <div class="row" style="text-align:center; padding-bottom: 25px; vertical-align: central">
        <div class="col-md-3" style="text-align: center">
            {!! Form::open(array('url' => '/editArt/uploadArt', 
                'enctype' => 'multipart/form-data', 'method' => 'POST',
                'files' => true, 'id' => 'img-upload-form')) !!}
                {!! csrf_field() !!}
                <div class="image-upload">
                    <label for="art_upload">
                        <img src="../assets/add-art-btn.png"/>
                    </label>
                    {!! Form::file('art_upload[]', array('multiple' => true, 'id' => 'art_upload')) !!}
                </div>
            {!! Form::close() !!}
        </div>
        <div class="col-md-3" style="text-align: center">
            <div class="row" style="text-align: center"><p>Import from Behance:</p></div>
            <div class="row" style="text-align: center">
                <a href="/behance"><img src="assets/behance_icon.png"></a>
            </div>
        </div>
        <div class="col-md-3" style="text-align: center">
            <h1>Then</h1>
        </div>
        <div class="col-md-3" style="text-align: center">
            <div class="row" style="text-align: center">
                <p>Tag your work to have employers find you.</p>
            </div>
            <div class="row" style="text-align: center">
                <a href="/tagArt"><img src="../assets/tag-btn.png"></a>
            </div>
        </div>
    </div>
    @if(isset($fileTypeError))
        @if($fileTypeError == 1)
            <div class="row" style='text-align: center;'><p style="color:red">Sorry, we only support jpg's and png's right now!</p></div>
        @elseif($fileTypeError == 2)
            <div class="row" style='text-align: center;'><p style="color:red">Jeez that's a big file. Try making it less than 5MB.</p></div>
        @endif
    @endif
</div>
    
@endsection
