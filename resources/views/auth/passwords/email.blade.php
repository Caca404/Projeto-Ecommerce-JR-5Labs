@extends('layouts.withoutNav')

@section('title', 'Esqueci minha senha')

@section('content')
<div class="container">
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-12 col-md-4">
            <div class="card">
                <div class="card-body">

                    <h4>Esqueci minha senha</h4>
                    <form method="POST" action="/forgot-password" class="mt-4">
                        @csrf

                        <div class="mb-3">
                            <label for="email">{{ __('Email') }}</label>
                            <input id="email" type="email" class="form-control 
                                @error('email') is-invalid @enderror" name="email" 
                                value="{{ old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-dark col-12">
                                {{ __('Mandar o Link de Alteração de Senha') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
