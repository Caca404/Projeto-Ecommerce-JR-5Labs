@extends('layouts.app')

@section('title', 'Minhas vendas')

@section('content')
    <div class="container">
        <h3>Minhas vendas</h3>
        <hr class="mb-5 mt-3">
        <div class="row g-3">
            @if(count($vendas))
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

                                    <img class="col-12 col-md-2" src="{{$produto->imagems->last()->path}}" alt="">
                                    
                                    <div class="col-12 col-md-6 mt-2 mt-md-0">
                                        <div>
                                            <h4 style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
                                                {{ucfirst($produto->name)}}
                                            </h4>
                                        </div>
                                        <h5 class="fw-normal">R$ {{number_format($venda->pivot->cost, 2, ',', '.')}}</h5>
                                        <span class="fst-italic">
                                            Comprador por:
                                            <i class="fa-solid fa-user text-secondary"></i>
                                            {{$venda->user->name}}
                                        </span>
                                        <div class="mt-2">
                                            <span>Avaliação</span>
                                            <div class="star-wrapper text-start mb-4">
                                                @for ($i = 5; $i >= 1; $i--)
                                                    <i class="fas fa-star s{{$i}}
                                                        {{
                                                            $venda->pivot->rating == $i ? 'active' : ''
                                                        }}
                                                    "></i>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4 mt-3 mt-md-0">
                                        <a href="/produto/seeComentaries/{{$produto->id}}" 
                                            class="btn btn-outline-dark w-100 p-2 mb-3">
                                            Ver Produto
                                        </a>
                                        <a href="/produto/edit/{{$produto->id}}" class="btn btn-warning w-100 p-2">
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