<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api_customer');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $carts = Cart::with('product')
                ->where('customer_id', auth()->guard('api_customer')->user()->id)
                ->latest()
                ->get();

        //return with Api Resource
        return new CartResource(true, 'List Data Carts : '.auth()->guard('api_customer')->user()->name.'', $carts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCartPrice()
    {
        $totalPrice = Cart::with('product')
            ->where('customer_id', auth()->guard('api_customer')->user()->id)
            ->sum('price');

        //return with Api Resource
        return new CartResource(true, 'Total Cart Price', $totalPrice);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $item = Cart::where('product_id', $request->product_id)->where('customer_id', auth()->guard('api_customer')->user()->id);
        $items = Cart::where('product_id', $request->product_id)->where('customer_id', auth()->guard('api_customer')->user()->id);

        //check if product already in cart and increment qty
        if ($item->count() && $items->count()) {

            //increment quantity
            $item->increment('weight');

            $item = $item->first();

            $items->increment('price');

            $items = $items->first();

            //sum price * quantity
            $price = $request->price + $items->price - 1;

            //sum weight
            $weight = $request->weight + $item->weight - 1;

            $item->update([
                'price'     => $price,
                'weight'    => $weight
            ]);

        } else {

            //insert new item cart
            $item = Cart::create([
                'product_id'    => $request->product_id,
                'customer_id'   => auth()->guard('api_customer')->user()->id,
                // 'qty'           => $request->qty,
                'price'         => $request->price,
                'weight'        => $request->weight
            ]);

        }

        //return with Api Resource
        return new CartResource(true, 'Success Add To Cart', $item);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getCartWeight()
    {
        $totalWeight = Cart::with('product')
        ->where('customer_id', auth()->guard('api_customer')->user()->id)
        ->sum('weight');

        //return with Api Resource
        return new CartResource(true, 'Total Cart Weight', $totalWeight);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function removeCart(Request $request)
    {
        $cart = Cart::with('product')
            ->whereId($request->cart_id)
            ->first();

        $cart->delete();

        //return with Api Resource
        return new CartResource(true, 'Success Remove Item Cart', null);
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
