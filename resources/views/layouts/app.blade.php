<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{ config('app.name', 'Laravel') }}</title>

    <script src="https://kit.fontawesome.com/d2d910e8a2.js" crossorigin="anonymous"></script>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    {{-- <ul class="navbar-nav me-auto">

                    </ul> --}}

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        @auth
                            @if (Auth::user()->type == "admin")
                                <li class="nav-item dropdown">
                                    <a class="nav-link 
                                        {{ Route::currentRouteName() == 'admin/compradores' ? 'active' : ''}}" 
                                        href="/admin/compradores">

                                        Compradores
                                    </a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link
                                        {{ Route::currentRouteName() == 'admin/vendedores' ? 'active' : ''}}" 
                                        href="/admin/vendedores">

                                        Vendedores
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link
                                        {{ Route::currentRouteName() == 'admin/produtos' ? 'active' : ''}}" 
                                        href="/admin/produtos">

                                        Produtos
                                    </a>
                                </li>

                            @elseif(Auth::user()->type == 'comprador')
                                <li class="nav-item text-secondary">
                                    <a href="#" class="nav-link d-flex">
                                        <i class="fa-solid fa-user me-2"></i>
                                        <h6 class="mb-0">R$ {{ number_format(Auth::user()->comprador->credits, 2, ',', '.')}}</h6>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link
                                        {{ Route::currentRouteName() == 'comprador/minhas-compras' ? 'active' : ''}}" 
                                        href="/comprador/minhas-compras">

                                        Minhas Compras
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link
                                        {{ Route::currentRouteName() == 'comprador/meus-favoritos' ? 'active' : ''}}" 
                                        href="/comprador/meus-favoritos">

                                        Meus favoritos
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link
                                        {{ Route::currentRouteName() == 'comprador/perfil' ? 'active' : ''}}" 
                                        href="/comprador/perfil">

                                        Perfil
                                    </a>
                                </li>
                            @else
                                <li class="nav-item text-secondary">
                                    <a href="#" class="nav-link d-flex">
                                        <i class="fa-solid fa-user me-2"></i>
                                        <h6 class="mb-0">R$ {{ number_format(Auth::user()->vendedor->credits, 2, ',', '.')}}</h6>
                                    </a>
                                </li>
                                @if(Auth::user()->vendedor->status == 'A')
                                    <li class="nav-item">
                                        <a class="nav-link
                                            {{ Route::currentRouteName() == 'vendedor/minhas-vendas' ? 'active' : ''}}" 
                                            href="/vendedor/minhas-vendas">

                                            Minhas Vendas
                                        </a>
                                    </li>
                                @endif
                            @endif
                            <li class="nav-item">
                                <a class="nav-link" href="/logout"
                                    onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                    {{ __('Sair') }}
                                </a>

                                <form id="logout-form" action="/logout" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
