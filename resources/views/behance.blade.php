@extends('layouts.app')

<link rel="stylesheet" href="css/custom_styles.css">
<link rel="stylesheet" href="css/image-picker.css">

@section('content')
    <div class="container">
        <div class="row" style="text-align: center;">
            <h1>Import Images from Behance</h1>
        </div>

        <form method="GET" action="behanceLookup">
            <div class='row' style="padding-bottom: 30px;">
                <input type="text" class="form-control smoothForm" name="username" placeholder="Search Your Behance Username">
            </div>
            <div class='row' style="text-align: center;">
                <input type='image' src='../assets/find-art.png'>
            </div>
        </form>
        <br/>

        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4" style="text-align:center"><h3>Imported Images:</h3></div>
            <div class="col-md-4"></div>
        </div>

        <?php if(!empty($images)){ ?>
                <div class="row" id="image_container">
                    <div class="col-md-2"></div>
                    <div class="col-md-8 grid" style="text-align: center">

                        <select id="artHolder" multiple="multiple" class="image-picker masonry">
                            <?php foreach($images as $image){ ?>
                                <option data-img-src="<?php echo $image ?>" value="<?php echo $image ?>"><?php echo $image ?></option>          
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-2"></div>          
                </div>
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4" style="text-align:center">
                        <input type="image" name="importImages" src="../assets/import-images.png"
                        onclick="saveBehanceImages()" style="text-align: center; display: inline-block;">
                    </div>
                    <div class="col-md-4"></div>
                </div>
        <?php } else { ?>
            <span>No Images</span>
        <?php } ?> 
        <br>
    </div>
@endsection