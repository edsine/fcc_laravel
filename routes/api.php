<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MailProxyController;

Route::post('/queue-mail', [MailProxyController::class, 'send']);
Route::post('/queue-mail-mda', [MailProxyController::class, 'queueMailForMDA']);