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
                                <div class="col-12 col-md-4 mt-3 mt-md-0 row align-items-center">
                                    <a href="/produto/{{$produto->id}}" class="btn btn-outline-dark w-100 p-3">
                                        Ver Produto
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