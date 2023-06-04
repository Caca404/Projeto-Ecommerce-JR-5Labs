@extends('layouts.app')

@section('title', 'Vendedores')

@section('content')
<div class="container">
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    @error('message')
        <div class="alert alert-danger" role="alert">
            {{ $message }}
        </div>
    @enderror
    <h3>Vendedores</h3>
    <hr class="mb-5 mt-3">
    <div class="card col-12 col-md-10 mx-auto mb-5" id="filtrosVendedorFromAdmin">
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

                        <label for="minCredit">Menor crédito</label>
                        <input type="number" class="form-control @error('minCredit') is-invalid @enderror" 
                            min="0" id="minCredit"name="minCredit"
                            value="{{app('request')->input('minCredit') ?? 0}}">
                        @error('minCredit')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <label for="maxCredit">Maior crédito</label>
                        <input type="number" class="form-control @error('maxCredit') is-invalid @enderror" 
                            min="0" max="1000000" id="maxCredit" name="maxCredit" 
                            value="{{app('request')->input('maxCredit') ?? 0}}">

                        @error('maxCredit')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <label for="status">Status</label>
                    <select name="status" id="status" 
                        class="form-control @error('status') is-invalid @enderror">

                        <option value="" {{empty(app('request')->input('status')) ? 'selected' : ''}}>
                            Selecione um status
                        </option>
                        <option value="A" {{app('request')->input('status') == 'A' ? 'selected': ''}}>
                            Aceito
                        </option>
                        <option value="P" {{app('request')->input('status') == 'P' ? 'selected': ''}}>
                            Pendente
                        </option>
                        <option value="R" {{app('request')->input('status') == 'R' ? 'selected': ''}}>
                            Rejeitado
                        </option>
                    </select>
                    @error('status')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-12 col-md-4">
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
                <th>Créditos</th>
                <th>Status</th>
                <th>Ação</th>
            </thead>
            <tbody>
                @if(count($vendedores))
                    @foreach ($vendedores as $vendedor)
                        <tr>
                            <td>{{$vendedor->user->name}}</td>
                            <td>R$ {{ number_format($vendedor->credits, 2, ',', '.')}}</td>
                            <td>
                                {{
                                    $vendedor->status == 'P' ? 'Pendente' : (
                                        $vendedor->status == "A" ? 'Aceito' : "Rejeitado"
                                    )
                                }}
                            </td>
                            @if($vendedor->status == "P")
                                <td class="d-flex flex-wrap justify-content-evenly">
                                    <form action="/admin/decisionStatusVendedor/{{$vendedor->id}}/A" method="get">
                                        <button class="btn btn-success">
                                            <i class="fa-solid fa-check"></i>
                                            Aprovar
                                        </button>
                                    </form>
                                    <form action="/admin/decisionStatusVendedor/{{$vendedor->id}}/R" method="get">
                                        <button class="btn btn-danger mt-md-0 mt-2">
                                            <i class="fa-solid fa-xmark"></i>
                                            Rejeitar
                                        </button>
                                    </form>
                                </td>
                            @elseif($vendedor->status == "A")

                                <td class="d-flex flex-wrap justify-content-center">
                                    <form action="/admin/decisionStatusVendedor/{{$vendedor->id}}/R" method="get">
                                        <button class="btn btn-danger mt-md-0 mt-2">
                                            <i class="fa-solid fa-xmark"></i>
                                            Rejeitar
                                        </button>
                                    </form>
                                </td>

                            @else
                                <td class="d-flex flex-wrap justify-content-center">
                                    <form action="/admin/decisionStatusVendedor/{{$vendedor->id}}/A" method="get">
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