<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Products\CampaignController;


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

Route::group([
    'middleware' => 'api', 'cors',
    'prefix' => 'auth'

], function () {
    Route::post('/login', 'Auth\AuthController@login');
    Route::post('/register', 'Auth\AuthController@register');
    Route::post('/logout', 'Auth\AuthController@logout');
    Route::post('/refresh', 'Auth\AuthController@refresh');
    Route::get('/user-profile', 'Auth\AuthController@userProfile');
    Route::post('/change-pass', 'Auth\AuthController@changePassWord');
});

Route::group([
    'middleware' => 'api', 'cors', 'auth',
    'prefix' => 'campaign'

], function () {
    // get all URL: http://127.0.0.1:8000/api/campaign
    Route::get('', 'Campaign\CampaignController@getAll');

    // search URL: http://127.0.0.1:8000/api/campaign/search?name=h&start_date=&end_date=
    Route::get('/search', 'Campaign\CampaignController@search');

    // create http://127.0.0.1:8000/api/campaign
    Route::post('', 'Campaign\CampaignController@create');

    // update URL: http://127.0.0.1:8000/api/campaign/update/{id}
    Route::put('/update/{id}', 'Campaign\CampaignController@update');

    // delete URL: http://127.0.0.1:8000/api/campaign/delete/{id}
    Route::put('/delete/{id}', 'Campaign\CampaignController@deleteById');
});