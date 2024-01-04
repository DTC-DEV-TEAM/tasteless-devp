<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Support\Facades\Request as Input;
use DB;
use App\GCList;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CustomerRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('customer.customer_registration');
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

        do {
            $generated_qr_code = Str::random(10);
        } while (GCList::where('qr_reference_number', $generated_qr_code)->exists());

        $gc_list = new GCList([
            'first_name' => $customer['first_name'],
            'last_name' => $customer['last_name'],
            'name' => $customer['first_name'].' '.$customer['last_name'],
            'phone' => $customer['contact_number'],
            'email' => $customer['email'],
            'store_concept' => $customer['concept'],
            'qr_reference_number' => $generated_qr_code
        ]);

        $gc_list->save();

        return redirect()->back()->with('success', $gc_list->toArray());
    }
    public function suggestExistingCustomer(Request $request){
        $term = $request->input('term');
        $suggestions = DB ::table('g_c_lists')
            ->whereNotNull('g_c_lists.name')
            ->where('g_c_lists.name', 'like', '%' . $term . '%')
            ->select('g_c_lists.name as text', 'g_c_lists.email as id')
            ->distinct('g_c_lists.email')
            ->orderBy('g_c_lists.name', 'desc')
            ->get();

        return response()->json($suggestions);
    }

    public function viewCustomerInfo(Request $request){
        $user_request = $request->all();
        $user_customer_email = $user_request['customerEmail'];
        $customer_information = GCList::where('email',$user_customer_email)
            ->first();

        return response()->json(['customer_information' => $customer_information]);
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
}
