@extends('layouts.app')

@section('title', 'Dashboard')

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
                        <button type="button" data-bs-target="#carouselExampleControlsNoTouching" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#carouselExampleControlsNoTouching" data-bs-slide-to="1" aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#carouselExampleControlsNoTouching" data-bs-slide-to="2" aria-label="Slide 3"></button>
                      </div>
                    <div class="carousel-inner">
                      <div class="carousel-item active">
                        <img src="https://picsum.photos/600/400" class="d-block w-100" alt="...">
                      </div>
                      <div class="carousel-item">
                        <img src="https://picsum.photos/600/400" class="d-block w-100" alt="...">
                      </div>
                      <div class="carousel-item">
                        <img src="https://picsum.photos/600/400" class="d-block w-100" alt="...">
                      </div>
                    </div>
                    <button class="carousel-control-prev d-none d-md-flex" type="button" data-bs-target="#carouselExampleControlsNoTouching" data-bs-slide="prev">
                      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                      <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next d-none d-md-flex" type="button" data-bs-target="#carouselExampleControlsNoTouching" data-bs-slide="next">
                      <span class="carousel-control-next-icon" aria-hidden="true"></span>
                      <span class="visually-hidden">Next</span>
                    </button>
                  </div>
            </div>
            <div class="col-12 col-md-4 mb-3 mb-md-0 d-flex flex-column justify-content-between">
                <div class="card">
                    <div class="card-body">
                        <span class="fst-italic">
                            <i class="fa-solid fa-store text-secondary"></i>
                            {{ $produto->vendedor->user->name }}
                        </span>

                        <h2 class="mt-3">{{ ucfirst($produto->name) }}</h2>
                        <h5 class="mb-4">R$ {{ number_format($produto->price, 2, ',', '.') }}</h5>

                        <span class="fw-bold">Categoria:</span> 
                        {{ucfirst($produto->category)}}

                        <h6 class="mt-4 mb-1">O que você precisa saber sobre o produto</h6>
                        <p class="mb-1">
                            {{$produto->description}}
                        </p>
                    </div>
                    <div class="card-footer bg-white mt-1">
                        <form action="/buy/{{$produto->id}}" method="GET">
                            @csrf
                            <button class="btn btn-warning w-100 p-2">Comprar agora</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
