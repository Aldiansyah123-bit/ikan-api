<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //count invoice
        $pending = Invoice::where('status', 'pending')->where('customer_id', auth()->guard('api_customer')->user()->id)->count();
        $success = Invoice::where('status', 'success')->where('customer_id', auth()->guard('api_customer')->user()->id)->count();
        $expired = Invoice::where('status', 'expired')->where('customer_id', auth()->guard('api_customer')->user()->id)->count();
        $failed  = Invoice::where('status', 'failed')->where('customer_id', auth()->guard('api_customer')->user()->id)->count();

        //response
        return response()->json([
            'success' => true,
            'message' => 'Statistik Data',
            'data'    => [
                'count' => [
                    'pending'   => $pending,
                    'success'   => $success,
                    'expired'   => $expired,
                    'failed'    => $failed
                ]
            ]
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
