<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Inventory;
use DB;
class ordersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::all();
        $items = 0;
        
        foreach($orders as $order){
            $pic = null;
            $o_items = json_decode($order->items);
            foreach($o_items as $ot){
                $items = $items + $ot->quantity;
                if($pic == null){
                    $pic = $ot->p_image;
                }
            }
            $order->no_items = $items;
            $order->p_image = $pic;
            $items = 0;
        }
        return $orders;
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
        $this->validate($request, [
            'cart_id' => 'required | integer',
            'total' => 'required | numeric',
            'address' => 'required | integer'
        ]);
        try{
            DB::beginTransaction();
            $cart = Cart::find($request->cart_id);
            $items = json_decode($cart->items);
            //////////update inventory//////////////////////
            foreach($items as $item){
                $inventory = Inventory::where('p_id', $item->p_id)
                                    ->where('size', $item->size)
                                    ->where('color', $item->color)
                                    ->lockForUpdate()->first();
                if($inventory->quantity < $item->quantity){
                    return response("Some of the items have been sold out", 422);
                }
                else{
                    $inventory->quantity -= $item->quantity;
                    $inventory->save();
                }
                
            }
            
            ////////////////////////////////////////////////
            $order = new Order;
            $order->items = json_encode($items);

            $latestOrder = Order::orderBy('created_at','DESC')->first();
            if($latestOrder){
                $order->order_no = '#'.str_pad($latestOrder->id + 1, 8, "0", STR_PAD_LEFT);
            }
            else{
                $order->order_no = '#'.str_pad(1, 8, "0", STR_PAD_LEFT);
            }

            $order->total = $request->total;
            $order->order_status = 'PROCESSING';
            $order->user_id = auth()->user()->id;
            $order->delivery_details = $request->address;
            $order->save();

            $cart->delete();
            DB::commit();
            return $order;
        }
        catch (\Exception $e) {
            DB::rollBack();
            throw $e;
            return response('Order Error', 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Order::find($id);
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
        $this->validate($request, [
            'items' => 'required | string',
            'total' => 'required | double',
            'delivery_details' => 'required | string'
        ]);
        $order = Order::find($id);
        $order->items = json_encode($request->items);
        $order->order_no = 'to be determined';
        $order->total = $request->total;
        $order->order_status = 'PROCESSING';
        $order->user_id = auth()->user()->id;
        $order->delivery_details = $request->delivery_details;
        $order->save();

        return $order;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::find($id);
        $order->delete();

        return $order;
    }

    public function getProcessing(){
        $orders = Order::where('order_status', 'PROCESSING')->get();
        $items = 0;
        
        foreach($orders as $order){
            $pic = null;
            $o_items = json_decode($order->items);
            foreach($o_items as $ot){
                $items = $items + $ot->quantity;
                if($pic == null){
                    $pic = $ot->p_image;
                }
            }
            $order->no_items = $items;
            $order->p_image = $pic;
            $items = 0;
        }
        return $orders;
    }

    public function getShipped(){
        $orders = Order::where('order_status', 'SHIPPED')->get();
        $items = 0;
        
        foreach($orders as $order){
            $pic = null;
            $o_items = json_decode($order->items);
            foreach($o_items as $ot){
                $items = $items + $ot->quantity;
                if($pic == null){
                    $pic = $ot->p_image;
                }
            }
            $order->no_items = $items;
            $order->p_image = $pic;
            $items = 0;
        }
        return $orders;
    }

    public function getDelivered(){
        $orders = Order::where('order_status', 'DELIVERED')->get();
        $items = 0;
        
        foreach($orders as $order){
            $pic = null;
            $o_items = json_decode($order->items);
            foreach($o_items as $ot){
                $items = $items + $ot->quantity;
                if($pic == null){
                    $pic = $ot->p_image;
                }
            }
            $order->no_items = $items;
            $order->p_image = $pic;
            $items = 0;
        }
        return $orders;
    }

    public function getMyOrders(){
        $orders = Order::where('user_id', auth()->user()->id)->get();
        $items = 0;
        
        foreach($orders as $order){
            $pic = null;
            $o_items = json_decode($order->items);
            foreach($o_items as $ot){
                $items = $items + $ot->quantity;
                if($pic == null){
                    $pic = $ot->p_image;
                }
            }
            $order->no_items = $items;
            $order->p_image = $pic;
            $items = 0;
        }
        return $orders;
    }

    public function getMyOrdersStatus($status){
        $orders = Order::where('user_id', auth()->user()->id)
                        ->where('order_status', $status)->get();
        $items = 0;
        $pic = null;
        foreach($orders as $order){
            $o_items = json_decode($order->items);
            foreach($o_items as $ot){
                $items = $items + $ot->quantity;
                if($pic == null){
                    $pic = $ot->p_image;
                }
            }
            $order->no_items = $items;
            $order->p_image = $pic;
            $items = 0;
        }
        return $orders;
    }

    public function repurchaseOrder(Request $request){
        $this->validate($request, [
            "order_id" => "required"
        ]);

        $order = Order::find($request->order_id);
        $cart = Cart::where('user_id', auth()->user()->id)
                    ->where('cart_status', 'ACTIVE')
                    ->first();
        
        
        if($cart){
            $cartData = json_decode($cart->items);
            $cart_db = json_decode($order->items);
            foreach($cart_db as $cd){
                foreach($cartData as $cart_data){
                    if($cd->p_id == $cart_data->p_id && $cd->color == $cart_data->color && $cd->size == $cart_data->size){
                        $cd->quantity += $cart_data->quantity;
                        array_splice($cartData, array_search($cart_data, $cartData), 1);
                    }
                }

            }
            if(count($cartData)>0){
                $cart_db = array_merge($cart_db,$cartData);
            }

            $cart->items = json_encode($cart_db);
            $cart->save();
            $request->session()->forget('cart');
            return $cart;
        }
        else{
            $cart = new Cart;
            $cart->items = $order->items;
            $cart->user_id = auth()->user()->id;
            $cart->cart_status = "ACTIVE";
            $cart->save();
        }

        return $cart;
    }

    public function shipOrder(Request $request){
        $this->validate($request,[
            "order_id" => "required"
        ]);

        $order = Order::find($request->order_id);
        $order->order_status = 'SHIPPED';
        $order->save();

        return $order;
    }   
}
