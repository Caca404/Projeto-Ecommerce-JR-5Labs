@extends('layouts.app')

@section('title', 'Compradores')

@section('content')
<div class="container">
    <h3>Compradores</h3>
    <hr class="mb-5 mt-3">
    <div class="table-responsive col-12 col-md-10 mx-md-auto">
        <table class="table table-bordered table-striped">
            <thead>
                <th>Nome</th>
                <th>Data de Nascimento</th>
                <th>CPF</th>
                <th>Estado</th>
                <th>Créditos</th>
            </thead>
            <tbody>
                @if(!empty($compradores))
                    @foreach ($compradores as $item)
                        <tr>
                            <td>{{$item->name}}</td>
                            <td>{{date('d/m/Y', strtotime($item->comprador->birth_date))}}</td>
                            <td>{{$item->comprador->cpf}}</td>
                            <td>{{$item->comprador->state}}</td>
                            <td>R$ {{ number_format($item->comprador->credits, 2, ',', '.')}}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" class="text-center">
                            <b>
                                Não tem compradores.
                            </b>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection