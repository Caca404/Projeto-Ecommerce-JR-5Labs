@extends('layouts.app')

@section('title', 'Compradores')

@section('content')
<div class="container">
    <h3>Compradores</h3>
    <hr class="mb-5 mt-3">
    <div class="card col-12 col-md-10 mx-auto mb-5" id="filtrosCompradorFromAdmin">
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
                            min="0" max="100000" id="maxCredit" name="maxCredit" 
                            value="{{app('request')->input('maxCredit') ?? 0}}">

                        @error('maxCredit')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <label for="states">Estado</label>
                    <select name="states" id="states" multiple>
                        @foreach ($states as $sigla => $state)
                            <option value="{{$sigla}}" 
                            {{
                                !empty(app('request')->input('states')) ? 
                                (in_array($sigla, json_decode(app('request')->input('states'))) ? 'selected' : '') 
                                : ''
                            }}>
                                {{$state}}
                            </option>
                        @endforeach
                    </select>
                    @error('states')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-12 col-md-4">
                    <label for="cpf">CPF</label>
                    <input type="text" name="cpf" id="cpf" class="form-control @error('cpf') is-invalid @enderror"
                        value="{{app('request')->input('cpf')}}" maxlength="11">
                    @error('cpf')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-12 col-md-4 row g-2">
                    <div class="col-12 col-md-6 mb-3">

                        <label for="minDate">De</label>
                        <input type="date" class="form-control @error('cpf') is-invalid @enderror" 
                            id="minDate" max="{{date('Y-m-d')}}" name="minDate" 
                            value="{{app('request')->input('minDate')}}">
                        @error('minDate')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <label for="maxDate">Até</label>
                        <input type="date" class="form-control @error('maxDate') is-invalid @enderror" 
                            id="maxDate" name="maxDate" max="{{date('Y-m-d')}}" 
                            value="{{app('request')->input('maxDate')}}">
                        @error('maxDate')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
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
                        <option value="credits-asc" 
                            {{
                                empty(app('request')->input('order')) ? '' :  
                                    (app('request')->input('order') == 'credits-asc' ? 'selected' : '')
                            }}>
                            Menor Crédito
                        </option>
                        <option value="credits-desc" 
                            {{
                                empty(app('request')->input('order')) ? '' :  
                                    (app('request')->input('order') == 'credits-desc' ? 'selected' : '')
                            }}>
                            Maior Crédito
                        </option>
                        <option value="birth_date-desc" 
                            {{
                                empty(app('request')->input('order')) ? '' :  
                                    (app('request')->input('order') == 'birth_date-desc' ? 'selected' : '')
                            }}>
                            Mais novo
                        </option>
                        <option value="birth_date-asc"  
                            {{
                                empty(app('request')->input('order')) ? '' :  
                                    (app('request')->input('order') == 'birth_date-asc' ? 'selected' : '')
                            }}>
                            Mais velho
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
                <th>Data de Nascimento</th>
                <th>CPF</th>
                <th>Estado</th>
                <th>Créditos</th>
            </thead>
            <tbody>
                @if(count($compradores))
                    @foreach ($compradores as $comprador)
                        <tr>
                            <td>{{$comprador->user->name}}</td>
                            <td>{{date('d/m/Y', strtotime($comprador->birth_date))}}</td>
                            <td>{{$comprador->cpf}}</td>
                            <td>{{$comprador->state}}</td>
                            <td>R$ {{ number_format($comprador->credits, 2, ',', '.')}}</td>
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