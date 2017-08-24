@extends('layouts.app')

@section('content')
<h2>Your Top Creators</h2>
<div>
    @for($i = 0; $i < 10; $i++)
        @if(isset($topArtistData[$i]))
            <h3><?php echo ($i+1)."." ?></h3>
            <p><?php echo $topArtistData[$i]->first_name." ".$topArtistData[$i]->last_name ?><br>
            <p>
                {!! Form::open(array('url' => '/search/downloadResume', 'enctype' => 'multipart/form-data')) !!}
                    {{ Form::hidden('artist_id', $topArtistData[$i]->id) }}   
                    <div class="col-md-12">
                        <div class="col-md-3">{!! Form::submit('Download Resume') !!}</div>
                    </div>
                {!! Form::close() !!}
            </p>
        @endif
    @endfor
</div>

@endsection