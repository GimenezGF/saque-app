<?php

use App\Http\Controllers\SaqueController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/gerar-qrcode', [SaqueController::class, 'gerarSaque']);
Route::get('/get-extrato', [SaqueController::class, 'getExtrato']);
