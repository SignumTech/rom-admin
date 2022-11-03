<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FlashSell;
use App\Models\FlashDetail;
use App\Models\Product;
use Carbon\Carbon;
class flashSaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $flashSale = FlashSell::orderBy('created_at', 'DESC')->get();
        $currentDate = Carbon::now();
        //dd($currentDate);
        foreach($flashSale as $fs){
            if((Carbon::parse($fs->starts_at)->lt($currentDate) ) && ($currentDate->lt(Carbon::parse($fs->expiry_date)) )){
                $fs->current = true;
            }
            else{
                $fs->current = false;
            }
        }

        return $flashSale;
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
            "starts_at" => "required",
            "ends_at" => "required"
        ]);
        $starts_at = Carbon::parse($request->starts_at);
        $ends_at = Carbon::parse($request->ends_at);
        ///////////////////check/////////////////////
        if($starts_at->gt($ends_at)){
            return response("The start date should be earlier than the ends_at", 422);
        }

        $flash_check = FlashSell::latest()->first();
        
        if($flash_check){
            if(Carbon::parse($flash_check->expiry_date)->gt($starts_at)){
                return response("The time range clashes with the previous flash sale.", 422);
            }
            else{
                $flash = new FlashSell;
                $flash->starts_at = $starts_at;
                $flash->expiry_date = $ends_at;
                $flash->save();
        
                return $flash;
            }
        }
        else{
            $flash = new FlashSell;
            $flash->starts_at = $starts_at;
            $flash->expiry_date = $ends_at;
            $flash->save();
    
            return $flash;
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
        $fs = FlashSell::find($id);
        $currentDate = Carbon::now();
        if((Carbon::parse($fs->starts_at)->lt($currentDate) ) && ($currentDate->lt(Carbon::parse($fs->expiry_date)) )){
            $fs->current = true;
        }
        else{
            $fs->current = false;
        }
        return $fs;
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

    public function addToflashSales(Request $request){
        $this->validate($request, [
            "flashPeriod" => "required",
            "discount" => "required",
            "p_id" => "required"
        ]);

        $flashDetail = new FlashDetail;
        $flashDetail->flash_id = $request->flashPeriod;
        $flashDetail->discount = $request->discount;
        $flashDetail->p_id = $request->p_id;
        $flashDetail->save();

        $product = Product::find($request->p_id);
        $product->promotion_status = 'FLASH SALE';
        $product->save();
        
        return $flashDetail;
    }

    public function getFlashProducts($id){
        $products = FlashDetail::join('products', 'flash_details.p_id', '=', 'products.id')
                             ->where('flash_id', $id)
                             ->get();
        return $products;
    }

    public function getFlashSales(){
        $flashSales = FlashSell::all();
        
        
        $currentDate = Carbon::now();
        
        foreach($flashSales as $fs){
            if((Carbon::parse($fs->starts_at)->lt($currentDate) ) && ($currentDate->lt(Carbon::parse($fs->expiry_date)) )){
                
                $flashProducts = FlashDetail::join('products', 'flash_details.p_id', '=', 'products.id')
                                            ->join('flash_sells', 'flash_details.flash_id', '=', 'flash_sells.id')
                                            ->where('flash_details.flash_id', $fs->id)->get();
                
                return $flashProducts;
            }         
        }
        
    }
}
