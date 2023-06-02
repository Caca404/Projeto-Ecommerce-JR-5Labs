@extends('layouts.app')

@section('title', 'Alterar Senha')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-4">
            <div class="card">

                <div class="card-body">
                    <h4>Alterar Senha</h4>
                    <form class="mt-4" method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-3">
                            <label for="email">{{ __('Email') }}</label>
                            <input id="email" type="email" class="form-control 
                                @error('email') is-invalid @enderror" name="email" 
                                value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password">{{ __('Senha') }}</label>
                            <input id="password" type="password" class="form-control 
                                @error('password') is-invalid @enderror" name="password" 
                                required>

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password-confirm">{{ __('Confirmar Senha') }}</label>
                            <input id="password-confirm" type="password" class="form-control
                                @error('password_confirmation') is-invalid @enderror" 
                                name="password_confirmation" required>

                            @error('password_confirmation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <button type="submit" class="btn btn-dark col-12">
                                {{ __('Alterar Senha') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
