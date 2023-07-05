<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\BlockController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashController;
use App\Http\Controllers\RawDataController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\CrmController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PhoneController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecordingController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
## auth
Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/authenticate', [LoginController::class, 'authenticate'])->name('auth.authenticate');


Route::group(['middleware' => 'auth'], function () {
    ## dashboard
    Route::get('/dashboard', [DashController::class, 'index'])->name('dashboard');
    Route::get('/download-notifications', [DashController::class, 'downloadNotifications']);
    Route::get('/download-unseen-count', [DashController::class, 'downloadUnseenCount']);

    ## realtime agent
    Route::get('/realtime-agent', [DashController::class, 'realTimeAgent'])->name('dashboard.realtimeAgent');
    Route::get('/realtime-agent-datatable', [DashController::class, 'realtimeAgentDatatable'])->name('dashboard.realtimeAgent.datatable');

    ## queue calls
    Route::get('/queue-call', [DashController::class, 'queueCall'])->name('dashboard.queueCall');
    Route::get('/queue-datatable', [DashController::class, 'queueDatatable'])->name('dashboard.queue.datatable');

    Route::get('/charts', [DashController::class, 'charts'])->name('dashboard.charts');


    ## crm
    Route::get('/crm', [CrmController::class, 'crm'])->name('crm.crm');
    Route::get('/crm-datatable', [CrmController::class, 'crmDatatable'])->name('crm.crm.datatable');
    Route::get('/crm-download/{start_date}/{end_date}', [CrmController::class, 'crmDataDownload'])->name('crm.crm.download');

    ## daily report
    Route::get('/daily-report', [AgentController::class, 'dailyReport'])->name('agentData.daily');
    Route::get('/daily-datatable', [AgentController::class, 'dailyDatatable'])->name('agentData.daily.datatable');
    Route::get('/daily-download', [AgentController::class, 'dailyReportDownload'])->name('agentData.daily.download');

    ## aux report
    Route::get('/aux-report', [AgentController::class, 'auxReport'])->name('agentData.aux');
    Route::get('/aux-datatable', [AgentController::class, 'auxDatatable'])->name('agentData.aux.datatable');
    Route::get('/aux-download/{start_date}/{end_date}', [AgentController::class, 'auxReportDownload'])->name('agentData.aux.download');

    ## auth report
    Route::get('/auth-report', [AgentController::class, 'authReport'])->name('agentData.auth');
    Route::get('/auth-datatable', [AgentController::class, 'authDatatable'])->name('agentData.auth.datatable');
    Route::get('/auth-download', [AgentController::class, 'authReportDownload'])->name('agentData.auth.download');

    ## inbound
    Route::get('/inbound', [RawDataController::class, 'inbound'])->name('rawData.inbound');
    Route::get('/inbound-datatable', [RawDataController::class, 'inboundDatatable'])->name('rawData.inbound.datatable');
    Route::get('/inbound-download/{start_date}/{end_date}', [RawDataController::class, 'inboundDataDownload'])->name('rawData.inbound.download');

    ## outbound
    Route::get('/outbound', [RawDataController::class, 'outbound'])->name('rawData.outbound');
    Route::get('/outbound-datatable', [RawDataController::class, 'outboundDatatable'])->name('rawData.outbound.datatable');
    Route::get('/outbound-download/{start_date}/{end_date}', [RawDataController::class, 'outboundDataDownload'])->name('rawData.outbound.download');

    ## drop calls
    Route::get('/drop', [RawDataController::class, 'drop'])->name('rawData.drop');
    Route::get('/drop-datatable', [RawDataController::class, 'dropDatatable'])->name('rawData.drop.datatable');
    Route::get('/drop-download/{start_date}/{end_date}', [RawDataController::class, 'dropDataDownload'])->name('rawData.drop.download');

    ## recording
    Route::get('/recording', [RecordingController::class, 'recording'])->name('recording.recording');
    Route::get('/recording-datatable', [RecordingController::class, 'recordingDatatable'])->name('recording.recording.datatable');

    ## recording QC
    Route::get('/recording-qc', [RecordingController::class, 'recordingQC'])->name('recording.qc');

    ## users
    Route::get('/user', [UserController::class, 'user'])->name('system.user');
    Route::post('/user-store', [UserController::class, 'userStore'])->name('system.user.store');
    Route::get('/user-status-change/{id}', [UserController::class, 'userStatusChange']);
    Route::get('/user-datatable', [UserController::class, 'userDatatable'])->name('system.user.datatable');

    ## phones
    Route::get('/phone', [PhoneController::class, 'phone'])->name('system.phone');
    Route::post('/phone-store', [PhoneController::class, 'phoneStore'])->name('system.phone.store');
    Route::delete('/phone-destroy/{extension}', [PhoneController::class, 'phoneDestroy'])->name('system.phone.destroy');
    Route::get('/phone-datatable', [PhoneController::class, 'phoneDatatable'])->name('system.phone.datatable');

    ## admins
    Route::get('/administrator', [AdminController::class, 'admin'])->name('system.administrator');
    Route::post('/administrator-store', [AdminController::class, 'administratorStore'])->name('system.administrator.store');
    Route::get('/administrator-datatable', [AdminController::class, 'adminDatatable'])->name('system.administrator.datatable');
    Route::get('/administrator-status-change/{id}', [AdminController::class, 'administratorStatusChange']);

    ## blocks
    Route::get('/block-list', [BlockController::class, 'block'])->name('system.block');
    Route::post('/block-list-store', [BlockController::class, 'blockStore'])->name('system.block.store');
    Route::get('/block-datatable', [BlockController::class, 'blockDatatable'])->name('system.block.datatable');
    Route::delete('/block-list-destroy/{phone_number}', [BlockController::class, 'blockDestroy']);

    ## profile
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::post('/profile-update', [ProfileController::class, 'profileUpdate'])->name('profile.update');
    Route::post('/profile-password-update', [ProfileController::class, 'profilePasswordUpdate'])->name('profile.password.update');

    ## common
    Route::get('/batch/{id}', [CommonController::class, 'batch'])->name('rawData.batch');
    Route::get('/delete', [CommonController::class, 'delete'])->name('rawData.delete');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});


## Clear application cache:
Route::get('/clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('route:cache');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    return 'Application cache has been cleared';
});
