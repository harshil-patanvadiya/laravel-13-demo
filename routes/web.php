<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::view('/login', 'auth.login')->name('login');
Route::view('/admin/categories', 'admin.categories.index')->name('admin.categories.index');
Route::view('/admin/products', 'admin.products.index')->name('admin.products.index');
Route::view('/admin/products/{id}', 'admin.products.show')->name('admin.products.show');
