@extends('layouts.withoutNav')

@section('title', "Login")

@section('content')
<div class="container">
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    <h2 class="text-center mb-5">{{ config('app.name', 'Laravel') }}</h2>
    <div class="row justify-content-center">
        <div class="col-12 col-md-4">
            <div class="card">
                <div class="card-body">
                    <h4>Login</h4>
                    <form method="POST" action="/login">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="col-form-label">{{ __('Email') }}</label>
                            <input id="email" type="email" class="form-control 
                                @error('email') is-invalid @enderror" 
                                name="email" value="{{ old('email') }}" 
                                autocomplete="email" autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="col-form-label">{{ __('Senha') }}</label>
                            <input id="password" type="password" class="form-control 
                                @error('password') is-invalid @enderror" name="password" 
                                autocomplete="current-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" 
                                    id="remember" {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label" for="remember">
                                    {{ __('Lembrar de mim') }}
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <a href="/forgot-password">Esqueceu sua senha?</a>
                        </div>

                        <div class="mb-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary col-12">
                                    {{ __('Login') }}
                                </button>
                            </div>
                        </div>
                    </form>
                    <form action="/register" method="GET">
                        <div class="border-top">
                            <div class="col-12 text-center pt-3">
                                <small>Não tem conta? Se registre agora.</small>
                                <button type="submit" class="btn btn-secondary col-12 mt-3">
                                    {{ __('Criar Conta') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
