@extends('layouts.app')

<link rel="stylesheet" href="css/image-picker.css">

@section('content')
<h3 style="text-align: center; padding-bottom: 20px;">See something you like? Select the image and click next!</h3>
<div class="row" id="image_container">
    <div class="col-md-1"></div>
    <div class="col-md-11 grid" style="text-align: center; max-width: 1200px;">
        <select id="artHolder" multiple="multiple" class="image-picker masonry">    
            @for ($i = ($screenNumber - 1) * 8; $i < (($screenNumber - 1) * 8) + 8; $i++)
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
        <input type="image" name="next" src="../assets/next.png"
            onclick="nextSearchImage(<?php echo $searchID ?>, <?php echo $numberOfScreens ?>, 
            <?php echo htmlspecialchars(json_encode($gridArtIDs)) ?>)" 
            style="text-align: center; display: inline-block;">
    </div>
</div>

@endsection