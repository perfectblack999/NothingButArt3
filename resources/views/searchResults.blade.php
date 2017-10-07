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
                    <div class="col-md-4"></div>
                    {!! Form::open(array('url' => '/search/downloadResume', 'enctype' => 'multipart/form-data')) !!}
                        {{ Form::hidden('artist_id', $topArtistData[$i]->id) }} 
                        <div class="col-md-2">
                            <input name="download_resume" type="image" src="../assets/download-resume.png"/>
                        </div>
                    {!! Form::close() !!}
                    {!! Form::open(array('url' => '/showArtistImages')) !!}
                        {{ Form::hidden('search_id', $searchID) }}
                        {{ Form::hidden('artist_id', $topArtistData[$i]->id) }}
                        <div class="col-md-2">
                            <input name="images_selected" type="image" src="../assets/pics-you-liked.png"/>
                        </div>
                    {!! Form::close() !!}
                    <div class="col-md-4"></div>
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