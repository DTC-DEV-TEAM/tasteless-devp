<?php

use App\Http\Controllers\AdminGCListsController;
use App\Http\Controllers\AdminGCListsStoreController;
use App\Http\Controllers\AdminQrCreationsController;
use App\Http\Controllers\CustomerRegistrationController;
use App\Http\Controllers\AdminEmailTestingsController;
use Illuminate\Support\Facades\Route;
use App\EmailTesting;

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
Route::get('/customer_registration/beyond_the_box', [CustomerRegistrationController::class, 'index']);
Route::get('/customer_registration/digital_walker', [CustomerRegistrationController::class, 'index']);
Route::get('/customer_registration/dw_and_btb', [CustomerRegistrationController::class, 'index']);
Route::get('/customer_registration/open_source', [CustomerRegistrationController::class, 'index']);
Route::get('/customer_registration/suggest-existing-customer', [CustomerRegistrationController::class, 'suggestExistingCustomer'])->name('suggest_existing_customer');
Route::post('/customer_registration/view-existing-customer', [CustomerRegistrationController::class, 'viewCustomerInfo'])->name('viewCustomerInfo');
// Add Customer
Route::post('/customer_registration/store', [CustomerRegistrationController::class, 'store'])->name('store_ui');
// Peding Invoice
Route::post('admin/store/edit/pendingInvoice', [AdminGCListsStoreController::class, 'pendingInvoice'])->name('pending_invoice');
// OIC Approval
Route::post('admin/store/edit/pendingOIC', [AdminGCListsStoreController::class, 'pendingOIC'])->name('pending_oic');

// Email Template
Route::get(config('crudbooster.ADMIN_PATH').'email_testings/add-template', [AdminEmailTestingsController::class, 'getAddTemplate'])->name('add-template');
Route::post(config('crudbooster.ADMIN_PATH').'delete-images', [AdminEmailTestingsController::class, 'deleteImages'])->name('delete-images');
Route::post(config('crudbooster.ADMIN_PATH').'/selectedHeader',[AdminEmailTestingsController::class, 'selectedHeader'])->name('selected-header');
//Send Email testing
Route::post(config('crudbooster.ADMIN_PATH').'send-email-testing', [AdminEmailTestingsController::class, 'sendEmailTesting'])->name('send-email-testing');

Route::post('/admin/qr_creations/email_testing', [AdminQrCreationsController::class, 'EmailTesting'])->name('emailtesting');
// Route::get('/get-sales/{receipt}/{company}/{store}/{voucher}', function($receipt, $company, $store, $voucher){
    
//     $data['pos_sale'] = DB::connection('mysql_tunnel')
//     ->table('pos_sale')
//     ->where('fcompanyid',$company)
//     ->where('fofficeid',$store)
//     ->where('fdocument_no',$receipt)
//     ->first();

//     $data['pos_sale_discount'] = DB::connection('mysql_tunnel')
//             ->table('pos_sale_product_discount')
//             ->where('fcompanyid',$company)
//             ->where('frecno',$data['pos_sale']->frecno)
//             ->first();

//     $data['pos_sale_discount_detail'] = DB::connection('mysql_tunnel')
//         ->table('mst_discount')
//         ->where('fdiscountid',$data['pos_sale_discount']->fdiscountid)
//         ->where('fcompanyid',$company)
//         ->first();

//     return $data;
// });
