<?php

use App\Http\Controllers\WhatsmonsterController;
use Illuminate\Support\Facades\Route;

Route::any('whatsmonster-callback', [WhatsmonsterController::class, 'callback']);
Route::post('new-account', [WhatsmonsterController::class, 'newAccount']);
Route::get('accounts', [WhatsmonsterController::class, 'index']);
Route::get('bot-reply-message', [WhatsmonsterController::class, 'getReplyMessage']);
Route::post('update-reply-message', [WhatsmonsterController::class, 'setReplyMessage']);
Route::post('upload-message-file', [WhatsmonsterController::class, 'uploadMessageFile']);
