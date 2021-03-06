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
//Route::get('/login', ['as' => 'login', 'uses' => 'HomeController@login']);

Route::get('/recruiterProfile/{id}', 'ProfileViewController@showRecruiterProfile');

Route::get('/editRecruiterProfile', ['as' => 'editRecruiterProfile', 'uses' => 'EditProfileController@EditRecruiterProfile']);
Route::post('/recruiter/update', 'EditProfileController@UpdateRecruiterProfile');

Route::get('/editArtistProfile', ['as' => 'editArtistProfile', 'uses' => 'EditProfileController@EditArtistProfile']);
Route::post('/artist/update', 'EditProfileController@UpdateArtistProfile');

Route::get('user/activation/{token}', 'Auth\AuthController@activateUser')->name('user.activate');
Route::post('/activateProfile', 'EditProfileController@ActivateProfile');

Route::get('/newSearch', 'SearchArtistController@NewSearch');

Route::get('/editArt', ['as' => 'editArt', 'middleware' => 'web', 'uses' => 'EditProfileController@EditArt']);
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

Route::get('/behance', 'BehanceController@ShowForm');
Route::get('/behanceLookup', 'BehanceController@GetData');

Route::post('/importBehanceImages', 'BehanceController@ImportImages');
Route::get('/homeBehanceImport', 'BehanceController@HomeBehanceImport');
Route::get('/nextBehancePage', 'BehanceController@NextBehancePage');

Route::get('/redirect', 'SocialAuthController@redirect');
Route::get('/callback', 'SocialAuthController@callback');

Route::get('500', function()
{
    abort(500);
});

Route::get('/deleteAccount', 'EditProfileController@DeleteProfile');
Route::get('/confirmDeleteProfile', 'EditProfileController@ConfirmDeleteProfile');

Route::post('/deletePic', 'TagArtController@DeletePic');

Route::get('/inviteFriends', 'InviteFriendsController@Display');
Route::post('/emailInvites', 'InviteFriendsController@EmailInvites');

Route::post('/search/downloadResume', 'SearchArtistController@DownloadResume');
Route::post('/showArtistImages', 'SearchArtistController@ShowArtistImages');

Route::get('/browseArt', 'BrowseArtController@Display');
Route::get('/nextBrowsePage', 'BrowseArtController@NextBrowsePage');

Route::get('/profileStats', 'EditProfileController@ShowStatsPage');