<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
class customersController extends Controller
{
    public function getCustomers(){
        $customers = User::leftJoin('address_books', 'users.id', '=', 'address_books.user_id')
                         ->where('account_type', "USER")
                         ->select('users.id','users.email','users.f_name', 'users.l_name', 'users.created_at', 'address_books.address_1')
                         ->distinct('users.id')->get();
        foreach($customers as $customer){
            $customer->spent = Order::where('user_id', $customer->id)->sum('total');
        }
        return $customers;
    }
}
