<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

abstract class OwnerBaseController extends Controller
{
    protected function shop(Request $request): Store
    {
        $shop = $request->user()->store;

        abort_if($shop === null, 409, 'No shop has been assigned to your account yet.');

        return $shop;
    }
}
