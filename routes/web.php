<?php

use App\Jobs\ImportContacts;
use App\Jobs\ReadFacebookFile;
use App\Models\Facebook\File;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

use Google\Cloud\Firestore\FirestoreClient;
use Propaganistas\LaravelPhone\PhoneNumber;

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
Route::get('images/{id}/{size?}', ['as' => 'image', 'uses' => 'ImageController@get']);


Auth::routes();

Route::get('/quick-search', 'PagesController@quickSearch')->name('quick-search');

Route::get('test2', function () {
    $line="972599460550:100002829538036:Ismail:Abu Hassanein:male:Gaza City:Gaza City:Single:AraPeak:2016::03/03/1993";
    $fileDb = File::find(2);

    $raw = $fileDb->lineToArray($line);
    $id = $fileDb->getIdData($raw);
    $datum = $fileDb->mapUser($raw);
    if ((!sizeof($datum)) || (!$id))
        dd($raw);
//            $fileDb->update(['file_line' => $LineNum]);


    dd($datum);
});
