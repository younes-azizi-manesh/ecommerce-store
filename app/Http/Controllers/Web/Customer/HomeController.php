<?php

namespace App\Http\Controllers\Web\Customer;

use App\Http\Controllers\Web\BaseController;

class HomeController extends BaseController
{
    public function home()
    {
        return view('customer.home');
    }
}
