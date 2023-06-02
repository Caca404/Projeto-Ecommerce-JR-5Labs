@extends('layouts.app')

@section('title', 'Verificação de Email')

@section('content')
<div class="container">
    <h3>Verificação de Email</h3>
    <hr class=" @if (session('message')) mb-3 @else mb-5 @endif mt-3">
    @if (session('message'))
        <div class="alert alert-success mb-5" role="alert">
            {{ __('Um novo link de verificação foi enviado para o seu endereço de e-mail.') }}
        </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    {{ __('Antes de prosseguir, verifique seu e-mail para obter um link de verificação.') }}
                    {{ __('Se você não recebeu o e-mail') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('clique aqui para solicitar outro') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
