@extends('layouts.app')
<link rel="stylesheet" href="css/image-picker.css">

@section('content')
<div class="row" id="image_container">
    <div class="col-md-2"></div>
    <div class="col-md-10 grid">
        <select id="artHolder" multiple="multiple" class="image-picker show-html masonry">    
            @for ($i = ($screenNumber - 1) * 9; $i < (($screenNumber - 1) * 9) + 9; $i++)
                @if (isset($imageDisplayLines[$i]))
                    <?php echo $imageDisplayLines[$i] ?>
                @endif
            @endfor
        </select>
    </div>
</div>
<div class="row">
    <div class="col-md-12" style="text-align: center;">

        <input type="image" name="next" src="../assets/more-pics.png" 
            onclick="nextBrowsePage(<?php echo $numberOfScreens ?>, 
            <?php echo htmlspecialchars(json_encode($gridArtIDs)) ?>, 
            <?php echo htmlspecialchars(json_encode($imagePaths)) ?>,
            <?php echo htmlspecialchars(json_encode($browseArtView)) ?>)" 
            style="text-align: center; display: inline-block;">
    </div>
</div>

@endsection