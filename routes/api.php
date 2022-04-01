<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\Detail_transaksiController;


Route::post('login',[AuthController::class,'login']);

Route::group(['middleware'=> ['jwt.verify:admin,kasir,owner']], function() {
    route::get('login/check', [AuthController::class, 'logincheck']);
    route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/user', [AuthController::class, 'store']);
    Route::get('dashboard', [DashboardController::class, 'index']);
});

Route::group(['middleware' => ['jwt.verify:admin']], function() {

    //PAKET
    Route::get('paket', [PaketController::class, 'getAll']);
    Route::get('paket/{id}', [PaketController::class, 'getById']);
    Route::post('paket', [PaketController::class, 'store']);
    Route::put('paket/{id}', [PaketController::class, 'update']);
    Route::delete('paket/{id}', [PaketController::class, 'delete']);

    //OUTLET
    Route::get('outlet', [OutletController::class, 'getAll']);
    Route::get('outlet/{id}', [OutletController::class, 'getById']);
    Route::post('outlet', [OutletController::class, 'store']);
    Route::put('outlet/{id}', [OutletController::class, 'update']);
    Route::delete('outlet/{id}', [OutletController::class, 'delete']);

    //USER
    Route::post('user', [UserController::class, 'store']);
    Route::get('user', [UserController::class, 'getAll']);
    Route::get('user/{id}', [UserController::class, 'getById']);
    Route::put('user/{id}', [UserController::class, 'update']);
    Route::delete('user/{id}', [UserController::class, 'delete']);
});

Route::group(['middleware' => ['jwt.verify:admin,kasir']], function() {

    //MEMBER
    route::post('member', [MemberController::class, 'store']);
    route::get('member', [MemberController::class, 'getAll']);
    route::get('member/{id}', [MemberController::class, 'getById']);
    route::put('member/{id}', [MemberController::class, 'update']);
    route::delete('member/{id}', [MemberController::class, 'delete']);

    //TRANSAKSI
    Route::post('transaksi', [TransaksiController::class, 'store']);
    Route::get('transaksi/{id}', [TransaksiController::class, 'getById']);
    Route::get('transaksi', [TransaksiController::class, 'getAll']);
    Route::put('transaksi/{id}', [TransaksiController::class, 'update']);

    //DETAIL TRANSAKSI
    Route::post('detail_transaksi', [Detail_transaksiController::class, 'store']);
    Route::get('detail_transaksi/detail/{id}', [Detail_transaksiController::class, 'getById']);
    Route::post('detail_transaksi/status/{id}', [TransaksiController::class, 'changeStatus']);
    Route::post('detail_transaksi/bayar/{id}', [TransaksiController::class, 'bayar']);
    Route::get('detail_transaksi/total/{id}', [Detail_transaksiController::class, 'getTotal']);
});

Route::group(['middleware' => ['jwt.verify:owner']], function() {
});