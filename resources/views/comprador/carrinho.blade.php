@extends('layouts.app')

@section('title', 'Meu Carrinho de Compras')

@section('content')
    <div class="container">
        <h3>Meu Carrinho de Compras</h3>
        <hr class="mb-4 mt-3">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        @error('credits')
            <div class="alert alert-danger" role="alert">
                <b>{{ $message }}</b>
            </div>
        @enderror
        <div class="row g-3 flex-column-reverse flex-md-row">
            @if(count($carrinho))
                <div class="row g-3 mx-0 col-12 col-md-8">
                    @foreach ($carrinho as $produto)
                        <div class="col-12 px-0">
                            <div class="card shadow-sm">
                                <div class="card-body row">
    
                                    <img class="col-12 col-md-2"
                                        src="{{$produto->imagems->last()->path}}" alt="">
                                    
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
                                        <a href="/produto/{{$produto->id}}" class="btn btn-outline-dark w-100 p-2">
                                            Ver Produto
                                        </a>
                                        <a href="/comprador/remove-carrinho/{{ $produto->id }}" 
                                                class="btn btn-dark w-100 mt-3 p-2">
                                            <i class="fa-solid fa-trash"></i>
                                            Remover do carrinho
                                        </a>
                                        @if($produto->compradors_favorito()->where('comprador_id', $comprador->id)->count() > 0)
                                            <a href="/desfavoritar/{{ $produto->id }}" 
                                                class="btn btn-secondary w-100 mt-3 p-2">

                                                <i class="fa-solid fa-trash"></i>
                                                Desfavoritar
                                            </a>
                                        @else
                                            <a href="/favoritar/{{ $produto->id }}" 
                                                class="btn btn-orange w-100 mt-3 p-2">

                                                <i class="fa-solid fa-star"></i>
                                                Favoritar
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="col-12 col-md-4">
                    <div class="card shadow-sm position-sticky mt-3" style="top: 10px">
                        <div class="card-body">
                            <h4>Compra Total</h4>
                            <hr class="mt-2 mb-3">
                            <ul class="p-0 mb-0" style="list-style-type: none">
                                @php $total = 0 @endphp
                                @foreach($carrinho as $produto)
                                    @php $total += $produto->price @endphp
                                    <li class="d-flex justify-content-between">
                                        <span class="text-secondary" 
                                            style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis;width: 65%">
                                            {{ucfirst($produto->name)}}
                                        </span>
                                        <span class="fw-normal">
                                           + R$ {{number_format($produto->price, 2, ',', '.')}}
                                        </span>
                                    </li>
                                @endforeach
                                <li class="d-flex justify-content-between border-top mt-2 pt-2">
                                    <span>
                                        <b>Total</b>
                                    </span>
                                    <span class="fw-bold">
                                       + R$ {{number_format($total, 2, ',', '.')}}
                                    </span>
                                </li>
                            </ul>
                        </div>
                        <div class="card-footer bg-white">
                            <form class="my-3" action="/comprador/clean-carrinho" method="get">
                                @csrf

                                <button class="btn btn-outline-dark w-100">
                                    Esvaziar Carrinho de Compras
                                </button>
                            </form>
                            <form action="/comprador/buy-carrinho" method="get">
                                @csrf

                                <button class="btn btn-warning w-100">
                                    Comprar tudo
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-12 col-md-8 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <span>
                                Nenhum produto foi colocado dentro do carrinho de compras! 
                                Clique no link abaixo para voltar aos produtos.
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