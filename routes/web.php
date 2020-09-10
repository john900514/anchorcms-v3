<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use AnchorCMS\UserActiveClients;

Route::get('/', function () {
    return view('welcome');//redirect('dashboard');
});

Route::get('/home', function () {
    return redirect('dashboard');
});

Route::get('/switch/{client_id}', function ($client_id) {
    if(backpack_user()->isHostUser()) {
        if(backpack_user()->client_id == $client_id)
        {
            session()->forget('active_client');
            $sesh = UserActiveClients::whereUserId(backpack_user()->id)->delete();
        }
        else
        {
            session()->put('active_client', $client_id);

            $sesh = UserActiveClients::whereUserId(backpack_user()->id)->first();

            if(!is_null($sesh))
            {
                $sesh->client_id = $client_id;
                $sesh->save();
            }
            else
            {
                $sesh = new UserActiveClients();
                $sesh->user_id = backpack_user()->id;
                $sesh->client_id = $client_id;
                $sesh->save();
            }
        }
    }
    return redirect(url()->previous());
});

Route::get('/registration',  'UserRegistrationController@render_complete_registration');
Route::post('/registration', 'UserRegistrationController@complete_registration');
