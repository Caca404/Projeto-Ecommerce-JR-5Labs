@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    <div class="row g-3">
        @if (Auth::user()->type == "vendedor" && Auth::user()->vendedor->status == "P")
            <div class="col-md-8">
                <div class="card bg-warning">
                    <div class="card-body">
                        {{ __('O administrador irá avaliar seu perfil para usar o site.') }}
                    </div>
                </div>
            </div>
        @elseif (!empty($produtos))    
            @foreach ($produtos as $produto)
                <div class="col-12 col-md-3 mb-3 mb-md-0">
                    <a href="/produto/edit/{{$produto->id}}" class="text-dark text-decoration-none">
                        <div class="card">
                            @if($produto->imagems->count())
                                <img src="/images/products/{{$produto->imagems->last()->name.'.'.$produto->imagems->last()->mime}}" 
                                    alt="Imagem produto" class="card-img-top" width="70">
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
            <div class="col-12 col-md-8">
                <a href="#" class="text-decoration-none text-dark">
                    <div class="card">
                        <div class="card-body">
                            <h4>
                                Olá, vendedor. Deseja cadastrar seus produtos para começar
                                sua venda?
                            </h4>
                            <button class="btn btn-warning mt-4">Cadastrar Novo Produto</button>
                        </div>
                    </div>
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
