@extends('layouts.app')

@section('content')
<h2>Your Top Creators</h2>
<div>
    @for($i = 0; $i < 10; $i++)
        @if(isset($topArtistData[$i]))
            <h3><?php echo ($i+1)."." ?></h3>
            <p><?php echo $topArtistData[$i]->first_name." ".$topArtistData[$i]->last_name ?><br>
            <?php echo "Email: ".$topArtistData[$i]->email ?><br>
            <?php echo "Phone: ".$topArtistData[$i]->phone ?></p>
        @endif
    @endfor
</div>

@endsection