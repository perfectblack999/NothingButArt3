<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::auth();

Route::get('/', ['middleware' => 'web', 'uses' => 'HomeController@index']);
Route::get('/home', ['as' => 'home', 'uses' => 'HomeController@index']);
Route::post('/home/downloadResume', 'HomeController@downloadResume');

Route::get('/recruiterProfile/{id}', 'ProfileViewController@showRecruiterProfile');

Route::get('/editRecruiterProfile', ['as' => 'editRecruiterProfile', 'uses' => 'EditProfileController@EditRecruiterProfile']);
Route::post('/recruiter/update', 'editProfileController@UpdateRecruiterProfile');

Route::get('/editArtistProfile', ['as' => 'editArtistProfile', 'uses' => 'EditProfileController@EditArtistProfile']);
Route::post('/artist/update', 'EditProfileController@UpdateArtistProfile');

Route::get('user/activation/{token}', 'Auth\AuthController@activateUser')->name('user.activate');
Route::post('/activateProfile', 'EditProfileController@ActivateProfile');

Route::get('/newSearch', 'SearchArtistController@NewSearch');

Route::get('/editArt', ['middleware' => 'web', 'uses' => 'EditProfileController@EditArt']);
Route::post('/editArt/uploadArt', ['middleware' => 'web', 'uses' => 'EditProfileController@UploadArt']);

Route::get('/tagArt', ['as' => 'tagArt', 'uses' => 'TagArtController@ShowArt']);

Route::get('/getTag', 'TagArtController@GetTag');
Route::post('/saveTag', 'TagArtController@SaveTag');

Route::get('/artistBio', 'BioController@LoadBio');
Route::post('/artistBio/save', 'BioController@SaveBio');

Route::post('/chooseArt', ['as' => 'chooseArt', 'uses' => 'SearchArtistController@GetArt']);

Route::get('/nextSearchImages', 'SearchArtistController@NextSearchImages');
Route::post('/saveSelectedImages', 'SearchArtistController@SaveSelectedImages');

Route::get('/searchResults', 'SearchArtistController@SearchResults');