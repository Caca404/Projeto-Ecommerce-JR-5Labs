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
    <div class="table-responsive col-12 col-md-10 mx-md-auto">
        <table class="table table-bordered table-striped">
            <thead>
                <th>Nome</th>
                <th>Preço</th>
                <th>Categoria</th>
            </thead>
            <tbody>
                @if(!empty($produtos))
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