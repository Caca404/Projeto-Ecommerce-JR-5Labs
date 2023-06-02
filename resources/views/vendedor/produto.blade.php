@extends('layouts.app')

@section('title', !empty($produto) ? $produto->name : 'Criar novo produto')

@section('content')
    <div class="container">
        @if (session('status'))
            <div class="alert alert-success mb-3" role="alert">
                {{ session('status') }}
            </div>
        @endif
        <h3 class="mb-4">{{ !empty($produto) ? $produto->name : 'Criar novo produto' }}</h3>
        <hr class="mb-5 mt-3">
        <form action="{{ !empty($produto) ? '/produto/edit/'.$produto->id : '/produto/create' }}" method="POST" 
            class="d-flex flex-row flex-wrap border rounded p-2 shadow-sm
            {{!empty($produto) ? '' : 'col-12 col-md-8 mx-auto'}}" enctype="multipart/form-data">
            @csrf

            @if(!empty($produto))
                <div class="flex-row col-12 col-md-6 p-3">
                    <input type="hidden" name="imagesToRemove[]" value="[]">

                    <div id="carouselExampleIndicators" class="carousel slide" data-bs-interval="false">
                        <div class="carousel-inner">
                            @foreach ($produto->imagems as $image)
                                <div class="carousel-item 
                                    {{$loop->index == 0 ? 'active' : ''}}" 
                                    data-item="{{$loop->index}}">

                                    <img src="{{$image->path}}" 
                                        alt="Imagem produto" class="d-block w-75 mx-auto">
                                </div>
                            @endforeach
                            <div id="hasNotImage" class="carousel-item bg-secondary text-white text-center p-2">
                                <h5>Não tem imagem no banco associado ao produto.</h5>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3 g-3">
                        @foreach ($produto->imagems as $image)
                            <div class="col-4 d-flex align-items-stretch">
                                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{$loop->index}}"
                                    class="btn border shadow-sm" aria-current="true" aria-label="Slide {{$loop->index+1}}">
                                    <img width="70" src="{{$image->path}}" alt="img {{$loop->index+1}}">
                                </button>
                                <i class="fa-solid fa-circle-xmark text-danger withRemove fa-xl" style="height: 0"
                                    onclick="removeImg({{$loop->index}})"></i>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="flex-row col-12 {{ !empty($produto) ? 'col-md-6' : '' }} p-3">
                <div class="row">
                    <div class="mb-3 col-12 col-md-6">
                        <label for="name">Nome</label>
                        <input type="text" class="form-control" name="name" id="name" 
                            value="{{ (old('name') ?? $produto->name ?? '') }}" required>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3 col-12 col-md-6">
                        <label for="price">Preço</label>
                        <input type="number" class="form-control" name="price" id="price" min="1.00"
                            step="0.5" value="{{ (old('price') ?? $produto->price ?? '') }}" required>
                        @error('price')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3 col-12">
                        <label for="category">Categoria</label>
                        <select name="category" id="category" class="form-control" required>
                            <option value="" disabled selected>Selecione uma categoria</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{$categoria}}" 
                                    {{ (old('category') ?? $produto->category ?? '') == $categoria ? 'selected' : '' }}>
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

                    <div class="mb-3 col-12">
                        <label for="images">Novas imagens (no max. 3 imagens contando com as existentes)</label>
                        <input type="file" name="images[]" id="images" accept="image/png, image/jpeg"
                            class="form-control @error('images') is-invalid @enderror"
                            multiple value="{{old('images')}}">
                        @error('images')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3 col-12">
                        <label for="description">Descrição</label>
                        <textarea name="description" id="description" cols="30" rows="5" style="max-height: 200px"
                            class="form-control @error('description') is-invalid @enderror" 
                            required>{{ (old('description') ?? $produto->description ?? '') }}</textarea>

                        @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-12">
                        @if(!empty($produto))
                            <button class="btn btn-warning w-100">Editar produto</button>
                        @else
                            <button class="btn btn-primary w-100">Criar produto</button>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
