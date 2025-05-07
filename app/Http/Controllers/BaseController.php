<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function customRedirect(string $route, string $messageType = '', string $message = '', array $parmeters = [], int $status = 302, mixed $header = [])
    {
        return redirect()->route($route, $parmeters, $status, $header)->with($messageType, $message);
    }
}
