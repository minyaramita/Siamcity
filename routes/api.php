<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResources(['user' => 'API\UserController']);
Route::get('/findUser', 'API\UserController@search');
Route::get('/profile', 'API\UserController@profile');
Route::put('/profile', 'API\UserController@updateProfile');

Route::apiResources(['school' => 'API\SchoolController']);
Route::get('/findSchool', 'API\SchoolController@search');
Route::get('/schoolDropdown', 'API\SchoolController@schoolDropdown');

Route::apiResources(['hospital' => 'API\HospitalController']);
Route::get('/findHospital', 'API\HospitalController@search');

Route::apiResources(['insurer' => 'API\InsurerController']);
Route::get('/findInsurer', 'API\InsurerController@search');

Route::apiResources(['claim' => 'API\ClaimController']);
Route::get('/findClaim', 'API\ClaimController@search');

Route::apiResources(['namelist' => 'API\NamelistController']);
Route::get('/findNamelist', 'API\NamelistController@search');