@extends('layouts.app')

@section('title', $produto->name)

@section('content')
    <div class="container">
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
        @error('id')
            <div class="alert alert-danger" role="alert">
                <b>{{ $message }}</b>
            </div>
        @enderror
        <div class="row g-3">
            <div class="col-12 col-md-8 mb-3 mb-md-0">
                <div id="carouselExampleControlsNoTouching" class="carousel slide" data-bs-touch="true" 
                    data-bs-interval="false">
                    <div class="carousel-inner">
                        @foreach ($produto->imagems as $imagem)
                            <div
                                class="carousel-item bg-secondary 
                                    {{ $loop->index == $produto->imagems->count() - 1 ? 'active' : '' }}">

                                <img src="{{ $imagem->path }}" class="d-block">
                            </div>
                        @endforeach
                    </div>
                    @if(count($produto->imagems) > 1)
                        <button class="carousel-control-prev" type="button"
                            data-bs-target="#carouselExampleControlsNoTouching" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button"
                            data-bs-target="#carouselExampleControlsNoTouching" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    @endif
                </div>
                <div class="row mt-3 g-3">
                    @foreach ($produto->imagems as $image)
                        <div class="col-4 d-flex align-items-stretch">
                            <button type="button" data-bs-target="#carouselExampleControlsNoTouching" data-bs-slide-to="{{$loop->index}}"
                                class="btn border shadow-sm w-100" aria-current="true" aria-label="Slide {{$loop->index+1}}">
                                <img width="70" src="{{$image->path}}" alt="img {{$loop->index+1}}">
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-12 col-md-4 mb-3 mb-md-0 d-flex flex-column justify-content-between">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <span class="fst-italic">
                            <i class="fa-solid fa-store text-secondary"></i>
                            {{ $produto->vendedor->user->name }}
                        </span>

                        <h2 class="mt-3">{{ ucfirst($produto->name) }}</h2>
                        <h5 class="mb-4">R$ {{ number_format($produto->price, 2, ',', '.') }}</h5>

                        <span>Avaliação do público - {{$numeroAvaliacoes}} pessoa(s)</span>
                        <div class="star-wrapper text-start mb-4">
                            @for ($i = 5; $i >= 1; $i--)
                                <i class="fas fa-star s{{$i}}
                                    {{
                                        $mediaAvaliacoes == $i ? 'active' : ''
                                    }}
                                "></i>
                            @endfor
                        </div>

                        <span class="fw-bold">Categoria:</span>
                        {{ ucfirst($produto->category) }}

                        <h6 class="mt-4 mb-1">O que você precisa saber sobre o produto</h6>
                        <p class="mb-1">
                            {{ $produto->description }}
                        </p>
                    </div>
                    @if(Auth::user()->type == 'comprador')
                        <div class="card-footer bg-white mt-1">
                            <form action="/buy/{{ $produto->id }}" method="GET">
                                @csrf
                                <button class="btn btn-warning w-100 p-2">
                                    <i class="fa-solid fa-dollar-sign"></i>
                                    Comprar agora
                                </button>
                            </form>
                            @if($isInShoppingCart)
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
                            
                            
                            @if($isFavorited)
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
                    @elseif(Auth::user()->type == 'vendedor')
                        <div class="card-footer bg-white mt-1">
                            <a href="/produto/edit/{{$produto->id}}" 
                                class="btn btn-warning w-100 p-2">

                                Editar Produto
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <hr class="mb-4 mt-5">
        <div class="row g-3">
            <h4>{{count($comentarios)}} comentários</h4>
            <div class="col-12 col-lg-6 mt-2 mb-4">
                <div class="border rounded shadow-sm p-3 bg-light position-sticky" style="top: 10px">
                    <form action="/{{Auth::user()->type}}/comentary/{{$produto->id}}" method="post" class="text-end">
                        @csrf

                        <div class="mb-3">
                            <textarea class="form-control @error('email') is-invalid @enderror" 
                                name="comentary" id="comentary" 
                                cols="30" rows="1" required
                                placeholder="Adicione seu comentário"
                                style="max-height: 150px" maxlength="3000"
                                >{{old('comentary')}}</textarea>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-25">Enviar</button>
                    </form>
                </div>
            </div>
            <div class="col-12 col-lg-6 mt-md-2">
                <div class="shadow-sm border rounded p-3 bg-light">
                    @if(count($comentarios))
                        @foreach($comentarios as $comentario)
                            <div class="card {{$loop->index == 0 ? '' : 'mt-3'}}">
                                <div class="card-header d-flex 
                                    justify-content-between bg-white fst-italic">
                                    <div>
                                        <i class="fa-solid fa-user text-secondary"></i>
                                        {{$comentario->name}}
                                    </div>
                                    <div>
                                        <i class="fa-solid fa-clock text-secondary"></i>
                                        {{
                                            date("d/m/Y H:i", strtotime($comentario->pivot->created_at))
                                        }}
                                    </div>
                                </div>
                                <div class="card-body p-2 px-4">
                                    {{$comentario->pivot->comentary}}
                                </div>
                            </div>
                        @endforeach
                    @else
                        <h6>Esse produto não tem comentários no momento.</h6>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
