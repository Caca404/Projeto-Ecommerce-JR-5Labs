@extends('layouts.app')

@section('title', 'Compradores')

@section('content')
<div class="container">
    <div class="table-responsive">
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
                            <td>{{$item->comprador->birth_date}}</td>
                            <td>{{$item->comprador->cpf}}</td>
                            <td>{{$item->comprador->state}}</td>
                            <td>{{$item->comprador->credits}}</td>
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