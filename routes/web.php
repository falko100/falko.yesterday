<?php

use App\Services\CocktailDbClient;
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
    throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
});


Route::get('/test', function () {
    $cocktailDbClient = resolve(CocktailDbClient::class);
    $cocktails = \App\Data\DatabaseCocktailData::collection($cocktailDbClient->getCocktailsByFirstLetter('e')['drinks']);
    dd($cocktails);
});
