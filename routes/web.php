<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\CustomLoginController;

use App\Http\Controllers\NotificationInboxController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\MyProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Notification\AllFederalMDANotificationController;
use App\Http\Controllers\Notification\SendSubmissionNotificationController;




Route::middleware('auth')->group(function () {

Route::get('/notification/send_submission_demand_notice', [SendSubmissionNotificationController::class, 'create'])
    ->name('send_submission_demand_notice.create');

Route::post('/notification/send_submission_demand_notice', [SendSubmissionNotificationController::class, 'send'])
    ->name('send_submission_demand_notice');

Route::get('/notification/all_federal_mda', [AllFederalMDANotificationController::class, 'create'])
    ->name('notification_for_all_federal_mda.create');

Route::post('/notification/all_federal_mda/send', [AllFederalMDANotificationController::class, 'send'])
    ->name('notification_for_all_federal_mda.send');

    Route::get('/notification/open', [NotificationController::class, 'create'])->name('open_notification');
Route::post('/notification/send', [NotificationController::class, 'send'])->name('send_notification');

Route::resource('settings', SettingsController::class); // CRUD for settings

    Route::get('profile', [MyProfileController::class, 'show'])->name('profile.show');
    Route::get('profile/edit', [MyProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [MyProfileController::class, 'update'])->name('profile.update');

    // Inbox route
    Route::get('/secure_area/notification/notification_inbox', [NotificationInboxController::class, 'showInbox'])->name('notification_inbox');

    // Show specific notification details
Route::get('/notification/show/{guid}', [NotificationInboxController::class, 'showNotification'])->name('notification_show');
});


Route::get('login', [CustomLoginController::class, 'showLoginForm'])->name('login'); // Show login form
Route::post('custom-login', [CustomLoginController::class, 'login'])->name('custom.login'); // Handle login request

Route::post('logout', [CustomLoginController::class, 'logout'])->name('logout'); // Handle logout



Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    //Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    
    Route::get('/user-profile-edit', [MyProfileController::class, 'edit'])->name('user.profile.edit');
    Route::get('/mis_head_nominal_roll_pending_approval_list', [MyProfileController::class, 'edit'])->name('mis_head_nominal_roll_pending_approval_list');
    Route::get('/submission_status', [MyProfileController::class, 'edit'])->name('submission_status');
    Route::get('/mis_head_submission_permission_request_pending_approval_list', [MyProfileController::class, 'edit'])->name('mis_head_submission_permission_request_pending_approval_list');
    Route::get('/mis_head_fix_submission_issues', [MyProfileController::class, 'edit'])->name('mis_head_fix_submission_issues');
    Route::get('/user-profile-edit', [MyProfileController::class, 'edit'])->name('user.profile.edit');
    Route::get('/user-profile-edit', [MyProfileController::class, 'edit'])->name('user.profile.edit');



    //Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    //Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
