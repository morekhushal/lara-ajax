<?php

use Illuminate\Support\Facades\Route;

/* Route::get('/', function () {
    return view('welcome');
}); */

Route::get('/', function(){
    return view('login');
});

Route::get('login', function(){
    return view('login');
})->name('login');

Route::view('allposts', 'allposts');
Route::view('addpost', 'addpost');
