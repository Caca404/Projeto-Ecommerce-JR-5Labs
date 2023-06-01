@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    <h2>Bem vindo ao Project Ecommerce!</h2>
    <hr class="mb-5 mt-3">
    <div class="card mb-5 col-12 col-md-8 mx-auto" id="filtrosCompradorDashboard">
        <a class="text-decoration-none" data-bs-toggle="collapse" href="#collapseExample" role="button" 
            aria-expanded="false" aria-controls="collapseExample">

            <div class="card-header bg-navy p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Filtros</h4>

                    <i class="fa-solid fa-plus"></i>
                </div>
            </div>
        </a>
        <div class="card-body collapse @if(!$isRequestEmpty) show @endif" id="collapseExample">

            <form class="row g-3">
                <div class="col-12">
                    <label for="name">Nome do produto</label>
                    <input type="text" class="form-control" id="name" name="name" 
                        placeholder="Pesquisar nome do produto" value="{{app('request')->input('name')}}">
                </div>
                <div class="col-12 col-md-6 row g-3">
                    <div class="col-12 mb-3">
                        <label for="smallerPrice">Menor preço</label>
                        <input type="number" class="form-control" min="1" id="smallerPrice"
                            name="smallerPrice" value="{{app('request')->input('smallerPrice')}}">
                        
                    </div>
                    <div class="col-12 mb-3">
                        <label for="biggerPrice">Maior preço</label>
                        <input type="number" class="form-control" min="1" 
                            max="1000000" id="biggerPrice" name="biggerPrice" 
                            value="{{app('request')->input('biggerPrice')}}">
                    </div>
                </div>
                <div class="col-12 col-md-6 row g-3">
                    <div class="mb-3">
                        <label for="category">Categorias</label>
                        <select name="category[]" id="category" class="selectize" multiple>
                            @foreach ($categorias as $categoria)
                                <option value="{{$categoria}}" @if(!empty(json_decode(app('request')->input('category')))) {{
                                    in_array($categoria, json_decode(app('request')->input('category'))) ? 'selected' : ''
                                }} @endif>
                                    {{$categoria}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary w-100">Filtrar</button>
                </div>
            </form>
        </div>
    </div>
    <div class="row align-cards g-3">
        @if (!empty($produtos))
            @foreach ($produtos as $produto)
                <div class="col-12 col-md-3 mb-3 mb-md-0 d-flex align-items-stretch">
                    <a href="/produto/{{$produto->id}}" class="text-dark text-decoration-none w-100">
                        <div class="card h-100">
                            @if($produto->imagems->count())
                                <div class="flex-fill d-flex">
                                    <img src="/images/products/{{$produto->imagems->last()->name.'.'.$produto->imagems->last()->mime}}" 
                                        alt="Imagem produto" class="card-img-top">
                                </div>
                            @endif
                            <div class="card-body text-center">
                                <h5>{{ucfirst($produto->name)}}</h5>
                                <span>R$ {{ number_format($produto->price, 2, ',', '.')}}</span>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        @else
            <div class="card col-md-8 bg-danger text-white">
                <div class="card-body">
                    <h4>Ops! Estamos sem produtos no momento.</h4>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
