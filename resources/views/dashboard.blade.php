@extends('layouts.admin.app')

@section('title', 'Dashboard — ' . config('app.name'))

@section('sidebar-nav')
    {{-- <li class="sidebar-header">Pages</li> --}}
    <li class="sidebar-item active">
        <a class="sidebar-link" href="{{ route('dashboard') }}">
            <i class="align-middle" data-feather="sliders"></i>
            <span class="align-middle">Dashboard</span>
        </a>
    </li>
@endsection

@section('content')
    <h1 class="h3 mb-3"><strong>Estadísticas</strong> Dashboard</h1>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <p class="mb-0">¡Bienvenido, {{ auth()->user()->name }}!</p>
                </div>
            </div>
        </div>
    </div>
@endsection
