@extends('layouts.site.app')   
@section('content')
    <div class="container mt-5 pt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Welcome to {{ config('app.name') }}</div>

                    <div class="card-body">
                        <p class="mb-0">This is the welcome page. You can log in or register to access the dashboard.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection