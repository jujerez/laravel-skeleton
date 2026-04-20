 <div class="mb-3">
    <label class="form-label" for="name">Nombre *</label>
    <input
        id="name"
        class="form-control @error('name') is-invalid @enderror"
        type="text"
        name="name"
        value="{{ old('name', $user->name ?? '') }}"
        required
        autofocus
    />
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label" for="username">Usuario</label>
    <input
        id="username"
        class="form-control @error('username') is-invalid @enderror"
        type="text"
        name="username"
        value="{{ old('username', $user->username ?? '') }}"
        placeholder="solo letras, números y _"
    />
    @error('username')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label" for="email">Correo electrónico *</label>
    <input
        id="email"
        class="form-control @error('email') is-invalid @enderror"
        type="email"
        name="email"
        value="{{ old('email', $user->email ?? '') }}"
        required
    />
    @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label" for="phone">Teléfono</span></label>
    <input
        id="phone"
        class="form-control @error('phone') is-invalid @enderror"
        type="tel"
        name="phone"
        value="{{ old('phone', $user->phone ?? '') }}"
    />
    @error('phone')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label" for="password">
        Contraseña
        @isset($user)
            <span class="text-muted">(dejar en blanco para no cambiar)</span>
        @endisset
    </label>
    <input
        id="password"
        class="form-control @error('password') is-invalid @enderror"
        type="password"
        name="password"
        @isset($user) @else required @endisset
    />
    @error('password')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label" for="password_confirmation">Confirmar contraseña</label>
    <input
        id="password_confirmation"
        class="form-control"
        type="password"
        name="password_confirmation"
        @isset($user) @else required @endisset
    />
</div>

<div class="mb-3">
    <div class="form-check form-switch">
        <input
            class="form-check-input"
            type="checkbox"
            id="is_active"
            name="is_active"
            value="1"
            {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }}
        />
        <label class="form-check-label" for="is_active">Usuario activo</label>
    </div>
</div>

<div class="mb-4">
    <label class="form-label">Roles</label>
    @error('roles')
        <div class="text-danger small mb-1">{{ $message }}</div>
    @enderror
    @php
        $currentRoleIds = old('roles') !== null
            ? array_map('intval', (array) old('roles'))
            : (isset($user) ? $user->roles->pluck('id')->map(fn ($id) => (int) $id)->toArray() : []);
    @endphp
    @foreach ($roles as $role)
        <div class="form-check">
            <input
                class="form-check-input"
                type="checkbox"
                name="roles[]"
                id="role_{{ $role->id }}"
                value="{{ $role->id }}"
                {{ in_array($role->id, $currentRoleIds) ? 'checked' : '' }}
            />
            <label class="form-check-label" for="role_{{ $role->id }}">
                {{ $role->name }}
                @if ($role->team_id)
                    <span class="text-muted small">(equipo)</span>
                @endif
            </label>
        </div>
    @endforeach
</div>
