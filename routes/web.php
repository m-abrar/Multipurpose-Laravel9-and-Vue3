<?php

use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\PropertiesController;
use App\Http\Controllers\Admin\PropertyTypesController;
use App\Http\Controllers\Admin\AmenitiesController;
use App\Http\Controllers\Admin\AppointmentStatusController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DashboardStatController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ApplicationController;
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
    return view('welcome');
});

// Route::get('/admin/dashboard', function () {
//     return view('dashboard');
// });

Route::middleware('auth')->group(function () {
    Route::get('/api/stats/appointments', [DashboardStatController::class, 'appointments']);
    Route::get('/api/stats/users', [DashboardStatController::class, 'users']);

    Route::get('/api/users', [UserController::class, 'index']);
    Route::post('/api/users', [UserController::class, 'store']);
    Route::patch('/api/users/{user}/change-role', [UserController::class, 'changeRole']);
    Route::put('/api/users/{user}', [UserController::class, 'update']);
    Route::delete('/api/users/{user}', [UserController::class, 'destory']);
    Route::delete('/api/users', [UserController::class, 'bulkDelete']);

    Route::get('/api/clients', [ClientController::class, 'index']);

    Route::get('/api/appointment-status', [AppointmentStatusController::class, 'getStatusWithCount']);
    Route::get('/api/appointments', [AppointmentController::class, 'index']);
    Route::post('/api/appointments/create', [AppointmentController::class, 'store']);
    Route::get('/api/appointments/{appointment}/edit', [AppointmentController::class, 'edit']);
    Route::put('/api/appointments/{appointment}/edit', [AppointmentController::class, 'update']);
    Route::delete('/api/appointments/{appointment}', [AppointmentController::class, 'destroy']);

    Route::get('/api/propertytypes', [PropertyTypesController::class, 'index']);
    Route::post('/api/propertytypes/create', [PropertyTypesController::class, 'store']);
    Route::get('/api/propertytypes/withcount', [PropertyTypesController::class, 'getTypesWithCount']);
    Route::get('/api/propertytypes/{propertyType}/edit', [PropertyTypesController::class, 'edit']);
    Route::put('/api/propertytypes/{propertyType}/edit', [PropertyTypesController::class, 'update']);
    Route::delete('/api/propertytypes/{propertytype}', [PropertyTypesController::class, 'destroy']);
    Route::post('/api/propertytypes/upload-image', [PropertyTypesController::class, 'uploadImage']);

    Route::get('/api/properties', [PropertiesController::class, 'index']);
    Route::post('/api/properties/create', [PropertiesController::class, 'store']);
    Route::get('/api/properties/{properties}/edit', [PropertiesController::class, 'edit']);
    Route::put('/api/properties/{properties}/edit', [PropertiesController::class, 'update']);
    Route::delete('/api/properties/{properties}', [PropertiesController::class, 'destroy']);

    Route::get('/api/amenities', [AmenitiesController::class, 'index']);
    Route::post('/api/amenities/create', [AmenitiesController::class, 'store']);
    Route::get('/api/amenities/withcount', [AmenitiesController::class, 'getTypesWithCount']);
    Route::get('/api/amenities/{propertyType}/edit', [AmenitiesController::class, 'edit']);
    Route::put('/api/amenities/{propertyType}/edit', [AmenitiesController::class, 'update']);
    Route::delete('/api/amenities/{propertytype}', [AmenitiesController::class, 'destroy']);
    Route::post('/api/amenities/upload-image', [AmenitiesController::class, 'uploadImage']);

    Route::get('/api/settings', [SettingController::class, 'index']);
    Route::post('/api/settings', [SettingController::class, 'update']);

    Route::get('/api/profile', [ProfileController::class, 'index']);
    Route::put('/api/profile', [ProfileController::class, 'update']);
    Route::post('/api/upload-profile-image', [ProfileController::class, 'uploadImage']);
    Route::post('/api/change-user-password', [ProfileController::class, 'changePassword']);
});

Route::get('{view}', ApplicationController::class)->where('view', '(.*)')->middleware('auth');
