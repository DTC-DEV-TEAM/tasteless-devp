<?php

namespace App\Http\Controllers;

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
