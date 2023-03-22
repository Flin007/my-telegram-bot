<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::post('/webhook', function (){
    return response('OK', 200);
});

Route::get('/send', function (){
    $token = "6176741474:AAG9nuq9y7KQV3XlvDE_slLAQnII34sKzpY";
    $chatId = 264493118;
    $text = 'Hello world';
    $data = http_build_query([
        'text' => $text,
        'chat_id' => $chatId,
    ]);
    $url = "https://api.telegram.org/bot$token/sendMessage?{$data}";
    file_get_contents($url);

    return response('OK', 200);
});


















