@extends('layouts.auth.app')

@section('title', 'Iniciar sesión — ' . config('app.name'))

@section('content')
    <div class="text-center mt-4">
        <h1 class="h2">¡Bienvenido de nuevo!</h1>
        <p class="lead">Inicia sesión en tu cuenta para continuar</p>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="m-sm-3">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label" for="email">Correo electrónico</label>
                        <input
                            id="email"
                            class="form-control form-control-lg @error('email') is-invalid @enderror"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Introduce tu correo electrónico"
                            required
                            autofocus
                        />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="password">Contraseña</label>
                        <input
                            id="password"
                            class="form-control form-control-lg @error('password') is-invalid @enderror"
                            type="password"
                            name="password"
                            placeholder="Introduce tu contraseña"
                            required
                        />
                    </div>

                    <div>
                        <div class="form-check align-items-center">
                            <input id="remember" type="checkbox" class="form-check-input" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label text-small" for="remember">Recuérdame</label>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-3">
                        <button type="submit" class="btn btn-lg btn-primary">Iniciar sesión</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="text-center mb-3">
        ¿No tienes una cuenta? <a href="{{ route('register') }}">Regístrate</a>
    </div>
@endsection
