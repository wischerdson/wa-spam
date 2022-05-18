<?php

use App\Http\Controllers\WhatsMonsterController;
use Illuminate\Support\Facades\Route;

Route::any('whatsmonster-callback', [WhatsMonsterController::class, 'callback']);
Route::post('new-account', [WhatsMonsterController::class, 'newAccount']);
Route::get('accounts', [WhatsMonsterController::class, 'index']);
Route::get('bot-reply-message', [WhatsMonsterController::class, 'getReplyMessage']);
Route::post('update-reply-message', [WhatsMonsterController::class, 'setReplyMessage']);
Route::post('upload-message-file', [WhatsMonsterController::class, 'uploadMessageFile']);
