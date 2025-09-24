<?php

use DutchCodingCompany\FilamentSocialite\Models\SocialiteUser;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/auth/google/redirect', function () {
//     return SocialiteUser::driver('google')
//     ->stateless()    
//     ->with(['prompt' => 'none']) // 👈 evita que siempre pida seleccionar cuenta
//         ->redirect();
// });

