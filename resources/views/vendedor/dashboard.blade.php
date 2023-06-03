@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    @if (Auth::user()->vendedor->status == "A" && !empty($produtos))
        <div class="card col-12 col-md-8 mx-auto" id="filtrosProdutos">
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
                        <div class="col-12">
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
                        <div class="col-12">
                            <label for="order">Ordenar</label>
                            <select name="order" id="order" 
                                class="form-control @error('order') is-invalid @enderror">
                                <option value="name-asc" 
                                    {{
                                        empty(app('request')->input('order')) ? 'selected' :  
                                            (app('request')->input('order') == 'name-asc' ? 'selected' : '')
                                    }}>
                                    A-Z
                                </option>
                                <option value="name-desc"
                                    {{
                                        empty(app('request')->input('order')) ? '' :  
                                            (app('request')->input('order') == 'name-desc' ? 'selected' : '')
                                    }}>
                                    Z-A
                                </option>
                                <option value="price-asc" 
                                    {{
                                        empty(app('request')->input('order')) ? '' :  
                                            (app('request')->input('order') == 'price-asc' ? 'selected' : '')
                                    }}>
                                    Mais Barato
                                </option>
                                <option value="price-desc" 
                                    {{
                                        empty(app('request')->input('order')) ? '' :  
                                            (app('request')->input('order') == 'price-desc' ? 'selected' : '')
                                    }}>
                                    Mais Caro
                                </option>
                            </select>
                            @error('order')
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
        <a href="/produto" class="btn btn-primary mt-3 mb-5 col-12 col-md-3 offset-md-7">
            <i class="fa-solid fa-plus"></i>
            Adicionar produtos
        </a>
    @endif

    <div class="row align-cards g-3">
        @if (Auth::user()->vendedor->status == "P")
            <div class="col-12 col-md-8 mx-md-auto">
                <div class="card bg-warning">
                    <div class="card-body">
                        {{ __('O administrador irá avaliar seu perfil para usar o site.') }}
                    </div>
                </div>
            </div>
        @elseif (Auth::user()->vendedor->status == "R")   
            <div class="col-md-8">
                <div class="card bg-warning">
                    <div class="card-body">
                        {{ __('Infelizmente, você não poderá usar o site, pois rejeitado.') }}
                    </div>
                </div>
            </div>
        @elseif (count($produtos))    
            @foreach ($produtos as $produto)
                <div class="col-12 col-md-3 mb-3 mb-md-0 d-flex align-items-stretch">
                    <a href="/produto/edit/{{$produto->id}}" class="text-dark text-decoration-none w-100">
                        <div class="card h-100">
                            @if($produto->imagems->count())
                                <div class="flex-fill d-flex">
                                    <img src="{{$produto->imagems->last()->path}}" 
                                        alt="Imagem produto" class="card-img-top">
                                </div>
                            @endif
                            <div class="card-body text-center">
                                <h5>{{ucfirst($produto->name)}}</h5>
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
                                
                                <div class="mt-3">
                                    <span>{{$numeroAvaliacoes}} avaliações</span>
                                </div>
                                <div class="star-wrapper mb-3">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <i class="fas fa-star s{{$i}}
                                            {{
                                                $mediaAvaliacoes == $i ? 'active' : ''
                                            }}
                                        "></i>
                                    @endfor
                                </div>

                                <div>
                                    <i class="fa-solid fa-eye"></i>
                                    {{$produto->visualization ? $produto->visualization : 0}}
                                </div>
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
                            <button onclick="location.href='/produto'" class="btn btn-warning mt-4">
                                Cadastrar Novo Produto
                            </button>
                        </div>
                    </div>
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
