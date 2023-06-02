@extends('layouts.app')

@section('title', $produto->name)

@section('content')
    <div class="container">
        @error('credits')
            <div class="alert alert-danger" role="alert">
                <b>{{ $message }}</b>
            </div>
        @enderror
        <div class="row g-3">
            <div class="col-12 col-md-8 mb-3 mb-md-0">
                <div id="carouselExampleControlsNoTouching" class="carousel slide" data-bs-interval="false">
                    <div class="carousel-indicators">
                        @foreach ($produto->imagems as $imagem)
                            <button type="button" data-bs-target="#carouselExampleControlsNoTouching"
                                data-bs-slide-to="{{ $loop->index }}" class="active" aria-current="true"
                                aria-label="Slide {{ $loop->index + 1 }}"></button>
                        @endforeach
                    </div>
                    <div class="carousel-inner">
                        @foreach ($produto->imagems as $imagem)
                            <div
                                class="carousel-item bg-secondary 
                                    {{ $loop->index == $produto->imagems->count() - 1 ? 'active' : '' }}">

                                <img src="{{ $imagem->path }}" class="d-block w-75 mx-auto">
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev d-none d-md-flex" type="button"
                        data-bs-target="#carouselExampleControlsNoTouching" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next d-none d-md-flex" type="button"
                        data-bs-target="#carouselExampleControlsNoTouching" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
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

                        <span class="fw-bold">Categoria:</span>
                        {{ ucfirst($produto->category) }}

                        <h6 class="mt-4 mb-1">O que você precisa saber sobre o produto</h6>
                        <p class="mb-1">
                            {{ $produto->description }}
                        </p>
                    </div>
                    <div class="card-footer bg-white mt-1">
                        <form action="/buy/{{ $produto->id }}" method="GET">
                            @csrf
                            <button class="btn btn-warning w-100 p-2">
                                <i class="fa-solid fa-dollar-sign"></i>
                                Comprar agora
                            </button>
                        </form>
                        
                        @if($isFavorited)
                            <form class="mt-3" action="/favoritar/{{ $produto->id }}/N" method="GET">
                                @csrf
                                <button class="btn btn-orange w-100 p-2">
                                    Desfavoritar
                                </button>
                            </form>
                        @else
                            <form class="mt-3" action="/favoritar/{{ $produto->id }}/S" method="GET">
                                @csrf
                                <button class="btn btn-orange w-100 p-2">
                                    <i class="fa-solid fa-star"></i>
                                    Favoritar
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
