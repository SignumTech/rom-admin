<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
class inventoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    public function itemsInventory(Request $request){
        $this->validate($request, [
            "items" => "required"
        ]);
        $inventory = [];
        $invIndex = 0;
        foreach($request->items as $item){
            $inv = Inventory::where('p_id', $item['p_id'])
                            ->where('color', $item['color'])
                            ->where('size', $item['size'])
                            ->first();

            $inventory[$invIndex] = $inv->quantity;
            $invIndex++; 
        }
        return $inventory;
    }
    public function itemsMobInventory(Request $request){
        $this->validate($request, [
            "items" => "required"
        ]);
        $inventory = [];
        $invIndex = 0;
        foreach(json_decode($request->items) as $item){
            $inv = Inventory::where('p_id', $item->p_id)
                            ->where('color', $item->color)
                            ->where('size', $item->size)
                            ->first();

            $inventory[$invIndex] = $inv->quantity;
            $invIndex++; 
        }
        return $inventory;
    }
}
