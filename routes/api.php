<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Create by : Superbenz33
| Service Tools : Postman
| 
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

route::get('login', function (){
    abort(401);
    // Redirect to Login page
})->name('login');

// Authorization Section || Sanctam
route::post('login', function () {

    // Check Email & Password
    $credentail = request()->only(['email','password']);
    
    // If Email & Password not match
    if( !auth()->validate($credentail) ) {
        abort(401);
    } else {

        // If Email & Password match
        $user = User::where('email', $credentail['email'])->first();
        $user->tokens()->delete();

        // Check token ability (Permission)
        $_Token = $user->createToken('external_user', ['Superadmin']);
        
        // Return Token to user
        return response()->json(['token' => $_Token->plainTextToken]);
    }
    
});

// Respone Data Section || Sanctam
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('numbers', function(){
        $user = auth()->user();

        // Check User Permission
        if ($user->tokenCan('Superadmin')) {
            return response()->json([1, 2, 3, 4, 5]);
        } else {
            abort(403);
        }
    });
});


