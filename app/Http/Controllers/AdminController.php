<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\APIResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    use APIResponse;
    
    public function index()
    {
        return $this->response("welcome ".Auth::user()->name, Auth::user(), 200);
    }
}
