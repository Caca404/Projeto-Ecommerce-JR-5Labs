@extends('layouts.app')

@section('title', 'Vendedores')

@section('content')
<div class="container">
    @error('message')
        <div class="alert alert-success" role="alert">
            {{ $message }}
        </div>
    @enderror
    <h3>Vendedores</h3>
    <hr class="mb-5 mt-3">
    <div class="table-responsive col-12 col-md-10 mx-md-auto">
        <table class="table table-bordered table-striped">
            <thead>
                <th>Nome</th>
                <th>Créditos</th>
                <th>Status</th>
                <th>Ação</th>
            </thead>
            <tbody>
                @if(!empty($vendedores))
                    @foreach ($vendedores as $user)
                        <tr>
                            <td>{{$user->name}}</td>
                            <td>R$ {{ number_format($user->vendedor->credits, 2, ',', '.')}}</td>
                            <td>
                                {{
                                    $user->vendedor->status == 'P' ? 'Pendente' : (
                                        $user->vendedor->status == "A" ? 'Aceito' : "Rejeitado"
                                    )
                                }}
                            </td>
                            @if($user->vendedor->status == "P")
                                <td class="d-flex flex-wrap justify-content-evenly">
                                    <form action="/admin/decisionStatusVendedor/{{$user->id}}/A" method="get">
                                        <button class="btn btn-success">
                                            <i class="fa-solid fa-check"></i>
                                            Aprovar
                                        </button>
                                    </form>
                                    <form action="/admin/decisionStatusVendedor/{{$user->id}}/R" method="get">
                                        <button class="btn btn-danger mt-md-0 mt-2">
                                            <i class="fa-solid fa-xmark"></i>
                                            Rejeitar
                                        </button>
                                    </form>
                                </td>
                            @elseif($user->vendedor->status == "A")

                                <td>
                                    <form action="/admin/decisionStatusVendedor/{{$user->id}}/R" method="get">
                                        <button class="btn btn-danger mt-md-0 mt-2">
                                            <i class="fa-solid fa-xmark"></i>
                                            Rejeitar
                                        </button>
                                    </form>
                                </td>

                            @else
                                <td>
                                    <form action="/admin/decisionStatusVendedor/{{$user->id}}/A" method="get">
                                        <button class="btn btn-success">
                                            <i class="fa-solid fa-check"></i>
                                            Aprovar
                                        </button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" class="text-center">
                            <b>
                                Não tem vendedores.
                            </b>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection