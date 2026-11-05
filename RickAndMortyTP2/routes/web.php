
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CharacterController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/characters', [CharacterController::class, 'index']); // llamar al metodo index del controlador CharacterController
