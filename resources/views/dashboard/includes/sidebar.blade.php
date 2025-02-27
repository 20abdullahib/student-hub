<!-- Sidebar -->
<nav id="sidebarMenu" class="sidebar d-lg-block bg-gray-800 text-white collapse"
    style="scrollbar-width: none; -ms-overflow-style: none;" data-simplebar="init">
    <div class="sidebar-inner px-4 pt-3 overflow-x-hidden">
        <div class="user-card d-flex d-md-none align-items-center justify-content-between justify-content-md-center pb-4"
            data-bs-boundary="window">
            <div class="d-flex align-items-center">
                <div class="d-block">
                    <h2 class="h5 mb-3">Hi,
                        {{ strtok(auth()->user()->name, ' ') }}

                    </h2>
                    <form id="logout-form" action="{{ route('dashboard.logout') }}" method="POST"
                        style="display: none;">
                        @csrf
                    </form>
                    <a href="# "onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="btn btn-secondary btn-sm d-inline-flex align-items-center">
                        <svg class="icon icon-xxs me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                        Sign Out
                    </a>
                </div>
            </div>
            <div class="collapse-close d-md-none">
                <a href="#sidebarMenu" data-bs-toggle="collapse" data-bs-target="#sidebarMenu"
                    aria-controls="sidebarMenu" aria-expanded="true" aria-label="Toggle navigation">
                    <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </a>
            </div>
        </div>
        <!-- Debug: Print current admin's roles -->
        {{-- <div class="mb-3 text-center text-white">
            <small>Roles: {{ auth()->user()->getRoleNames()->implode(', ') }}</small>
        </div> --}}
        <ul class="nav flex-column pt-3 pt-md-0">
            <!-- Magic Overview -->
            {{-- <li class="nav-item {{ request()->routeIs('dashboard.index') ? 'active' : '' }}"> --}}
            <li class="nav-item {{ route('dashboard.index') }}">
                <a href="{{ route('dashboard.index') }}" class="nav-link d-flex align-items-center">
                    <span class="sidebar-icon">
                        <img src="{{ asset('assets/Dashboard/images/logo-icon-magic.png') }}"
                            style="height:2rem; width:1.5rem;" alt="Magic Logo">
                    </span>
                    <span class="sidebar-text">Magic Overview</span>
                </a>
            </li>
            <!-- Dashboard -->
            <li class="nav-item {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
                <a href="{{ route('dashboard.index') }}" class="nav-link">
                    <span class="sidebar-icon">
                        <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                            <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                        </svg>
                    </span>
                    <span class="sidebar-text">Dashboard</span>
                </a>
            </li>
            <!-- Dropbox Controllers -->
            <li class="nav-item">
                <span
                    class="nav-link d-flex justify-content-between align-items-center {{ request()->is('dashboard/dropbox*') ? 'active' : 'collapsed' }}"
                    data-bs-toggle="collapse" data-bs-target="#Dropbox">
                    <span>
                        <span class="sidebar-icon">
                            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M7.004 3.5L2 6.689l5.004 3.191L2 13.063 7.004 16.5l5.006-3.188L17 16.5l5.004-3.188L17 10.006l5.004-3.187L17 3.5l-4.996 3.187L7.004 3.5zM2 16.689l5.004 3.188 4.996-3.188-5.004-3.187L2 16.689zm10 0l4.996 3.188 5.004-3.188-4.996-3.187L12 16.689z">
                                </path>
                            </svg>
                        </span>
                        <span class="sidebar-text">Dropbox Controllers</span>
                    </span>
                    <span class="link-arrow">
                        <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </span>
                </span>
                    <div class="multi-level collapse {{ request()->is('dashboard/dropbox*') ? 'show' : '' }}" role="list"
                        id="Dropbox" aria-expanded="{{ request()->is('dashboard/dropbox*') ? 'true' : 'false' }}">
                        <ul class="flex-column nav">
                            @hasanyrole('super admin|admin')
                            <li class="nav-item {{ request()->routeIs('dropbox.account.index') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('dropbox.account.index') }}">
                                    <span class="sidebar-text">Accounts</span>
                                </a>
                            </li>
                            <li class="nav-item {{ request()->routeIs('dropbox.account.form') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('dropbox.account.form') }}">
                                    <span class="sidebar-text">New Account</span>
                                </a>
                            </li>
                            @endhasanyrole
                            @hasanyrole('super admin|admin|editor')
                                <li class="nav-item {{ request()->routeIs('dropbox.upload.form') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('dropbox.upload.form') }}">
                                        <span class="sidebar-text">Upload Files</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ request()->routeIs('dropbox.files.index') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('dropbox.files.index') }}">
                                        <span class="sidebar-text">Files</span>
                                    </a>
                                </li>
                            @endrole
                        </ul>
                    </div>
            </li>

            <!-- Admins -->
            @hasanyrole('super admin|admin')
                <li class="nav-item">
                    <span
                        class="nav-link d-flex justify-content-between align-items-center {{ request()->is('dashboard/admin*') ? 'active' : 'collapsed' }}"
                        data-bs-toggle="collapse" data-bs-target="#Admins">
                        <span>
                            <span class="sidebar-icon">
                                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 448 512"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M224 256A128 128 0 1 0 96 128a128 128 0 0 0 128 128zm0 32c-63.6 0-192 32-192 96v48a16 16 0 0 0 16 16h352a16 16 0 0 0 16-16v-48c0-64-128.4-96-192-96zm-32 32h64l32 96h-16l-32-32-32 32h-16z"/>
                                </svg>
                            </span>
                            <span class="sidebar-text">Admins</span>
                        </span>
                        <span class="link-arrow">
                            <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </span>
                    </span>
                    <div class="multi-level collapse {{ request()->is('dashboard/admin*') ? 'show' : '' }}"
                        role="list" id="Admins"
                        aria-expanded="{{ request()->is('dashboard/admin*') ? 'true' : 'false' }}">
                        <ul class="flex-column nav">
                            @hasanyrole('admin|super admin')
                                <li class="nav-item {{ request()->routeIs('admin.index') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('admin.index') }}">
                                        <span class="sidebar-text">Admins</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ request()->routeIs('admin.create') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('admin.create') }}">
                                        <span class="sidebar-text">New Admins</span>
                                    </a>
                                </li>
                                @can('add roles')
                                    <li class="nav-item {{ request()->routeIs('permission.create') ? 'active' : '' }}">
                                        <a class="nav-link" href="{{ route('permission.create') }}">
                                            <span class="sidebar-text">Permissions</span>
                                        </a>
                                    </li>
                                    @endcan
                                    @endhasanyrole
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item {{ request()->routeIs('settings.index') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('settings.index') }}">
                                <span class="sidebar-icon">
                                    <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 460 512"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M495.9 166.6c3.2 8.7 .5 18.4-6.4 24.6l-43.3 39.4c1.1 8.3 1.7 16.8 1.7 25.4s-.6 17.1-1.7 25.4l43.3 39.4c6.9 6.2 9.6 15.9 6.4 24.6c-4.4 11.9-9.7 23.3-15.8 34.3l-4.7 8.1c-6.6 11-14 21.4-22.1 31.2c-5.9 7.2-15.7 9.6-24.5 6.8l-55.7-17.7c-13.4 10.3-28.2 18.9-44 25.4l-12.5 57.1c-2 9.1-9 16.3-18.2 17.8c-13.8 2.3-28 3.5-42.5 3.5s-28.7-1.2-42.5-3.5c-9.2-1.5-16.2-8.7-18.2-17.8l-12.5-57.1c-15.8-6.5-30.6-15.1-44-25.4L83.1 425.9c-8.8 2.8-18.6 .3-24.5-6.8c-8.1-9.8-15.5-20.2-22.1-31.2l-4.7-8.1c-6.1-11-11.4-22.4-15.8-34.3c-3.2-8.7-.5-18.4 6.4-24.6l43.3-39.4C64.6 273.1 64 264.6 64 256s.6-17.1 1.7-25.4L22.4 191.2c-6.9-6.2-9.6-15.9-6.4-24.6c4.4-11.9 9.7-23.3 15.8-34.3l4.7-8.1c6.6-11 14-21.4 22.1-31.2c5.9-7.2 15.7-9.6 24.5-6.8l55.7 17.7c13.4-10.3 28.2-18.9 44-25.4l12.5-57.1c2-9.1 9-16.3 18.2-17.8C227.3 1.2 241.5 0 256 0s28.7 1.2 42.5 3.5c9.2 1.5 16.2 8.7 18.2 17.8l12.5 57.1c15.8 6.5 30.6 15.1 44 25.4l55.7-17.7c8.8-2.8 18.6-.3 24.5 6.8c8.1 9.8 15.5 20.2 22.1 31.2l4.7 8.1c6.1 11 11.4 22.4 15.8 34.3zM256 336a80 80 0 1 0 0-160 80 80 0 1 0 0 160z"/>
                                    </svg>
                                </span>
                                <span class="sidebar-text">Settings</span>
                            </a>
                        </li>
            @endhasanyrole
            {{-- <li role="separator" class="dropdown-divider mt-4 mb-3 border-gray-700"></li> --}}
            {{-- <li class="nav-item">
                <a href="https://themesberg.com/docs/volt-bootstrap-5-dashboard/getting-started/quick-start/"
                    target="_blank" class="nav-link d-flex align-items-center">
                    <span class="sidebar-icon">
                        <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </span>
                    <span class="sidebar-text">Documentation <span
                            class="badge badge-sm bg-secondary ms-1 text-gray-800">v1.4</span></span>
                </a>
            </li> --}}
        </ul>
    </div>
</nav>
