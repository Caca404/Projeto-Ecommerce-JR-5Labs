@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    <h3>Bem vindo ao Project Ecommerce!</h3>
    <hr class="@if(empty(Auth::user()->email_verified_at)) mb-3 @else mb-5 @endif mt-3">
    @if (empty(Auth::user()->email_verified_at))
        <div class="alert alert-warning mb-5 text-dark d-flex" role="alert">
            <span>
                @if(empty(Auth::user()->has_verified_email_before))
                    Foi enviado ao seu email a validação dele. 
                    <b>Se validar você ganhará R$ 10.000 em créditos. </b> 
                @endif
                Caso queira reenviar o link de verificação clique no seguinte link:
            </span>
            <form class="ms-2" id="sendEmailVerification" action="{{ route('verification.send') }}" method="post">
                @csrf

                <a class="text-dark" onclick="document.querySelector('#sendEmailVerification').submit()" 
                    href="#">Reenviar</a>
            </form> 
        </div>
    @endif
    <div class="card mb-5 col-12 col-md-8 mx-auto" id="filtrosProdutos">
        <a class="text-decoration-none text-white" data-bs-toggle="collapse" href="#collapseExample" role="button" 
            aria-expanded="false" aria-controls="collapseExample">

            <div class="card-header rounded-top bg-dark p-3">
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
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                        id="name" name="name" placeholder="Pesquisar nome do produto" 
                        value="{{app('request')->input('name')}}">
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                </div>
                <div class="col-12 col-md-6 row g-3">
                    <div class="col-12 mb-3">

                        <label for="smallerPrice">Menor preço</label>
                        <input type="number" class="form-control @error('smallerPrice') is-invalid @enderror" 
                            min="0" id="smallerPrice" name="smallerPrice" 
                            value="{{app('request')->input('smallerPrice') ?? 0}}">
                        @error('smallerPrice')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>
                    <div class="col-12 mb-3">

                        <label for="biggerPrice">Maior preço</label>
                        <input type="number" class="form-control @error('biggerPrice') is-invalid @enderror" 
                            min="0" max="1000000" id="biggerPrice" name="biggerPrice" 
                            value="{{app('request')->input('biggerPrice') ?? 0}}">

                        @error('biggerPrice')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>
                </div>
                <div class="col-12 col-md-6 row g-3">
                    <div class="mb-3">
                        <label for="category">Categorias</label>
                        <select name="category[]" id="category" multiple>
                            @foreach ($categorias as $categoria)
                                <option value="{{$categoria}}" 
                                {{
                                    !empty(app('request')->input('category')) ? 
                                    (in_array($categoria, json_decode(app('request')->input('category')[0])) ? 'selected' : '') 
                                    : ''
                                }}>
                                    {{$categoria}}
                                </option>
                            @endforeach
                        </select>
                        @error('category')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-12">
                    <button class="btn btn-secondary w-100">Filtrar</button>
                </div>
            </form>
        </div>
    </div>
    <div class="row align-cards g-3">
        @if (!empty($produtos))
            @foreach ($produtos as $produto)
                <div class="col-12 col-md-3 mb-3 mb-md-0 d-flex align-items-stretch">
                    <a href="/produto/{{$produto->id}}" class="text-dark text-decoration-none w-100">
                        <div class="card h-100 shadow-sm" title="{{ucfirst($produto->name)}}">
                            @if($produto->imagems->count())
                                <div class="flex-fill d-flex">
                                    <img src="{{$produto->imagems->last()->path}}" 
                                        alt="Imagem produto" class="card-img-top">
                                </div>
                            @endif
                            <div class="card-body text-center">
                                <h5 style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
                                    {{ucfirst($produto->name)}}
                                </h5>
                                <span>R$ {{ number_format($produto->price, 2, ',', '.')}}</span>

                                @php
                                    $mediaAvaliacoes = 0;
                                    $numeroAvaliacoes = 0;
                                    
                                    $avaliacoes = $produto->avaliacoes()
                                        ->where('produto_id', $produto->id)
                                        ->get();

                                    foreach ($avaliacoes as $avaliacao) {
                                        $mediaAvaliacoes += $avaliacao->pivot->rating;
                                        $numeroAvaliacoes++;
                                    }

                                    if($numeroAvaliacoes > 0)
                                        $mediaAvaliacoes = round($mediaAvaliacoes/$numeroAvaliacoes);
                                @endphp
                                
                                <div class="star-wrapper mt-3">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <i class="fas fa-star s{{$i}}
                                            {{
                                                $mediaAvaliacoes == $i ? 'active' : ''
                                            }}
                                        "></i>
                                    @endfor
                                </div>
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
