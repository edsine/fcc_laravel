<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MailProxyController;

Route::post('/queue-mail', [MailProxyController::class, 'send']);
Route::post('/queue-mail-mda', [MailProxyController::class, 'queueMailForMDA']);
Route::post('/queue-sms-mda', [MailProxyController::class, 'queueSMSForMDA']);
Route::post('/get-my-inbox', [MailProxyController::class, 'getMyInbox'])->name('mailproxy.inbox');
Route::post('/get-message-details', [MailProxyController::class, 'getMessageDetails'])->name('mailproxy.message');
