@extends('layouts.app')

@section('content')
<h2 style="text-align: center;">Your Top Creators</h2>

@if(count($topArtistData) > 0)
    @for($i = 0; $i < 10; $i++)
        @if(isset($topArtistData[$i]))
            <div class="row" style="padding-bottom: 20px; padding-top: 20px;">
                <div class="row" style="text-align:center; padding-bottom: 20px;">
                    <img src="../assets/unknown-portrait.png">
                </div>
                <div class="row" style="text-align:center;">
                    {!! Form::open(array('url' => '/search/downloadResume', 'enctype' => 'multipart/form-data')) !!}
                        {{ Form::hidden('artist_id', $topArtistData[$i]->id) }}
                        <input name="download_resume" type="image" src="../assets/download-resume.png"/>
                    {!! Form::close() !!}
                </div>
            </div>
        @endif
    @endfor
@else
<div class="row" style="text-align: center;">
    <p>Sorry, we couldn't find anyone. Try some different search criteria!</p>
</div>
@endif

@endsection