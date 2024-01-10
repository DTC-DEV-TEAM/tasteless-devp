<?php

namespace App\Http\Controllers;

use App\g_c_lists_devp;
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

        $data = [];
        $data['store_branches'] = DB::table('store_concepts')
            ->where('store_concepts.status', 'ACTIVE')
            ->orderBY('beach_name', 'asc')
            ->get()
            ->toArray();

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
        
        do {
            $generated_qr_code = Str::random(10);
        } while (GCList::where('qr_reference_number', $generated_qr_code)->exists());

        $gc_list = new g_c_lists_devp([
            'first_name' => $customer['first_name'],
            'last_name' => $customer['last_name'],
            'name' => $customer['first_name'].' '.$customer['last_name'],
            'phone' => $customer['contact_number'],
            'email' => $customer['email'],
            'store_concept' => $customer['concept'],
            'store_concepts_id' => $customer['store_concepts_id'],
            'qr_reference_number' => $generated_qr_code,
            'store_status' => 1
        ]);

        $gc_list->save();

        return redirect()->back()->with('success', $gc_list->toArray());
    }
    public function suggestExistingCustomer(Request $request){
        $term = $request->input('term');
        $suggestions = DB::table('g_c_lists_devps')
            ->whereNotNull('g_c_lists_devps.email')
            ->where('g_c_lists_devps.email', 'like', '%' . $term . '%')
            ->select('g_c_lists_devps.email as text', DB::raw('MAX(g_c_lists_devps.id) as id'))
            ->groupBy('g_c_lists_devps.email')
            ->orderBy('id', 'desc')
            ->get();
    
        return response()->json($suggestions);
    }

    public function viewCustomerInfo(Request $request){
        $user_request = $request->all();
        $user_customer_id = $user_request['customerID'];
        $customer_information = g_c_lists_devp::where('id',$user_customer_id)
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
