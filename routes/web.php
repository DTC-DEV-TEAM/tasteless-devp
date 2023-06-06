<?php

use App\Http\Controllers\AdminGCListsController;
use App\Http\Controllers\AdminQrCreationsController;
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
// Email Testing

// Email
Route::get('admin/g_c_lists/email', function(){
    return view('/redeem_qr.sendemail');
});

// Redeemed Email
Route::get('/redeemed_email', function() {
    $email = EmailTesting::get()->first();
    $name = 'Patrick';
    $gc_description = '50% sale';
    $gc_value = '500php';
    $url = url('admin/g_c_lists/edit/' . $id.'?value='.$qr_reference_number);
    $qrCodeApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($url);
    $qr_code = "<a href='$qrCodeApiUrl' download='qr_code.png'> <img src='$qrCodeApiUrl' alt='QR Code'> </a>";
    
    $htmlEmail = str_replace(
        ['[name]', '[campaign_id]', '[gc_description]', '[qr_code]'],
        [$name, $gc_description, $gc_value, $qr_code ],
        $email->html_email
    );

    return view('redeem_qr.redeemedemail', compact('htmlEmail'));
});
