<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
// Route untuk halaman leaflet/peta lahan
Route::get('/peta-lahan', function () {
    return view('leaflet'); // panggil file leaflet.blade.php
})->name('peta.lahan');