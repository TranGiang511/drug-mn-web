<?php

use App\Http\Controllers\InfoController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\NewTranController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\NewController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ResgiterController;
use App\Http\Controllers\AuthController;
use App\Mail\EmailResetPassword;
use Illuminate\Support\Facades\Mail;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/home', [HomeController::class, 'index'])
    ->name('home')
    ->middleware('switch_lang');

Route::get('/set-language/{lang}', [HomeController::class,'switch_lang'])
    ->name('setLanguage')
    ->middleware('switch_lang');

// Auth::routes();

Route::group(['middleware' => ['auth', 'logout_when_reset_password', 'switch_lang']], function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/admin', function () {
        return view('admin/index');
    });
});

//-----------------------------------------------------------------------------------
Route::group(['middleware' => ['switch_lang']], function () {
    Route::get('/login', [AuthController::class, 'index'])
        ->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/logout', [AuthController::class, 'logout']);

    Route::get('/register', [ResgiterController::class, 'index']);
    Route::post('/register', [ResgiterController::class, 'register']);

    Route::get('/reset_password', [AuthController::class, 'reset_password_show'])
        ->name('reset_password');
    Route::post('/reset_password', [AuthController::class, 'reset_password_submit'])
        ->name('reset_password_submit');

    Route::get('/password/reset/{email}/{token}', [AuthController::class, 'change_the_password_show'])
        ->name('change_the_password');

    // Route::get('/reset_password_random', [AuthController::class, 'reset_password_random_show'])
        // ->name('reset_password_random');
    Route::post('/reset_password_random', [AuthController::class, 'reset_password_random_submit'])
        ->name('reset_password_random_submit');

    // Route::get('/password/reset/{email}', [AuthController::class, 'change_the_password_show'])
        // ->name('change_the_password');

    // Route::get('/password/reset/', [AuthController::class, 'change_the_password_submit']);
    Route::post('/password/reset/', [AuthController::class, 'change_the_password_submit'])->name('password_reser');
});

//-----------------------------------------------------------------------------------
Route::group(['middleware'=>['logout_when_reset_password', 'admin', 'switch_lang']], function() {
    Route::get('/users/student', [UserController::class, 'index'])
        ->name('student.index');

    Route::get('/users/student/create',[UserController::class, 'create']);
    Route::post('/users/student', [UserController::class, 'store']);

    Route::get('/users/student/{user}',[UserController::class, 'show'])
        ->name('student_show');

    Route::get('/users/student/{user}/edit', [UserController::class, 'edit']);
    Route::put('/users/student/{user}', [UserController::class, 'update'])
        ->name('student_update');

    Route::get('/users/student/delete/{user}', [UserController::class, 'destroy'])
        ->name('delete_user_by_id');

    Route::get('/usersDeleteMutil', [UserController::class, 'delete_mutil']);
    Route::delete('/usersDeleteMutil', [UserController::class, 'delete_mutil']);

    Route::get('/exportUsers', [UserController::class, 'export']);
    Route::post('/exportUsers', [UserController::class, 'export'])
        ->name('export_users');

    Route::get('/importUsers', [UserController::class, 'import']);
    Route::post('/importUsers', [UserController::class, 'import'])
        ->name('import_users');
});

Route::group(['middleware'=>['logout_when_reset_password', 'admin', 'switch_lang']], function() {
    Route::get('/users/self_show', [UserController::class, 'self_show'])
        ->name('users.self_show');
    Route::get('/users/self_edit/{user}', [UserController::class, 'self_edit'])
        ->name('users.self_edit');
    Route::get('/users/self_update/{user}', [UserController::class, 'self_update']);
    Route::put('/users/self_update/{user}', [UserController::class, 'self_update'])
        ->name('users.self_update');
});

//----------------------------------------------------------------------- 
Route::group(['middleware'=>['logout_when_reset_password', 'admin', 'switch_lang']], function() {
    Route::get('/news/mn_users', [NewController::class, 'mn_users_index'])
        ->name('news.mn_users.index');

    Route::get('/news/mn_users/{new}/show', [NewController::class, 'news_mn_user_show']);
    
    Route::get('/news/public/{new}', [NewController::class, 'public']);
    Route::get('/newsDeleteMutilAuth', [NewController::class, 'news_delete_mutil_auth']);
    Route::delete('/newsDeleteMutilAuth', [NewController::class, 'news_delete_mutil_auth']);
    Route::get('/newsDeleteMutilUsers', [NewController::class, 'news_delete_mutil_users']);
    Route::delete('/newsDeleteMutilUsers', [NewController::class, 'news_delete_mutil_users']);
    Route::get('/news/banner/{new}', [NewController::class, 'banner']);
    Route::get('/newsBannerMutil', [NewController::class, 'news_banner_mutil']);
    Route::post('/newsBannerMutil', [NewController::class, 'news_banner_mutil'])
        ->name('news_banner_mutil');
    Route::get('/newsPublicMutil', [NewController::class, 'news_public_mutil']);
    Route::post('/newsPublicMutil', [NewController::class, 'news_public_mutil'])
        ->name('news_public_mutil');      
});

Route::group(['middleware'=>['logout_when_reset_password', 'auth', 'switch_lang']], function() {
    //NEWS
    Route::get('/news/{new}/self_edit', [NewController::class, 'self_edit']);
    Route::put('/news/{new}/self_edit', [NewController::class, 'update']);
    Route::get('/news/delete/{new}', [NewController::class, 'destroy'])
        ->name('delete_new_by_id')
        ->middleware('edit_delete_news');
    Route::get('/newsDeleteMutil', [NewController::class, 'delete_mutil']);
    Route::delete('/newsDeleteMutil', [NewController::class, 'delete_mutil']);

    //NEWS AUTH
    Route::get('/news/auth',[NewController::class, 'news_auth_index'])
        ->name('news.auth.index');

    Route::get('/news_auth/{new}/show', [NewController::class, 'news_auth_show']);
        
    Route::get('/news_auth/{new}/edit', [NewController::class, 'news_auth_edit']);
    Route::put('/news_auth/{new}/update_vi', [NewController::class, 'news_auth_update_vi']);
    Route::put('/news_auth/{id_news}/update_en', [NewController::class, 'news_auth_update_en']);
    Route::post('/news_auth/{id_news}/create_en', [NewController::class, 'news_auth_create_en']);
});
    
//->auth + $user = Auth::user();
Route::group(['middleware'=>['logout_when_reset_password', 'auth', 'switch_lang']], function() {
    //NEWS
    Route::get('/news/create',[NewController::class, 'create']);
    Route::post('/news/create', [NewController::class, 'store']);
});

Route::group(['middleware' => ['logout_when_reset_password', 'auth', 'switch_lang']], function () {
    Route::get('/news/create_en/{id_news}',[NewTranController::class, 'create_en']);
    Route::get('news/store_en/{id_news}', [NewTranController::class, 'store_en']);
    Route::post('news/store_en/{id_news}', [NewTranController::class, 'store_en'])->name('store_en');
});

Route::group(['middleware' => ['switch_lang']], function () {
    Route::get('/news', [NewController::class, 'index'])
        ->name('news.index');
    Route::get('/news/{new}',[NewController::class, 'show']);

    // Route::get('/new/search', [NewController::class, 'search'])->name('search'); 
    Route::get('/calendar', [NewController::class, 'calendar']);
    Route::get('/calendar-show/{formattedDate}', [NewController::class, 'calendar_show']);
});

//-----------------------------------------------------------------------------------
Route::group(['middleware' => ['switch_lang']], function () {
    Route::post('/contact', [ContactController::class, 'contact_submit'])
        ->name('contact_submit');
});

Route::group(['middleware'=>['logout_when_reset_password', 'admin', 'switch_lang']], function() {
    Route::get('/contact', [ContactController::class, 'index'])
        ->name('contact.index');

    Route::get('/contact/delete/{contact}', [ContactController::class, 'destroy'])
        ->name('delete_contact_by_id');

    Route::get('/contact/{contact}',[ContactController::class, 'show'])
        ->name('contact_show');

    Route::get('/contactDeleteMutil', [ContactController::class, 'delete_mutil']);
    Route::delete('/contactDeleteMutil', [ContactController::class, 'delete_mutil']);
});


//-----------------------------------------------------------------------------------
Route::group(['middleware' => ['switch_lang']], function () {
    Route::get('/info/{infoType}', [InfoController::class, 'type'])->name('info.type');
});

Route::group(['middleware'=>['logout_when_reset_password', 'admin', 'switch_lang']], function() {
    Route::get('/mn_info', [InfoController::class, 'index']);

    Route::get('/mn_info/create', [InfoController::class, 'create']);
    Route::post('/mn_info/create', [InfoController::class, 'store']);

    Route::get('/mn_info/{info}',[InfoController::class, 'show']);

    Route::get('/mn_info/{info}/edit', [InfoController::class, 'edit']);
    Route::put('/mn_info/{info}/edit', [InfoController::class, 'update']);

    Route::get('/mn_info/{info}/delete/', [InfoController::class, 'destroy']);

    // Route::get('/mnInfoDeleteMutil', [InfoController::class, 'delete_mutil']);
    // Route::delete('/mnInfoDeleteMutil', [InfoController::class, 'delete_mutil']);
});



