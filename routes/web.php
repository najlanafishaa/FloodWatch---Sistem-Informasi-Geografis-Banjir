<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FloodController;
use App\Http\Controllers\GeojsonController;

// Public Routes
Route::get('/', [PublicController::class, 'landing'])->name('landing');
Route::get('/map', [PublicController::class, 'map'])->name('map');
Route::get('/api/floods', [PublicController::class, 'apiFloods'])->name('api.floods');
Route::get('/api/geojson', [PublicController::class, 'apiGeojson'])->name('api.geojson');
Route::get('/api/dataset-stats', [PublicController::class, 'apiDatasetStats'])->name('api.dataset-stats');
Route::get('/api/dataset-exists', [PublicController::class, 'apiDatasetExists'])->name('api.dataset-exists');
Route::get('/api/bps-geojson', [PublicController::class, 'apiBpsGeojson'])->name('api.bps-geojson');


// Admin Auth Routes (separate URL: /admin/login)
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    
    // Flood CRUD
    Route::resource('floods', FloodController::class);
    
    // GeoJSON Layers
    Route::get('/geojson', [GeojsonController::class, 'index'])->name('geojson.index');
    Route::post('/geojson/upload', [GeojsonController::class, 'upload'])->name('geojson.upload');
    Route::post('/geojson/{geojsonLayer}/toggle', [GeojsonController::class, 'toggleActive'])->name('geojson.toggle');
    Route::delete('/geojson/{geojsonLayer}', [GeojsonController::class, 'destroy'])->name('geojson.destroy');
});
