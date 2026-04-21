@role('superadmin')
<li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
    <a class="sidebar-link" href="{{ route('dashboard') }}">
        <i class="align-middle" data-feather="sliders"></i>
        <span class="align-middle">Dashboard</span>
    </a>
</li>
<li class="sidebar-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
    <a class="sidebar-link" href="{{ route('admin.users.index') }}">
        <i class="align-middle" data-feather="users"></i>
        <span class="align-middle">Usuarios</span>
    </a>
</li>
@endrole