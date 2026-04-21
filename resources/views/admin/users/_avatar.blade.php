<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Avatar</h5>
    </div>
    <div class="card-body text-center">
        <img
            id="avatar-preview"
            src="{{ isset($user) ? $user->avatarUrl() : asset('assets/static/img/avatars/avatar.jpg') }}"
            class="rounded-circle mb-3"
            style="width: 120px; height: 120px; object-fit: cover;"
            alt="Avatar"
        />

        <div class="d-grid gap-2">
            <label for="avatar" class="btn btn-outline-primary mb-0" role="button">
                <i class="align-middle me-1" data-feather="upload"></i>
                {{ isset($user) ? 'Cambiar avatar' : 'Subir avatar' }}
            </label>
            <input
                id="avatar"
                type="file"
                name="avatar"
                accept="image/jpeg,image/png,image/webp"
                class="d-none"
            />
        </div>

        @error('avatar')
            <div class="text-danger small mt-2">{{ $message }}</div>
        @enderror

        <p class="text-muted small mt-2 mb-0">JPG, PNG o WEBP · máx. 2 MB</p>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('avatar').addEventListener('change', function () {
        if (this.files && this.files[0]) {
            document.getElementById('avatar-preview').src = URL.createObjectURL(this.files[0]);
        }
    });
</script>
@endpush
