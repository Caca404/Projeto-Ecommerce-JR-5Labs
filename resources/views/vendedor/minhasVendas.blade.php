@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container">
        <h3>Produtos</h3>
        <hr class="mb-5 mt-3">
        <div class="row g-3">
            @if(!empty($vendas))
                @foreach ($vendas as $produto)
                    @foreach ($produto->compradors as $venda)
                        <div class="col-12 col-md-8 mx-auto">
                            <div class="card">
                                <div class="card-header bg-white text-end">
                                    <span class="fst-italic text-secondary">
                                        Data do Pedido:
                                        <i class="fa-solid fa-clock"></i>
                                        {{date('d/m/Y H:i', strtotime($venda->pivot->created_at))}}
                                    </span>
                                </div>
                                <div class="card-body row">
                                    @if($produto->imagems->count())
                                        <img class="col-12 col-md-2" src="/images/products/{{$produto->imagems->last()->name.'.'.$produto->imagems->last()->mime}}" alt="">
                                    @else
                                        <img class="col-12 col-md-2" src="https://picsum.photos/600/600" alt="">
                                    @endif
                                    
                                    <div class="col-12 col-md-6 mt-2 mt-md-0">
                                        <div>
                                            <h4 style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
                                                {{ucfirst($produto->name)}}
                                            </h4>
                                        </div>
                                        <h5 class="fw-normal">R$ {{number_format($produto->price, 2, ',', '.')}}</h5>
                                        <span class="fst-italic">
                                            Comprador por:
                                            <i class="fa-solid fa-user text-secondary"></i>
                                            {{$venda->user->name}}
                                        </span>
                                    </div>
                                    <div class="col-12 col-md-4 mt-3 mt-md-0 row align-items-center">
                                        <a href="/produto/edit/{{$produto->id}}" class="btn btn-warning w-100 p-3">
                                            Editar Produto
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
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