<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CardfiveController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------

| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/wallet', function () {
    return view('wallet.index');
})->name('wallet.index');

Route::post('/game_setting', [AdminController::class, 'game_setting'])->name('game_setting');
Route::get('/fetch', [CardfiveController::class, 'fetch_data'])->name('fetch_data');
Route::post('/admin_prediction', [CardfiveController::class, 'admin_prediction'])->name('admin_prediction');
Route::get('/',[AdminController::class,'login_page'])->name('login_page');
Route::post('/login',[AdminController::class,'login'])->name('auth.login');
Route::get('/dashboard',[AdminController::class,'dashboard'])->name('admin.dashboard');
Route::any('/result_history',[CardfiveController::class, 'result_history'])->name('result_history');
Route::get('/logout',[AdminController::class,'logout'])->name('logout');
Route::get('/12card5',[AdminController::class,'cardfive'])->name('prediction.12card5');
Route::any('/bethistory',[CardfiveController::class,'bethistory'])->name('admin.bethistory');
Route::any('/reset_bethistory',[CardfiveController::class,'reset_bethistory'])->name('reset_bethistory');
Route::get('/password',[AdminController::class,'password'])->name('admin.password');
Route::post('/update_password',[AdminController::class,'update_password'])->name('update_password');
Route::get('/wallet',[AdminController::class,'wallet'])->name('admin.addmoney');
Route::post('/add_money',[AdminController::class,'add_money'])->name('add_money');

// 26/09/2024 


Route::get('/createrole', [AdminController::class, 'createRole'])->name('createRole');
Route::post('/get-terminals', [AdminController::class, 'getTerminalsByRole'])->name('getTerminals');
Route::post('/store', [AdminController::class, 'store'])->name('store');
Route::post('/editpass/{id}', [AdminController::class, 'editpass'])->name('admins.editpass');


Route::post('/receiveamount/{id}', [AdminController::class, 'receiveamount'])->name('admins.receiveamount');



route::get('{id}/edit', [AdminController::class, 'editRole']);
Route::any('/stokist', [AdminController::class, 'stokistlist'])->name('stokistlist');
Route::put('/admins/{id}/status', [AdminController::class, 'updateStatus'])->name('admins.updateStatus');
Route::put('/admins/{id}/user', [AdminController::class, 'update'])->name('admins.userupdate');
Route::delete('/admins/{id}', [AdminController::class, 'destroy'])->name('admins.destroy');
Route::post('/wallet/{id}', [AdminController::class, 'addwallet'])->name('admins.addwallet');
Route::get('/transaction/history/{id}', [AdminController::class, 'history'])->name('transaction.history');


Route::get('/getSubstockists/{stockistId}', [AdminController::class, 'getSubstockists']);
Route::get('/getUsers/{substockistId}', [AdminController::class, 'getUsers']);
Route::get('/getUserData/{userId}', [AdminController::class, 'getUserData']);
Route::get('/getTableData', [AdminController::class, 'getTableData']);
// web.php
Route::get('/getUserByTerminal/{terminalId}', [AdminController::class, 'getUserByTerminal']);
Route::any('/prediction_history',[CardfiveController::class, 'prediction_history'])->name('prediction_history');
Route::post('/delete_prediction',[CardfiveController::class, 'delete_prediction'])->name('delete_prediction');

Route::get('/create_role',[AdminController::class, 'create_role'])->name('create_role');
Route::post('/create_role_code',[AdminController::class, 'create_role_code'])->name('create_role_code');
Route::post('/add_role',[AdminController::class, 'add_role'])->name('add_role');


Route::get('/userreject/',[AdminController::class, 'userreject'])->name('user.reject');



Route::get('/userpending/{status}', [AdminController::class, 'userpending'])->name('user.pending');



Route::put('/admins/{id}/update-status', [AdminController::class, 'updateStatuss'])->name('admins.updateStatus');



Route::get('/get-stockist-subordinates/{stockist_terminal_id}', [CardfiveController::class, 'getStockistSubordinates']);
Route::get('/get-substockist-users/{substockist_terminal_id}', [CardfiveController::class, 'getSubstockistUsers']);










