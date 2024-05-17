<?php

namespace App\Http\Controllers;

use App\EgcValueType;
use App\EmailTemplateImg;
use App\EmailTesting;
use App\g_c_lists_devp;
use Session;
use Illuminate\Support\Facades\Request as Input;
use DB;
use App\GCList;
use App\GCListsDevpsCustomer;
use App\Jobs\GCListFetchJob;
use App\Jobs\SendEmailJob;
use App\Jobs\SendEmailOtp;
use App\StoreHistory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CustomerRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($store_branch, $customer_reference_number)
    {

        $gclist_devp = g_c_lists_devp::where('customer_reference_number', $customer_reference_number)->first();

        if($gclist_devp->store_status >= 4 || !$gclist_devp){

            return view('customer.prohibited');
        }

        $data = [];
        $data['store_branches'] = DB::table('store_concepts')
            ->where('store_concepts.status', 'ACTIVE')
            ->orderBY('beach_name', 'asc')
            ->get()
            ->toArray();
        $data['recipient'] = DB::table('g_c_lists_devps')->where('customer_reference_number',$customer_reference_number)->first();
        $data['customer'] = DB::table('g_c_lists_devps_customers')->where('id', $data['recipient']->g_c_lists_devps_customer_id)->first();
        
        return view('customer.customer_registration', $data);
    }

    public function qrLink()
    {
        return view('customer.customer_qr_link');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $customer = $request->all();

        $gc_list_devp = g_c_lists_devp::where('customer_reference_number', $customer['customer_reference_number']);
        $gc_list_devp_customer = GCListsDevpsCustomer::where('id', $gc_list_devp->first()->g_c_lists_devps_customer_id);
        $store_history = StoreHistory::where('g_c_lists_devps_id',$gc_list_devp->first()->id);

        do {
            $generated_qr_code = Str::random(10);
        } while (DB::table('g_c_lists_devps')->where('qr_reference_number', $generated_qr_code)->exists());
        
        $otp = '';

        for ($i = 0; $i < 4; $i++) {
            $otp .= rand(0, 9);
        }
        
        $gc_list_devp_customer->update([
            'first_name' => $customer['first_name'],
            'last_name' => $customer['last_name'],
            'name' => $customer['first_name'].' '.$customer['last_name'],
            'phone' => $customer['contact_number'],
            'email' => $customer['email'],
            'confirmed_email' => $customer['confirm_email'],
            'otp_code' => $otp,
            'is_subscribe' => $customer['subscribe']
        ]);
        $gc_list_devp->update([
            'qr_reference_number' => $generated_qr_code,
            'store_status' => 2
        ]);

        $data = [
            'gc_list_devp_customer_id' => $gc_list_devp_customer->first()->id,
            'subject_of_the_email' => 'Your One-Time Password (OTP) for E-Gift Card',
            'email' => $gc_list_devp_customer->first()->email,
            'otp' => $gc_list_devp_customer->first()->otp_code,
            'link' => $gc_list_devp->first()->qr_link
        ];

        $egc_data = $this->sendGiftCardCustomer($gc_list_devp_customer->first(), $gc_list_devp->first());

        SendEmailJob::withChain([
            new SendEmailOtp($data)
        ])->dispatch($egc_data);

        return response()->json(['is_otp_sent' => 1]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function verifyOtp(Request $request){

        $customer = $request->all();

        $gc_list_devp = g_c_lists_devp::where('customer_reference_number', $customer['customer_reference_number']);
        $gc_list_devp_customer = GCListsDevpsCustomer::where('id', $gc_list_devp->first()->g_c_lists_devps_customer_id);
        
        if($gc_list_devp_customer->first()->otp_code == $customer['otp']){

            $gc_list_devp_customer->update([
                'otp_is_matched' => 1
            ]);

            $devp =  $gc_list_devp->update([
                'store_status' => 3
            ]);

            return response()->json(['otp' => true]);
        }else{
            return response()->json(['otp' => false]);
        }
    }

    public function sendEgc(Request $request){

        $customer = $request->all();
        
        $gc_list_devp = g_c_lists_devp::where('customer_reference_number', $customer['customer_reference_number']);
        $gc_list_devp_customer = GCListsDevpsCustomer::where('id', $gc_list_devp->first()->g_c_lists_devps_customer_id);
        $store_history = StoreHistory::where('g_c_lists_devps_id',$gc_list_devp->first()->id);
        $same_email = false;

        $gc_list_devp->update([
            'first_name' => $customer['egc_first_name'],
            'last_name' => $customer['egc_last_name'],
            'name' => $customer['egc_first_name'].' '.$customer['egc_last_name'],
            'email' => $customer['egc_email'],
        ]);


        if($gc_list_devp->first()->email != $gc_list_devp_customer->first()->email){
            $send_egc = $this->sendGiftCardRecipient($gc_list_devp->first());

            $gc_list_devp->update([
                'store_status' => 4,
                'email_is_sent' => 1
            ]);
            $same_email = true;
        }else{
            $gc_list_devp->update([
                'store_status' => 5,
            ]);
        }

        return response()->json(['same_email' => $same_email]);
    }

    public function sendGiftCardRecipient($recipient){

        $email_testings = new EmailTesting();
		$egc_value = EgcValueType::where('id',(int) $recipient->egc_value_id)->first()->value;

        $url = "/g_c_lists/edit/$recipient->id?value=$recipient->qr_reference_number&campaign_id=3";
        $qrCodeApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($url);
        $qr_code = "<div id='qr-code-download'><div id='download_qr'><a href='$qrCodeApiUrl' download='qr_code.png'> <img src='$qrCodeApiUrl' alt='QR Code'> </a></div></div>";
        
        $emailTesting = $email_testings
            ->where('status','ACTIVE')
            ->first();
        $emailTestingImg = EmailTemplateImg::where('header_id', $emailTesting->id)
            ->get();

        $html_email_img = [];

        foreach($emailTestingImg as $file){
            $filename = $file->file_name;
            $html_email_img[]= $filename;
        }

        $html_email = str_replace(
            ['[name]'],
            [$recipient->name],
            $emailTesting->html_email
        );

        $data = array(
            'id' => $recipient->id,
            'html_email' => $html_email,
            'email_subject' => $emailTesting->subject_of_the_email,
            'html_email_img' => $html_email_img,
            'email' => $recipient->email,
            'qrCodeApiUrl' => $qrCodeApiUrl,
            'qr_code' => $qr_code,
            'gc_value' => $egc_value,
            'store_logo' => $emailTesting->store_logos_id,
            'qr_reference_number'=> $recipient->qr_reference_number,
        );

        SendEmailJob::dispatch($data);
    }

    public function sendGiftCardCustomer($customer, $recipient){
        
        $email_testings = new EmailTesting();
		$egc_value = EgcValueType::where('id',(int) $recipient->egc_value_id)->first()->value;

        $url = "/g_c_lists/edit/$recipient->id?value=$recipient->qr_reference_number&campaign_id=3";
        $qrCodeApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($url);
        $qr_code = "<div id='qr-code-download'><div id='download_qr'><a href='$qrCodeApiUrl' download='qr_code.png'> <img src='$qrCodeApiUrl' alt='QR Code'> </a></div></div>";
        
        $emailTesting = $email_testings
            ->where('status','ACTIVE')
            ->first();
        $emailTestingImg = EmailTemplateImg::where('header_id', $emailTesting->id)
            ->get();

        $html_email_img = [];

        foreach($emailTestingImg as $file){
            $filename = $file->file_name;
            $html_email_img[]= $filename;
        }

        $html_email = str_replace(
            ['[name]'],
            [$customer->name],
            $emailTesting->html_email
        );

        $data = array(
            'id' => $recipient->id,
            'html_email' => $html_email,
            'email_subject' => $emailTesting->subject_of_the_email,
            'html_email_img' => $html_email_img,
            'email' => $customer->email,
            'qrCodeApiUrl' => $qrCodeApiUrl,
            'qr_code' => $qr_code,
            'gc_value' => $egc_value,
            'store_logo' => $emailTesting->store_logos_id,
            'qr_reference_number'=> $recipient->qr_reference_number,
            'link' => str_replace('qr_link','customer_registration',$recipient->qr_link)
        );

        return $data;
    }
}
