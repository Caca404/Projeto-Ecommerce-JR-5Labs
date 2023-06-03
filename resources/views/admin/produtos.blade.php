@extends('layouts.app')

@section('title', 'Produtos')

@section('content')
<div class="container">
    @error('message')
        <div class="alert alert-success" role="alert">
            {{ $message }}
        </div>
    @enderror
    <h3>Produtos</h3>
    <hr class="mb-5 mt-3">
    <div class="card col-12 col-md-10 mx-auto mb-5" id="filtrosProdutosFromAdmin">
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
                <div class="col-12 col-md-4">

                    <label for="name">Nome</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                        id="name" name="name" placeholder="Pesquisar nome do comprador" 
                        value="{{app('request')->input('name')}}">
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                </div>
                <div class="col-12 col-md-4 row g-2">
                    <div class="col-12 col-md-6 mb-3">

                        <label for="minPrice">Menor Preço</label>
                        <input type="number" class="form-control @error('minPrice') is-invalid @enderror" 
                            min="0" id="minPrice"name="minPrice"
                            value="{{app('request')->input('minPrice') ?? 0}}">
                        @error('minPrice')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <label for="maxPrice">Maior Preço</label>
                        <input type="number" class="form-control @error('maxPrice') is-invalid @enderror" 
                            min="0" max="100000" id="maxPrice" name="maxPrice" 
                            value="{{app('request')->input('maxPrice') ?? 0}}">

                        @error('maxPrice')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <label for="category">Categoria</label>
                    <select name="category" id="category" multiple>
                        @foreach ($categorias as $categoria)
                            <option value="{{$categoria}}" 
                            {{
                                !empty(app('request')->input('categories')) ? 
                                (in_array($categoria, json_decode(app('request')->input('categories'))) ? 'selected' : '') 
                                : ''
                            }}>
                                {{$categoria}}
                            </option>
                        @endforeach
                    </select>
                    @error('categories')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-12 col-md-4">
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
                <div class="col-12">
                    <button class="btn btn-secondary w-100">Filtrar</button>
                </div>
            </form>
        </div>
    </div>
    <div class="table-responsive col-12 col-md-10 mx-md-auto">
        <table class="table table-bordered table-striped">
            <thead>
                <th>Nome</th>
                <th>Preço</th>
                <th>Categoria</th>
            </thead>
            <tbody>
                @if(count($produtos))
                    @foreach ($produtos as $produto)
                        <tr>
                            <td>{{$produto->name}}</td>
                            <td>R$ {{ number_format($produto->price, 2, ',', '.')}}</td>
                            <td>{{$produto->category}}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" class="text-center">
                            <b>
                                Não tem produtos.
                            </b>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection