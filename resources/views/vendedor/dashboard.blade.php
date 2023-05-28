@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (Auth::user()->type == "vendedor" && Auth::user()->vendedor->status != "P")
                        {{ __('O administrador irá avaliar seu perfil para usar o site.') }}
                    @else



                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
