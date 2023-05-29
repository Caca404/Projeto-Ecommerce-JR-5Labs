@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    <h3>Dashboard Admin</h3>
    <hr class="mb-5 mt-3">
    <div class="d-flex flex-row justify-content-around flex-wrap">
        <a class="col-12 col-md-3 mb-4 text-dark text-decoration-none" href="/admin/compradores">
            <div class="border rounded p-2 py-4 text-center">
                <i class="fa-solid fa-user mb-3" style="font-size: 60px"></i>
                <h4>Lista de Compradores</h4>
            </div>
        </a>
        <a class="col-12 col-md-3 mb-4 text-dark text-decoration-none" href="/admin/vendedores">
            <div class="border rounded p-2 py-4 text-center">
                <i class="fa-solid fa-store mb-3" style="font-size: 60px"></i>
                <h4>Lista de Vendedores</h4>
            </div>
        </a>
        <a class="col-12 col-md-3 mb-4 text-dark text-decoration-none" href="#">
            <div class="border rounded p-2 py-4 text-center">
                <i class="fa-solid fa-cart-shopping mb-3" style="font-size: 60px"></i>
                <h4>Lista de Produtos</h4>
            </div>
        </a>
    </div>
</div>
@endsection
