<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AddressBook;
class addressBookController extends Controller
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
        $this->validate($request, [
            "f_name" => "required",
            "l_name" => "required",
            "phone_no" => "required",
            "city" => "required",
            "state" => "required",
            "address_1" => "required",
        ]);

        $addressBook = new AddressBook;
        $addressBook->f_name = $request->f_name;
        $addressBook->l_name = $request->l_name;
        $addressBook->user_id = auth()->user()->id;
        $addressBook->phone_no = $request->phone_no;
        $addressBook->city = $request->city;
        $addressBook->state = $request->state;
        $addressBook->address_1 = $request->address_1;
        $addressBook->address_2 = $request->address_2;
        if(!AddressBook::where("user_id", auth()->user()->id)->first()){
            $addressBook->type = 'DEFAULT';
        }

        $addressBook->save();

        return $addressBook;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $book = AddressBook::where('user_id',$id)->get();
        if(count($book)>0){
            return $book;
        }
        else{
            return response('Address doesnt exist', 422);
        }
        
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
            "f_name" => "required",
            "l_name" => "required",
            "phone_no" => "required",
            "city" => "required",
            "state" => "required",
            "address_1" => "required",
        ]);
        
        $addressBook = AddressBook::find($id);
        $addressBook->f_name = $request->f_name;
        $addressBook->l_name = $request->l_name;
        $addressBook->user_id = auth()->user()->id;
        $addressBook->phone_no = $request->phone_no;
        $addressBook->city = $request->city;
        $addressBook->state = $request->state;
        $addressBook->address_1 = $request->address_1;
        $addressBook->address_2 = $request->address_2;

        $addressBook->save();

        return $addressBook;
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

    public function showAddress($id){
        $address = AddressBook::find($id);
        return $address;
    }

    public function makeDefaultAddress($id){

        $current_default = AddressBook::where('user_id', auth()->user()->id)
                                      ->where('type', 'DEFAULT')
                                      ->first();
        $current_default->type = null;
        $current_default->save();

        $address = AddressBook::find($id);
        $address->type = "DEFAULT";
        $address->save();

        return $address;
    }
}
