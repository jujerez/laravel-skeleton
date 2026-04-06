@extends('layouts.auth.app')

@section('title', 'Sign Up — ' . config('app.name'))

@section('content')
    <div class="text-center mt-4">
        <h1 class="h2">¡Comencemos!</h1>
        <p class="lead">Crea tu cuenta para continuar</p>
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

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label" for="name">Nombre completo</label>
                        <input
                            id="name"
                            class="form-control form-control-lg @error('name') is-invalid @enderror"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Introduce tu nombre completo"
                            required
                            autofocus
                        />
                    </div>

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

                    <div class="mb-3">
                        <label class="form-label" for="password_confirmation">Confirmar contraseña</label>
                        <input
                            id="password_confirmation"
                            class="form-control form-control-lg"
                            type="password"
                            name="password_confirmation"
                            placeholder="Confirma tu contraseña"
                            required
                        />
                    </div>

                    <div class="d-grid gap-2 mt-3">
                        <button type="submit" class="btn btn-lg btn-primary">Registrarse</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="text-center mb-3">
        ¿Ya tienes una cuenta? <a href="{{ route('login') }}">Inicia sesión</a>
    </div>
@endsection
