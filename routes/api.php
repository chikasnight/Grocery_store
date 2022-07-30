<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GroceryController;
use App\Http\Controllers\UserController;


Route::group(['middleware' =>'auth:sanctum'],function(){
    Route::post('groceries',[GroceryController::class,'createGrocery']);
    Route::put('groceries/{groceryId}',[GroceryController::class,'editGrocery']);
    Route::delete('groceries/{groceryId}',[GroceryController::class,'deleteGrocery']);
    Route::post('logout',[UserController::class,'logout']);
});

Route::post('register',[UserController::class,'register']);
Route::post('login',[UserController::class,'login']);
Route::get('groceries/search',[GroceryController::class,'search']);
Route::get('groceries/{groceryId}',[GroceryController::class,'getGrocery']);
