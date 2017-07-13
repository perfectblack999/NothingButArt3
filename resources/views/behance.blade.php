@extends('layouts.app')

@section('content')
    <div class="container">
    <h1>PHP - Behance API</h1>

    <form method="GET" action="behanceLookup">
        <div class="input-group">
            <input type="text" class="form-control" name="username" placeholder="Search Behance Username">
            <span class="input-group-btn">
                <button class="btn btn-default" type="submit">Submit</button>
            </span>
        </div><!-- /input-group -->
    </form>
    <br/>

    <table class="table table-bordered">
        <tr>
            <th>Images</th>
        </tr>
        <?php if(!empty($images)){ ?>
            <?php foreach($images as $image){ ?>
                <tr>
                    <td><img src="<?php echo $image ?>"></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td>What's your username?</td>
            </tr>
        <?php } ?>
    </table>
  </div>
@endsection