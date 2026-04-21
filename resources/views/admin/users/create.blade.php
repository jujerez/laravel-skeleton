@extends('layouts.admin.app')

@section('title', 'Nuevo usuario — ' . config('app.name'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0"><strong>Nuevo</strong> Usuario</h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="align-middle" data-feather="arrow-left"></i> Volver
        </a>
    </div>

    <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data" data-callback="formCallback">
        @csrf

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row align-items-start">
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-body">
                        @include('admin.users._form')
                        <button type="submit" class="btn btn-primary">Crear usuario</button>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4 mt-3 mt-lg-0">
                @include('admin.users._avatar')
            </div>
        </div>
    </form>
@endsection
@push('scripts')
<script>
function formCallback(data) {
    CommonFunctions.notificationSuccessStayOrBack(
        data.message,
        data.entryUrl,
        "{{ route('admin.users.index') }}"
    );
}
</script>
@endpush
