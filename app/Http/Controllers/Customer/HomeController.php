<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\BaseController;

class HomeController extends BaseController
{
    public function home()
    {
        return view('customer.home');
    }
}
