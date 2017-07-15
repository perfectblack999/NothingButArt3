@extends('layouts.app')

<link rel="stylesheet" href="css/custom_styles.css">
<link rel="stylesheet" href="css/image-picker.css">

@section('content')
    <div class="container">
        <h1>Import Images from Behance</h1>

        <form method="GET" action="behanceLookup">
            <div class="input-group">
                <input type="text" class="form-control" name="username" placeholder="Search Your Behance Username">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="submit">Submit</button>
                </span>
            </div><!-- /input-group -->
        </form>
        <br/>

        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4" style="text-align:center"><h3>Imported Images:</h3></div>
            <div class="col-md-4"></div>
        </div>

        <?php if(!empty($images)){ ?>
            <form method="POST" action="importBehanceImages">
                <div class="row" id="image_container">
                    <div class="col-md-2"></div>
                    <div class="col-md-8 grid" style="text-align: center">

                        <select id="artHolder" multiple="multiple" class="image-picker show-html masonry">
                            <?php foreach($images as $image){ ?>
                                <option data-img-src="<?php echo $image ?>" value="<?php echo $image ?>"><?php echo $image ?></option>          
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-2"></div>          
                </div>
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4" style="text-align:center"><button class="btn btn-default" type="submit">Import Images</button></div>
                    <div class="col-md-4"></div>
                </div>
            </form>
        <?php } else { ?>
        <span>No Images</span>
        <?php } ?> 
        <br>
    </div>
@endsection