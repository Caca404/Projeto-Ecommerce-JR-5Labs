@extends('layouts.app')

@section('title', 'Perfil')

@section('content')
<div class="container">
    <h3 class="mb-4">Perfil</h3>
    <hr class="mb-5 mt-3">
    <div class="row">
        <div class="shadow-sm d-flex flex-row flex-wrap border rounded p-3">
            <div class="flex-row col-12 col-md-8 pe-3">
                <div class="row">
                    <div class="mb-3 col-12 col-md-6">
                        <label for="name" class="col-form-label">{{ __('Nome') }}</label>
                        <input id="name" type="text" class="form-control" name="name" 
                            value="{{ $name }}" readonly>
                    </div>
        
                    <div class="mb-3 col-12 col-md-6">
                        <label for="email" class="col-form-label">{{ __('Email') }}</label>
                        <input id="email" type="email" class="form-control" name="email" 
                            value="{{ $email }}" readonly>
                    </div>
                </div>
                <hr class="my-2">
                <div class="row">
                    <div class="mb-3 col-12 col-md-6">
                        <label for="cpf" class="col-form-label">{{ __('CPF') }}</label>
                        <input id="cpf" type="text" class="form-control" 
                            value="{{ $cpf }}" name="cpf" readonly>
                    </div>

                    <div class="mb-3 col-12 col-md-6">
                        <label for="birth_date" class="col-form-label">{{ __('Data de Nascimento') }}</label>
                        <input id="birth_date" type="date" class="form-control" 
                            name="birth_date" value="{{ $birth_date }}" readonly>
                    </div>

                    <div class="mb-3 col-12 col-md-6">
                        <label for="state" class="col-form-label">{{ __('Estado') }}</label>
                        <input type="text" class="form-control" name="state" 
                            id="state" value="{{ $state }}" readonly>
                    </div>

                    <div class="mb-3 col-12 col-md-6">
                        <label for="city" class="col-form-label">{{ __('Cidade') }}</label>
                        <input type="text" name="city" id="city" value="{{ $city }}"
                            class="form-control" readonly>
                    </div>
                </div>
            </div>
            <div class="flex-row col-12 col-md-4 border-start ps-3">
                <button class="btn btn-primary col-12 mb-3" data-bs-toggle="modal"
                    data-bs-target="#editPerfil">Alterar Perfil</button>

                <button class="btn btn-danger col-12 mb-3" data-bs-toggle="modal"
                    data-bs-target="#editPassword">Alterar Senha</button>
            </div>
        </div>
    </div>
</div>
<div id="modals">
    <div class="modal fade" id="editPerfil" tabindex="-1" aria-labelledby="editPerfilLabel" 
        aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPerfilLabel">Alterar Perfil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/comprador/perfil" method="post">
                        @csrf
                        @method('PATCH')

                        <div class="row">
                            <div class="mb-3 col-12 col-md-6">
                                <label for="name" class="col-form-label">{{ __('Nome') }}</label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                                    name="name" value="{{ old('name') ?? $name }}" required autocomplete="name" autofocus>
    
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
    
                            <div class="mb-3 col-12 col-md-6">
                                <label for="email" class="col-form-label">{{ __('Email') }}</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                    name="email" value="{{ old('email') ?? $email }}" required autocomplete="email">
    
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3 col-12 col-md-6">
                                <label for="cpf" class="col-form-label">{{ __('CPF') }}</label>
                                <input id="cpf" type="text" class="form-control @error('cpf') is-invalid @enderror" 
                                    value="{{ old('cpf') ?? $cpf }}" name="cpf" required autocomplete="cpf">

                                @error('cpf')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3 col-12 col-md-6">
                                <label for="birth_date" class="col-form-label">{{ __('Data de Nascimento') }}</label>
                                <input id="birth_date" type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                                    name="birth_date" value="{{ old('birth_date') ?? $birth_date }}" required>

                                @error('birth_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3 col-12 col-md-6">
                                <label for="state" class="col-form-label">{{ __('Estado') }}</label>
                                <select name="state" id="state" class="form-control @error('state') is-invalid @enderror" required>
                                    <option value="" disabled selected>Selecione um estado</option>

                                    @foreach ($states as $key => $estado)
                                        <option value="{{$key}}" {{ (old('state') ?? $stateUF) == $key ? 'selected' : ''}}>
                                            {{$estado}}
                                        </option>
                                    @endforeach
                                    
                                </select>

                                @error('state')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3 col-12 col-md-6">
                                <label for="city" class="col-form-label">{{ __('Cidade') }}</label>
                                <input type="text" name="city" id="city" class="form-control 
                                    @error('city') is-invalid @enderror" value="{{ old('city') ?? $city }}" required>

                                @error('city')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="document.querySelector('#editPerfil form').submit()" 
                        class="btn btn-primary w-100">Salvar Alterações</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editPassword" tabindex="-1" aria-labelledby="editPasswordLabel" 
        aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPasswordLabel">Alterar Senha</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/updatePassword" method="post">
                        @csrf
    
                        <div class="row">
                            <div class="mb-3 col-12">
                                <label for="old_password" class="col-form-label">{{ __('Senha Atual') }}</label>
                                <input id="old_password" type="password" class="form-control 
                                    @error('old_password') is-invalid @enderror" name="old_password" required>
    
                                @error('old_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3 col-12">
                                <label for="password" class="col-form-label">{{ __('Nova Senha') }}</label>
                                <input id="password" type="password" class="form-control 
                                    @error('password') is-invalid @enderror" name="password" required>
    
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
    
                            <div class="mb-3 col-12">
                                <label for="password-confirm" class="col-form-label">{{ __('Confirmar Nova Senha') }}</label>
                                <input id="password-confirm" type="password" class="form-control 
                                    @error('password_confirmation') is-invalid @enderror" 
                                    name="password_confirmation" required>
    
                                @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="document.querySelector('#editPassword form').submit()" 
                        class="btn btn-danger w-100">Salvar Alterações</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection