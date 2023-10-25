<?php

use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\PropertiesController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\PropertyTypesController;
use App\Http\Controllers\Admin\AmenitiesController;
use App\Http\Controllers\Admin\LocationsController;
use App\Http\Controllers\Admin\FeaturesController;
use App\Http\Controllers\Admin\ServicesController;
use App\Http\Controllers\Admin\LineItemsController;
use App\Http\Controllers\Admin\AppointmentStatusController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DashboardStatController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ApplicationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MediaController;

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
Route::get('/wp', function () {
    return 'TestTestTest';
});

// Route::get('/admin/dashboard', function () {
//     return view('dashboard');
// });

// /api/service/{service_id}/media/all
// /api/service/{service_id}/media/featured-update/{media_id}
// /api/service/{service_id}/media/add-remove/{media_id}

Route::prefix('/api/service/{service_id}/media')->name('api.service.media.')->group(function () {
    Route::get('/all', [ServicesController::class, 'getAllMedia'])->name('index');
    Route::get('/featured-update/{media_id}', [ServicesController::class, 'featuredUpdate'])->name('featured-update');
    Route::get('/add-remove/{media_id}', [ServicesController::class, 'addOrRemoveMedia'])->name('add-remove');
});


Route::prefix('/api/location/{location_id}/media')->name('api.location.media.')->group(function () {
    Route::get('/all', [LocationsController::class, 'getAllMedia'])->name('index');
    Route::get('/featured-update/{media_id}', [LocationsController::class, 'featuredUpdate'])->name('featured-update');
    Route::get('/add-remove/{media_id}', [LocationsController::class, 'addOrRemoveMedia'])->name('add-remove');
});


Route::prefix('/api/lineitem/{line_item_id}/media')->name('api.lineitem.media.')->group(function () {
    Route::get('/all', [LineitemsController::class, 'getAllMedia'])->name('index');
    Route::get('/featured-update/{media_id}', [LineitemsController::class, 'featuredUpdate'])->name('featured-update');
    Route::get('/add-remove/{media_id}', [LineitemsController::class, 'addOrRemoveMedia'])->name('add-remove');
});


Route::prefix('/api/feature/{feature_id}/media')->name('api.feature.media.')->group(function () {
    Route::get('/all', [FeaturesController::class, 'getAllMedia'])->name('index');
    Route::get('/featured-update/{media_id}', [FeaturesController::class, 'featuredUpdate'])->name('featured-update');
    Route::get('/add-remove/{media_id}', [FeaturesController::class, 'addOrRemoveMedia'])->name('add-remove');
});


Route::prefix('/api/amenity/{amenity_id}/media')->name('api.amenity.media.')->group(function () {
    Route::get('/all', [AmenitiesController::class, 'getAllMedia'])->name('index');
    Route::get('/featured-update/{media_id}', [AmenitiesController::class, 'featuredUpdate'])->name('featured-update');
    Route::get('/add-remove/{media_id}', [AmenitiesController::class, 'addOrRemoveMedia'])->name('add-remove');
});

Route::prefix('/api/propertytype/{propertytype_id}/media')->name('api.propertytype.media.')->group(function () {
    Route::get('/all', [PropertyTypesController::class, 'getAllMedia'])->name('index');
    Route::get('/featured-update/{media_id}', [PropertyTypesController::class, 'featuredUpdate'])->name('featured-update');
    Route::get('/add-remove/{media_id}', [PropertyTypesController::class, 'addOrRemoveMedia'])->name('add-remove');
});

Route::prefix('/api/property/{property_id}/media')->name('api.property.media.')->group(function () {
    Route::get('/all', [PropertiesController::class, 'getAllMedia'])->name('index');
    Route::get('/featured-update/{media_id}', [PropertiesController::class, 'featuredUpdate'])->name('featured-update');
    Route::get('/add-remove/{media_id}', [PropertiesController::class, 'addOrRemoveMedia'])->name('add-remove');
});


Route::get('/api/media', [MediaController::class, 'index'])->name('media.index');
Route::get('/media/{id}/edit', [MediaController::class, 'edit'])->name('media.edit');
Route::post('/media/upload', [MediaController::class, 'upload'])->name('media.upload');
Route::delete('/api/media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
Route::post('/media-upload', [MediaController::class, 'upload']);

// Route::post('/api/upload-file', [ApplicationController::class, 'uploadFile']);





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
    Route::get('/api/amenities/{amenities}/edit', [AmenitiesController::class, 'edit']);
    Route::put('/api/amenities/{amenities}/edit', [AmenitiesController::class, 'update']);
    Route::delete('/api/amenities/{amenities}', [AmenitiesController::class, 'destroy']);
    Route::post('/api/amenities/upload-image', [AmenitiesController::class, 'uploadImage']);

    Route::get('/api/features', [FeaturesController::class, 'index']);
    Route::post('/api/features/create', [FeaturesController::class, 'store']);
    Route::get('/api/features/withcount', [FeaturesController::class, 'getTypesWithCount']);
    Route::get('/api/features/{id}/edit', [FeaturesController::class, 'edit']);
    Route::put('/api/features/{features}/edit', [FeaturesController::class, 'update']);
    Route::delete('/api/features/{features}', [FeaturesController::class, 'destroy']);
    Route::post('/api/features/upload-image', [FeaturesController::class, 'uploadImage']);

    Route::get('/api/services', [ServicesController::class, 'index']);
    Route::post('/api/services/create', [ServicesController::class, 'store']);
    Route::get('/api/services/withcount', [ServicesController::class, 'getTypesWithCount']);
    Route::get('/api/services/{id}/edit', [ServicesController::class, 'edit']);
    Route::put('/api/services/{services}/edit', [ServicesController::class, 'update']);
    Route::delete('/api/services/{services}', [ServicesController::class, 'destroy']);
    Route::post('/api/services/upload-image', [ServicesController::class, 'uploadImage']);

    Route::get('/api/lineitems', [LineitemsController::class, 'index']);
    Route::post('/api/lineitems/create', [LineitemsController::class, 'store']);
    Route::get('/api/lineitems/withcount', [LineitemsController::class, 'getTypesWithCount']);
    Route::get('/api/lineitems/{id}/edit', [LineitemsController::class, 'edit']);
    Route::put('/api/lineitems/{lineitems}/edit', [LineitemsController::class, 'update']);
    Route::delete('/api/lineitems/{lineitems}', [LineitemsController::class, 'destroy']);
    Route::post('/api/lineitems/upload-image', [LineitemsController::class, 'uploadImage']);

    Route::get('/api/locations', [LocationsController::class, 'index']);
    Route::post('/api/locations/create', [LocationsController::class, 'store']);
    Route::get('/api/locations/withcount', [LocationsController::class, 'getTypesWithCount']);
    Route::get('/api/locations/{id}/edit', [LocationsController::class, 'edit']);
    Route::put('/api/locations/{locations}/edit', [LocationsController::class, 'update']);
    Route::delete('/api/locations/{locations}', [LocationsController::class, 'destroy']);
    Route::post('/api/locations/upload-image', [LocationsController::class, 'uploadImage']);


    Route::get('/api/settings', [SettingController::class, 'index']);
    Route::post('/api/settings', [SettingController::class, 'update']);

    Route::get('/api/profile', [ProfileController::class, 'index']);
    Route::put('/api/profile', [ProfileController::class, 'update']);
    Route::post('/api/upload-profile-image', [ProfileController::class, 'uploadImage']);
    Route::post('/api/change-user-password', [ProfileController::class, 'changePassword']);



    Route::get('/api/bookings', [BookingController::class, 'index']);
    Route::post('/api/booking/create', [BookingController::class, 'store']);
    Route::get('/api/booking/{booking}/edit', [BookingController::class, 'edit']);
    Route::put('/api/booking/{booking}/edit', [BookingController::class, 'update']);
    Route::delete('/api/booking/{booking}', [BookingController::class, 'destroy']);


});

Route::get('{view}', ApplicationController::class)->where('view', '(.*)')->middleware('auth');
