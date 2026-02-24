<?php


use App\Http\Controllers\HomeController;
use App\Http\Controllers\Newsletter\NewsletterController;
use App\Http\Controllers\Onboarding\OnboardingController;
use App\Http\Controllers\Page\PageController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\Payment\SubscriptionController;
use App\Http\Controllers\Talent\TalentArticleController;
use App\Http\Controllers\Talent\TalentBookController;
use App\Http\Controllers\Talent\TalentController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Talent\TalentFilmographyController;
use App\Http\Controllers\Talent\TalentTempController;
use App\Http\Controllers\Video\VideoController;
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

Route::get('/home',[HomeController::class,'index']);
Route::get('/home/data',[HomeController::class,'getMoreData']);
Route::get ('/latest-articles' , [TalentController::class,'getLatestArticles']);



Route::post('/subscribe',[NewsletterController::class,'store']);

Route::get('/on-boarding',[OnboardingController::class,'index']);
Route::post('/on-boarding',[OnboardingController::class,'store']);
Route::get ('/subscription/{status}/enroll/{enroll}/stripe/{stripe_id}' , [SubscriptionController::class,'index']);

Route::get('/search',[TalentController::class,'search']);

Route::get ('/talent/{lang}/{talent}/book' , [TalentBookController::class,'index']);
Route::post ('/talent/{talent}/book' , [TalentBookController::class,'store']);
Route::get ('/payment/{status}/order/{order}' , [PaymentController::class,'index']);


//talent temp
Route::get( '/talent-temp/{lang}/{talent}' , [ TalentTempController::class , 'show' ] );
Route::get( '/talent-temp/{lang}/{talent}/book' , [ TalentTempController::class , 'book' ] );
Route::get( '/talent-temp/{lang}/{talent}/{title}' , [ TalentTempController::class , 'index' ] );

Route::post ('/talent/{talent}/promo-code-validity/{code}' , [TalentBookController::class,'promoCodeValidity']);


Route::get ('/talent/{lang}/{talent}/article' , [TalentArticleController::class,'getMoreArticles']);
Route::get ('/talent/{lang}/{talent}/spot/{is_published}' , [TalentController::class,'getSpotFirstItem']);
Route::get ('/talent/{lang}/{talent}/spot/type/{type}' , [TalentController::class,'getSpotData']);
Route::get ('/talent/{lang}/{talent}/insight/type/{type}' , [TalentController::class,'getInsightData']);

Route::get ('/talent/{lang}/{talent}/{title}' , [TalentArticleController::class,'index']);

Route::get ('/talent' , [TalentController::class,'index']);
Route::get ('/talent/{lang}/{talent}' , [TalentController::class,'show']);
Route::post ('/talent/{talent}/notify' , [TalentController::class,'notify']);
Route::post ('/talent/{talent}/verify' , [TalentController::class,'verifyIdentity']);

Route::get ('/filmography/{slug}' , [TalentFilmographyController::class,'show']);

Route::get ('/video/{code}/preview' , [VideoController::class,'index']);

Route::get ('/category' , [CategoryController::class,'index']);

Route::get ('/category/{slug}' , [CategoryController::class,'show']);

Route::group( ['prefix' => 'page' ,] , function () {
    Route::get ('/about', [PageController::class, 'about']);
    Route::get ('/privacy-policy', [PageController::class, 'privacy_policy']);
    Route::get ('/terms', [PageController::class, 'terms']);
    Route::get ('/contact', [PageController::class, 'contact']);
});
