@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container">
        <h3>Produtos</h3>
        <hr class="mb-5 mt-3">
        <div class="card mb-5 col-12 col-md-8 mx-auto" id="filtrosCompradorDashboard">
            <a class="text-decoration-none" data-bs-toggle="collapse" href="#collapseExample" role="button" 
                aria-expanded="false" aria-controls="collapseExample">
    
                <div class="card-header rounded-top bg-navy p-3">
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
        <div class="row g-3">
            @if(!empty($compras))
                @foreach ($compras as $produto)
                    <div class="col-12 col-md-8 mx-auto">
                        <a href="/produto/{{$produto->id}}" class="text-dark text-decoration-none">
                            <div class="card">
                                <div class="card-header bg-white text-end">
                                    <span class="fst-italic text-secondary">
                                        Data do Pedido:
                                        <i class="fa-solid fa-clock"></i>
                                        {{date('d/m/Y H:i', strtotime($produto->pivot->created_at))}}
                                    </span>
                                </div>
                                <div class="card-body row">
                                    @if($produto->imagems->count())
                                        <img class="col-12 col-md-2" 
                                            src="/images/products/{{$produto->imagems->last()->name.'.'.$produto->imagems->last()->mime}}" 
                                            alt="">
                                    @else
                                        <img class="col-12 col-md-2" src="https://picsum.photos/600/600" alt="">
                                    @endif
                                    <div class="col-12 col-md-6 mt-2 mt-md-0">
                                        <div>
                                            <h4 style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
                                                {{ucfirst($produto->name)}}
                                            </h4>
                                        </div>
                                        <h5 class="fw-normal">R$ {{number_format($produto->pivot->cost, 2, ',', '.')}}</h5>
                                        <span class="fst-italic">
                                            Vendido por:
                                            <i class="fa-solid fa-store text-secondary"></i>
                                            {{$produto->vendedor->user->name}}
                                        </span>
                                    </div>
                                    <div class="col-12 col-md-4 mt-3 mt-md-0 row align-items-center">
                                        <button class="btn btn-outline-dark w-100 p-3">Ver produto</button>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            @else
                <div class="col-12 col-md-8 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <span>
                                Nenhum pedido feito! Clique no link abaixo para
                                voltar aos produtos.
                            </span>
                            <br>
                            <button class="btn btn-outline-dark mt-3" onclick="location.href = '/'">
                                <i class="fa-solid fa-arrow-left"></i>
                                Voltar aos produtos
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection