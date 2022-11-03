<?php

namespace App\Http\Controllers;

use Chapa\Chapa\Facades\Chapa as Chapa;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Inventory;
use App\Models\Cart;
use DB;
class ChapaController extends Controller
{
    /**
     * Initialize Rave payment process
     * @return void
     */
    protected $reference;

    public function __construct(){
        $this->reference = Chapa::generateReference();

    }
    public function initialize(Request $request)
    {
        $this->validate($request, [
            "amount" => "required",
            "address" => "required",
            "cart_id" => "required"
        ]);
        //This generates a payment reference
        $reference = $this->reference;
        //generate a session for the order
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

            $order->total = $request->amount;
            $order->order_status = 'PROCESSING';
            $order->user_id = auth()->user()->id;
            $order->delivery_details = $request->address;
            $order->save();

            $cart->delete();
            DB::commit();
            $request->session()->put('order_id', $order->id);
        }
        catch (\Exception $e) {
            DB::rollBack();
            throw $e;
            return response('Order Error', 422);
        }
        
        // Enter the details of the payment
        $data = [
            
            'amount' => $request->amount,
            'email' => 'fnote.md@gmail.com',
            'tx_ref' => $reference,
            'currency' => "ETB",
            'callback_url' => route('callback',[$reference]),
            'return_url' => route('return_url',[$reference]),
            'first_name' => auth()->user()->f_name,
            'last_name' => auth()->user()->l_name,
            "customization" => [
                "title" => 'Product purchase',
                "description" => "A product purchase"
            ]
        ];
        

        $payment = Chapa::initializePayment($data);


        if ($payment['status'] !== 'success') {
            // notify something went wrong
            return $payment;
        }

        return redirect($payment['data']['checkout_url']);
    }

    /**
     * Obtain Rave callback information
     * @return void
     */
    public function callback($reference)
    {
        
        $data = Chapa::verifyTransaction($reference);
        dd($data);

        //if payment is successful
        if ($data['status'] ==  'success') {
        

        dd($data);
        }

        else{
            //oopsie something ain't right.
        }


    }

    public function return_url($reference, Request $request)
    {
        
        $data = Chapa::verifyTransaction($reference);

        //if payment is successful
        if ($data['status'] ==  'success') {
            if($request->session()->has('order_id')){
                $order_id = $request->session()->get('order_id');
                $order = Order::find($order_id);
                $order->payment_status = "PAID";
                $order->reference = $data["data"]["reference"];
                $order->tx_ref = $data["data"]["tx_ref"];
                $order->save();

                $request->session()->forget('order');

                return redirect('/orderComplete');
            }
        }

        else{
            //oopsie something ain't right.
        }


    }
}