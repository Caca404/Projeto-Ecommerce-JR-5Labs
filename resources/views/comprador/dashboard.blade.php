@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    <h2>Bem vindo ao Project Ecommerce!</h2>
    <hr class="mb-5 mt-3">
    <div class="row g-3">
        @if (!empty($produtos))    
            @foreach ($produtos as $produto)
                <div class="col-12 col-md-3 mb-3 mb-md-0">
                    <a href="/produto/{{$produto->id}}" class="text-dark text-decoration-none">
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
            <div class="card col-md-8 bg-danger text-white">
                <div class="card-body">
                    <h4>Ops! Estamos sem produtos no momento.</h4>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
