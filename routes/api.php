<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get("/emploi", [ApiController::class, "getLisEmploi"]);
Route::get("/emploi/{id}", [ApiController::class, "getEmploi"]);
Route::get("/cv", [ApiController::class, "getAllPostCv"]);
Route::get("/cv/{id}", [ApiController::class, "getPostCv"]);

//route pour les users
//Route::post("/user", [ApiController::class, "createUser"]);
Route::get("/users", [ApiController::class, "getUsers"]);


//route pour se connecter
Route::post("/login", [ApiController::class, "login"]);

