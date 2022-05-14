<?php

use App\Http\Controllers\WhatsMonsterController;
use Illuminate\Support\Facades\Route;

Route::any('whatsmonster-callback', [WhatsMonsterController::class, 'callback']);
Route::any('new-account', [WhatsMonsterController::class, 'newAccount']);
