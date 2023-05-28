@extends('layouts.app')

@section('title', 'Criar conta')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form class="row" method="POST" action="{{ route('register') }}">
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
                                    <input id="cpf" type="text" class="form-control @error('cpf') is-invalid @enderror" 
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
                                        <option value="AC">Acre</option>
                                        <option value="AL">Alagoas</option>
                                        <option value="AP">Amapá</option>
                                        <option value="AM">Amazonas</option>
                                        <option value="BA">Bahia</option>
                                        <option value="CE">Ceará</option>
                                        <option value="DF">Distrito Federal</option>
                                        <option value="ES">Espirito Santo</option>
                                        <option value="GO">Goiás</option>
                                        <option value="MA">Maranhão</option>
                                        <option value="MT">Mato Grosso</option>
                                        <option value="MS">Mato Grosso do Sul</option>
                                        <option value="MG">Minas Gerais</option>
                                        <option value="PA">Pará</option>
                                        <option value="PB">Paraíba</option>
                                        <option value="PR">Paraná</option>
                                        <option value="PE">Pernambuco</option>
                                        <option value="PI">Piauí</option>
                                        <option value="RJ">Rio de Janeiro</option>
                                        <option value="RN">Rio Grande do Norte</option>
                                        <option value="RS">Rio Grande do Sul</option>
                                        <option value="RO">Rondônia</option>
                                        <option value="RR">Roraima</option>
                                        <option value="SC">Santa Catarina</option>
                                        <option value="SP">São Paulo</option>
                                        <option value="SE">Sergipe</option>
                                        <option value="TO">Tocantins</option>
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection