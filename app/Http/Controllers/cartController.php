<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Inventory;
class cartController extends Controller
{
    public function addToCart(Request $request){
        $this->validate( $request, [
            "product" => "required",
            "color" => "required",
            "size" => "required"
        ]);
        if (Auth::check()) {
            $product = [];
            $product['p_name'] = $request->product['p_name'];
            $color = $request['color'];

            $product['p_image'] = json_decode($request->product['p_image'])->$color[0];
            $product['price'] = $request->product['price'];
            $product['promotion_status'] = $request->product['promotion_status'];
            if($request->product['promotion_status'] == 'FLASH SALE'){
                $product['new_price'] = $request->product['price'] - ($request->product['price']*$request->product['discount']/100);
            }
            else{
                $product['new_price'] = $request->product['price'];
            }
            $product['p_id'] = $request->product['id'];
            $product['color'] = $request['color'];
            $product['size'] = $request['size'];
            $product['quantity'] = $request['quantity'];

            $cart = Cart::where('user_id', auth()->user()->id)
                        ->where('cart_status', 'ACTIVE')
                        ->first();
            if($cart){
                $cart_items = json_decode($cart->items);
                $isSameProduct = false;
                foreach($cart_items as $cd){
                    if($cd->p_id == $product['p_id'] && $cd->color == $product['color'] && $cd->size == $product['size']){
                        $cd->quantity += 1;
                        $isSameProduct = true;
                        break;
                    }
                }
                if($isSameProduct == false){
                    array_push($cart_items,$product);
                }
                $cart->items = json_encode($cart_items);
                $cart->save();
                return $cart;
            }
            else{
                $data = [];
                array_push($data,$product);
                $cart = new Cart;
                $cart->items = json_encode($data);
                $cart->user_id = auth()->user()->id;
                $cart->save();
                return $cart;
            }
        }
        else{
            if($request->session()->has('cart')) {
                $product = [];
                $product['p_name'] = $request->product['p_name'];
                $color = $request['color'];

                $product['p_image'] = json_decode($request->product['p_image'])->$color[0];
                $product['price'] = $request->product['price'];
                $product['promotion_status'] = $request->product['promotion_status'];
                if($request->product['promotion_status'] == 'FLASH SALE'){
                    $product['new_price'] = $request->product['price'] - ($request->product['price']*$request->product['discount']/100);
                }
                else{
                    $product['new_price'] = $request->product['price'];
                }
                $product['p_id'] = $request->product['id'];
                $product['color'] = $request['color'];
                $product['size'] = $request['size'];
                $product['quantity'] = $request['quantity'];
                $cartData = json_decode($request->session()->get('cart'));
                $isSameProduct = false;
                foreach($cartData as $cd){
                    if($cd->p_id == $product['p_id'] && $cd->color == $product['color'] && $cd->size == $product['size']){
                        $cd->quantity += 1;
                        $isSameProduct = true;
                        break;
                    }
                }
                if($isSameProduct == false){
                    array_push($cartData,$product);
                }
                $request->session()->put('cart', json_encode($cartData));
                return response('',200);
            }
            else{
                $cartData = [];
                $product = [];
                
                $product['p_name'] = $request->product['p_name'];
                $color = $request['color'];
                
                $product['p_image'] = json_decode($request->product['p_image'])->$color[0];
                $product['price'] = $request->product['price'];
                $product['promotion_status'] = $request->product['promotion_status'];
                if($request->product['promotion_status'] == 'FLASH SALE'){
                    $product['new_price'] = $request->product['price'] - ($request->product['price']*$request->product['discount']/100);
                }
                else{
                    $product['new_price'] = $request->product['price'];
                }
                $product['p_id'] = $request->product['id'];
                $product['color'] = $request['color'];
                $product['size'] = $request['size'];
                $product['quantity'] = $request['quantity'];
                array_push($cartData,$product);
                $request->session()->put('cart', json_encode($cartData));

                return response('',200);
            }
        }

    }

    public function getCart(Request $request){
        if (Auth::check()){

            if($request->session()->has('cart')){
                $cartData = json_decode($request->session()->get('cart'));
                $cart = Cart::where('user_id', auth()->user()->id)
                            ->where('cart_status', 'ACTIVE')
                            ->first();
                if($cart){
                    $cart_db = json_decode($cart->items);
                    foreach($cart_db as $cd){
                        foreach($cartData as $cart_data){
                            if($cd->p_id == $cart_data->p_id && $cd->color == $cart_data->color && $cd->size == $cart_data->size){
                                $cd->quantity += 1;
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
                    $cart->items = json_encode($cartData);
                    $cart->user_id = auth()->user()->id;
                    $cart->save();
                    $request->session()->forget('cart');
                    return $cart;
                }
                
            }
            else{
                $cart = Cart::where('user_id', auth()->user()->id)
                            ->where('cart_status', 'ACTIVE')
                            ->first();
                return $cart;
            }
            
        }
        else{
            if($request->session()->has('cart')){
                $data =[];
                $data['items'] = $request->session()->get('cart');
                return $data;
            }
            else{
                return response("No items in cart", 422);
            }
        }
    }

    public function editCart(Request $request){
        $this->validate( $request, [
            "product" => "required",
            "color" => "required",
            "size" => "required",
            "index" => "required",
        ]);

        if (Auth::check()){
            $cart = Cart::where('user_id', auth()->user()->id)
                            ->where('cart_status', 'ACTIVE')
                            ->first();
            $cart_items = json_decode($cart->items);
            //$new_item = $cart_items[$request->index];
            
            $cart_items[$request->index]->size = $request->size;
            $cart_items[$request->index]->color = $request->color;
            $cart->items = json_encode($cart_items);
            $cart->save();
            
            return $cart;
        }
        else{
            $cartData = json_decode($request->session()->get('cart'));
            //$cart_items = $cartData[$request->index];
            $cartData[$request->index]->size = $request->size;
            $cartData[$request->index]->color = $request->color;
            $request->session()->put('cart', json_encode($cartData));

            return response('',200);
        }
    }

    public function updateCart(Request $request, $id){
        $this->validate($request, [
            "items" => "required"
        ]);

        $invData = [];
        $items = $request->items;
        
        foreach($items as $item){
            $invCheck = Inventory::where('p_id', $item['p_id'])
                                 ->where('color', $item['color'])
                                 ->where('size', $item['size'])
                                 ->first();
            if($invCheck->quantity < $item['quantity']){
                $invData[$item['p_id']]['err'] = 'Only '.$invCheck->quantity.' are available';
                $invData[$item['p_id']]['invError'] = true;
            }
        }

        if(count($invData) > 0){
            return response($invData, 422);
        }
        else{
            $cart = Cart::find($id);
            $cart->items = json_encode($request->items);
            $cart->save();

            return $cart;            
        }

    }

    public function updateMobCart(Request $request, $id){
        $this->validate($request, [
            "items" => "required"
        ]);

        $invData = [];
        $items = $request->items;
        
        foreach(json_decode($items) as $item){
            $invCheck = Inventory::where('p_id', $item->p_id)
                                 ->where('color', $item->color)
                                 ->where('size', $item->size)
                                 ->first();
            if($invCheck->quantity < $item->quantity){
                $invData[$item->p_id]['err'] = 'Only '.$invCheck->quantity.' are available';
                $invData[$item->p_id]['invError'] = true;
            }
        }

        if(count($invData) > 0){
            return response($invData, 422);
        }
        else{
            $cart = Cart::find($id);
            $cart->items = json_encode(json_decode($request->items));
            $cart->save();

            return $cart;            
        }

    }

    public function deleteItem(Request $request){
        $this->validate($request, [
            "index" => "required"
        ]);
        
        if (Auth::check()){
            $cart = Cart::where('user_id', auth()->user()->id)
                            ->where('cart_status', 'ACTIVE')
                            ->first();
            $cart_items = json_decode($cart->items);
            
            array_splice($cart_items, $request->index, 1);
            $cart->items = json_encode($cart_items);
            $cart->save();
            return $cart;
        }
        else{
            $cartData = json_decode($request->session()->get('cart'));
            array_splice($cartData, $request->index, 1);

            $request->session()->put('cart', json_encode($cartData));
            $data = [];
            $data['items'] = $request->session()->get('cart');
            return $data;
        }

    }

    public function getMobCart(Request $request){
        $this->validate($request, [
            "items" => "required"
        ]);
        $cartData = json_decode($request->items);
        if(count($cartData)>0){
                
                $cart = Cart::where('user_id', auth()->user()->id)
                            ->where('cart_status', 'ACTIVE')
                            ->first();
                if($cart){
                    $cart_db = json_decode($cart->items);
                    foreach($cart_db as $cd){
                        foreach($cartData as $cart_data){
                            if($cd->p_id == $cart_data->p_id && $cd->color == $cart_data->color && $cd->size == $cart_data->size){
                                $cd->quantity += 1;
                                array_splice($cartData, array_search($cart_data, $cartData), 1);
                            }
                        }

                    }
                    if(count($cartData)>0){
                        $cart_db = array_merge($cart_db,$cartData);
                    }
        
                    $cart->items = json_encode($cart_db);
                    $cart->save();
                    
                    return $cart;
                }
                else{
                    $cart = new Cart;
                    $cart->items = json_encode($cartData);
                    $cart->user_id = auth()->user()->id;
                    $cart->save();
                    
                    return $cart;
                }
                
            }
            else{
                $cart = Cart::where('user_id', auth()->user()->id)
                            ->where('cart_status', 'ACTIVE')
                            ->first();
                return $cart;
            }
        
        return $cart;
    }


}
