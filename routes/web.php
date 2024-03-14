<?php

use App\Http\Controllers\AdminGCListsController;
use App\Http\Controllers\AdminGCListsStoreController;
use App\Http\Controllers\AdminQrCreationsController;
use App\Http\Controllers\CustomerRegistrationController;
use App\Http\Controllers\AdminEmailTestingsController;
use Illuminate\Support\Facades\Route;
use App\EmailTesting;
use App\Http\Controllers\AdminGCListsHistoryController;

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

Route::get('/', function () {
    return redirect('/admin/login');
});

// Scan QR
Route::get('admin/g_c_lists/scan_qr', [AdminGCListsController::class, 'getScanQR'])->name('scan_qr');
// Input BDO code
Route::post('admin/g_c_lists/scan_qr/get_bdo', [AdminGCListsController::class, 'getBdo'])->name('get_bdo');

// Upload File
Route::get('admin/qr_creations/edit/{id}', [AdminQrCreationsController::class, 'getEdit'])->name('qr_creations_edit');
Route::get('admin/qr_creations/upload_gc_list', [AdminQrCreationsController::class, 'uploadGCList'])->name('upload_file');
Route::post('admin/qr_creations/upload_gc_list/excel', [AdminQrCreationsController::class, 'uploadGCListPost'])->name('import_file');
// Export File
Route::get('admin/g_c_lists/upload_gc_list/dowload_template', [AdminQrCreationsController::class, 'exportGCListTemplate'])->name('export_file');
// Get Edit
Route::get('admin/g_c_lists/edit/{id}', [AdminGCListsController::class, 'getEdit'])->name('edit_redeem_code');
// Redeeming Code
Route::post('admin/g_c_list/edit/redeem_code', [AdminGCListsController::class, 'redeemCode'])->name('redeem_code');
Route::post('admin/g_c_list/edit/save_invoice_number', [AdminGCListsController::class, 'inputInvoice'])->name('input_invoice');
// Redemption Period Ended
Route::post('admin/g_c_list/edit/close_transaction', [AdminGCListsController::class, 'closeTransaction'])->name('close_transaction');
// Add Campaign
Route::post('admin/qr_creations/add/campaign', [AdminQrCreationsController::class, 'addCampaign'])->name('add_campaign');
Route::post('admin/qr_creations/add/campaignIhc', [AdminQrCreationsController::class, 'addCampaignIhc'])->name('add_campaign_ihc');
// Save Campaign
Route::post('admin/qr_creations/edit/saveCampaignIhc', [AdminQrCreationsController::class, 'saveCampaignIhc'])->name('save_campaign_ihc');
// Campaign Approval
Route::post('admin/qr_creations/edit/campaign-approval', [AdminQrCreationsController::class, 'campaignApproval'])->name('campaign_approval');
// getAddIhc
Route::get('admin/qr_creations/getAddIhc/', [AdminQrCreationsController::class, 'getAddIhc']);
// Stores
Route::post('admin/qr_creations/getStores', [AdminQrCreationsController::class, 'Stores'])->name('getStores');
// Email
Route::get('admin/g_c_lists/email', function(){
    return view('/redeem_qr.sendemail');
});

// Customer Information
Route::get('/qr_link/{store_concept}/{store_branch}/{qr_reference_number}', [CustomerRegistrationController::class, 'qrLink']);
Route::get('/customer_registration/beyond_the_box/{store_branch}/{qr_reference_number}', [CustomerRegistrationController::class, 'index']);
Route::get('/customer_registration/digital_walker/{store_branch}/{qr_reference_number}', [CustomerRegistrationController::class, 'index']);
Route::get('/customer_registration/dw_and_btb/{store_branch}/{qr_reference_number}', [CustomerRegistrationController::class, 'index']);
Route::get('/customer_registration/open_source/{store_branch}/{qr_reference_number}', [CustomerRegistrationController::class, 'index']);
Route::get('/customer_registration/suggest-existing-customer', [CustomerRegistrationController::class, 'suggestExistingCustomer'])->name('suggest_existing_customer');
Route::post('/customer_registration/view-existing-customer', [CustomerRegistrationController::class, 'viewCustomerInfo'])->name('viewCustomerInfo');
// Add Customer
Route::post('/customer_registration/store', [CustomerRegistrationController::class, 'store'])->name('store_ui');
// Peding Invoice
Route::post('admin/store_devps/edit/pendingInvoice', [AdminGCListsStoreController::class, 'pendingInvoice'])->name('pending_invoice');
// OIC Approval
Route::post('admin/store_devps/edit/pendingOIC', [AdminGCListsStoreController::class, 'pendingOIC'])->name('pending_oic');
// Void Request
Route::get('admin/store_devps/void/{id}', [AdminGCListsStoreController::class, 'voidRequest'])->name('egc_void');
// EGC Value
Route::get('admin/store_devps/egc_value', [AdminGCListsStoreController::class, 'egcValue'])->name('egc_value');
// createEGC Store
Route::post('admin/store_devps/create_egc', [AdminGCListsStoreController::class, 'createEGC'])->name('create_egc');
// First step customer information and otp
Route::post('customer_registration/submit-otp', [CustomerRegistrationController::class, 'store'])->name('send_otp');
// Second step verify OTP
Route::post('customer_registration/verify-otp', [CustomerRegistrationController::class, 'verifyOtp'])->name('verify_otp');
// Third step send EGC
Route::post('customer_registration/send-egc', [CustomerRegistrationController::class, 'sendEgc'])->name('send_egc');


// Email Template
Route::get(config('crudbooster.ADMIN_PATH').'email_testings/add-template', [AdminEmailTestingsController::class, 'getAddTemplate'])->name('add-template');
Route::post(config('crudbooster.ADMIN_PATH').'delete-images', [AdminEmailTestingsController::class, 'deleteImages'])->name('delete-images');
Route::post(config('crudbooster.ADMIN_PATH').'/selectedHeader',[AdminEmailTestingsController::class, 'selectedHeader'])->name('selected-header');
//Send Email testing
Route::post(config('crudbooster.ADMIN_PATH').'send-email-testing', [AdminEmailTestingsController::class, 'sendEmailTesting'])->name('send-email-testing');

Route::post('/admin/qr_creations/email_testing', [AdminQrCreationsController::class, 'EmailTesting'])->name('emailtesting');

// Yajra Table
Route::get('gc_list_data', [AdminGCListsHistoryController::class, 'getGCList'])->name('get_gc_list');
// GCList Export
Route::get('admin/redemption_history/gclist_export', [AdminGCListsHistoryController::class, 'export'])->name('gclist_export');
// Campaign GC Export
Route::get('admin/redemption_history/campaign_export', [AdminGCListsHistoryController::class, 'exportCGc'])->name('campaign_gclist_export');
// Store GC Export
Route::get('admin/redemption_history/store_export', [AdminGCListsHistoryController::class, 'exportSGc'])->name('store_gclist_export');

Route::get('1245', function(){
    return view('email_testing.otp-email');
});

Route::get('send-email-test', function(){
    return view('email_testing.sendemail-t');
});