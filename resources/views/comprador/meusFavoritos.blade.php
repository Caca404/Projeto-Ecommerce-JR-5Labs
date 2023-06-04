@extends('layouts.app')

@section('title', 'Meus Favoritos')

@section('content')
    <div class="container">
        <h3>Meus Favoritos</h3>
        <hr class="mb-5 mt-3">
        <div class="row g-3">
            @if(count($produtos))
                @foreach ($produtos as $produto)
                    <div class="col-12 col-md-8 mx-auto">
                        <div class="card">
                            <div class="card-body row">

                                <img class="col-12 col-md-2" src="{{$produto->imagems->last()->path}}" alt="">
                                
                                <div class="col-12 col-md-6 mt-2 mt-md-0">
                                    <div>
                                        <h4 style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
                                            {{ucfirst($produto->name)}}
                                        </h4>
                                    </div>
                                    <h5 class="fw-normal">R$ {{number_format($produto->price, 2, ',', '.')}}</h5>
                                    <span class="fst-italic">
                                        Vendido por:
                                        <i class="fa-solid fa-user text-secondary"></i>
                                        {{$produto->vendedor->user->name}}
                                    </span>
                                </div>
                                <div class="col-12 col-md-4 mt-3 mt-md-0">
                                    <a href="/produto/{{$produto->id}}" 
                                        class="btn btn-outline-dark w-100 p-2">
                                        Ver Produto
                                    </a>
                                    
                                    @if($comprador->carrinhos()->where('produto_id', $produto->id)->count() > 0)
                                        <a href="/comprador/remove-carrinho/{{ $produto->id }}" 
                                            class="btn btn-dark w-100 mt-3 p-2">

                                            <i class="fa-solid fa-trash"></i>
                                            Remover do carrinho
                                        </a>
                                    @else
                                        <a href="/comprador/add-carrinho/{{ $produto->id }}" 
                                            class="btn btn-light border shadow-sm w-100 mt-3 p-2">
            
                                            <i class="fa-solid fa-cart-shopping"></i>
                                            Adicionar ao carrinho
                                        </a>
                                    @endif

                                    <a href="/desfavoritar/{{ $produto->id }}" 
                                        class="btn btn-secondary w-100 mt-3 p-2">

                                        <i class="fa-solid fa-trash"></i>
                                        Desfavoritar
                                    </a>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12 col-md-8 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <span>
                                Nenhum produto foi favoritado! Clique no link abaixo para
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