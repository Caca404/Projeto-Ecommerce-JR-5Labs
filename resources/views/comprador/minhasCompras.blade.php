@extends('layouts.app')

@section('title', 'Minhas compras')

@section('content')
    <div class="container">
        <h3>Minhas Compras</h3>
        <hr class="{{session('status') ? 'mb-3' : 'mb-5' }}  mt-3">

        @if (session('status'))
            <div class="alert alert-success mb-5" role="alert">
                {{ session('status') }}
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
                                value="{{app('request')->input('biggerPrice')?? 0}}">
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
                                @foreach ($orderTypes as $orderType => $orderTypeName)
                                    <option value="{{$orderType}}" 
                                        {{
                                            empty(app('request')->input('order')) ? 
                                                ($orderType == 'name' ? 'selected' : '') :  
                                                (app('request')->input('order') == $orderType ? 'selected' : '')
                                        }}>
                                        {{$orderTypeName}}
                                    </option>
                                @endforeach
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
        <div class="row g-3">
            @if(count($compras))
                @foreach ($compras as $produto)
                    <div class="col-12 col-md-8 mx-auto">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white text-end">
                                <span class="fst-italic text-secondary">
                                    Data do Pedido:
                                    <i class="fa-solid fa-clock"></i>
                                    {{date('d/m/Y H:i', strtotime($produto->pivot->created_at))}}
                                </span>
                            </div>
                            <div class="card-body row">
                                <img class="col-12 col-md-2" 
                                    src="{{$produto->imagems->last()->path}}" 
                                    alt="" />

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
                                    <div class="star-wrapper text-start mt-3">
                                        @php 
                                            $avaliacao = $comprador->avaliacoes()
                                                ->where('produto_id', $produto->id)
                                                ->first() 
                                        @endphp

                                        @for ($i = 5; $i >= 1; $i--)
                                            <a href="/rate/{{$produto->id}}/{{$i}}" 
                                                class="fas fa-star s{{$i}}
                                                {{
                                                    !empty($avaliacao) ? ($avaliacao->pivot->rating == $i ? 'active' : '') : ''
                                                }}
                                            "></a>
                                        @endfor
                                    </div>
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
                                            class="btn btn-danger w-100 mt-3 p-2">
                                            Adicionar ao carrinho
                                        </a>
                                    @endif
                                    
                                    
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
            @elseif(!$isRequestEmpty)
                <div class="col-12 col-md-8 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <span>
                                Nenhum pedido está de acordo com o filtro.
                            </span>
                        </div>
                    </div>
                </div>
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