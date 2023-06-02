@extends('layouts.app')

@section('title', 'Criar conta')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="border-bottom pb-3 mb-3">Crie sua conta</h4>
                    <form class="row mb-4" method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3 col-12 col-md-6">
                            <label for="name" class="col-form-label">{{ __('Nome') }}</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                                name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3 col-12 col-md-6">
                            <label for="email" class="col-form-label">{{ __('Email') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                name="email" value="{{ old('email') }}" required autocomplete="email">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3 col-12 col-md-6">
                            <label for="password" class="col-form-label">{{ __('Senha') }}</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                name="password" required autocomplete="new-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3 col-12 col-md-6">
                            <label for="password-confirm" class="col-form-label">{{ __('Confirmar Senha') }}</label>
                            <input id="password-confirm" type="password" class="form-control 
                                @error('password_confirmation') is-invalid @enderror" 
                                name="password_confirmation" required autocomplete="new-password">

                            @error('password_confirmation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input @error('typeUser') is-invalid @enderror" type="radio" id="comprador" 
                                    name="typeUser" value="comprador" {{ old('typeUser') == 'comprador' ? 'checked' : '' }}>

                                <label class="form-check-label" for="comprador">
                                    {{ __('Comprador') }}
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input @error('typeUser') is-invalid @enderror" type="radio" id="vendedor"  
                                    required name="typeUser" value="vendedor" {{ old('typeUser') == 'vendedor' ? 'checked' : '' }}>

                                <label class="form-check-label" for="vendedor">
                                    {{ __('Vendedor') }}
                                </label>
                            </div>

                            @error('typeUser')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div id="compradorForm" class="{{ old('typeUser') == 'comprador' ? '' : 'd-none' }} mb-3">
                            <hr class="mb-2">

                            <div class="row">
                                <div class="mb-3 col-12 col-md-6">
                                    <label for="cpf" class="col-form-label">{{ __('CPF') }}</label>
                                    <input id="cpf" type="text" max="11" class="form-control @error('cpf') is-invalid @enderror" 
                                        value="{{ old('cpf') }}" name="cpf" {{ old('typeUser') == 'comprador' ? 'required' : '' }} 
                                        autocomplete="cpf">

                                    @error('cpf')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
    
                                <div class="mb-3 col-12 col-md-6">
                                    <label for="birth_date" class="col-form-label">{{ __('Data de Nascimento') }}</label>
                                    <input id="birth_date" type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                                        name="birth_date" value="{{ old('birth_date') }}" 
                                        {{ old('typeUser') == 'comprador' ? 'required' : '' }}>

                                    @error('birth_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3 col-12 col-md-6">
                                    <label for="state" class="col-form-label">{{ __('Estado') }}</label>
                                    <select name="state" id="state" class="form-control @error('state') is-invalid @enderror"
                                        {{ old('typeUser') == 'comprador' ? 'required' : '' }}>

                                        <option value="" disabled selected>Selecione um estado</option>

                                        @foreach ($states as $sigla => $state)
                                            <option value="{{$sigla}}" {{old('state') == $sigla ? 'selected' : ''}}>
                                                {{$state}}
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
                                        @error('city') is-invalid @enderror" value="{{ old('city') }}"
                                        {{ old('typeUser') == 'comprador' ? 'required' : '' }}>

                                    @error('city')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-0 col-12">
                            <button type="submit" class="btn btn-primary col-12">
                                {{ __('Criar Conta') }}
                            </button>
                        </div>
                    </form>
                    <form class="border-top mt-3 pt-4 text-center" action="/login" method="get">
                        <h6>Já tem Login? Entre por aqui</h6>
                        <button class="btn btn-secondary col-12 mt-3">Logar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection