<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Tracking Pixel Routes
|--------------------------------------------------------------------------
|
| Here is where you can register routes for AnchorCMS's tracking pixel.
| These routes are loaded by the RouteServiceProvider within a group
| which  is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('{client_uuid}', 'CapeAndPizzaPixelController@get_pixel');
Route::get('{client_uuid}/pizza-lib', 'CapeAndPizzaPixelController@get_pixel_js');
