<?php

namespace App\Http\Controllers;

use App\Models\Vendedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendedorController extends Controller
{
    public function dashboard(Request $request)
    {
        $produtos = Auth::user()->vendedor->produtos;
        
        return view('vendedor/dashboard', ['produtos' => $produtos]);
    }
}
