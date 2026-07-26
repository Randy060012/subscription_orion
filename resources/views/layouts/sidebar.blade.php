<nav class="col-md-3 col-lg-2 d-md-block sidebar collapse p-3">
    <!-- LOGO ORION TECHNOLOGIES -->
    <div class="d-flex align-items-center mb-4 px-2 text-white text-decoration-none">
        <!-- <img src="#" alt="Orion Technologies Logo" class="img-fluid me-2" style="max-height: 25px;"> -->
        <span class="fs-6 fw-bold">ORION ADMIN</span>
    </div>

    <hr class="text-secondary opacity-25">

    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}"
                class="nav-link {{ request()->routeIs('dashboard*') ? 'active' : '' }}"
                {!! request()->routeIs('dashboard*') ? 'aria-current="page"' : '' !!}>
                <i class="fa-solid fa-house me-2"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="{{ route('index.agences') }}"
                class="nav-link {{ request()->routeIs('*.agences*') || request()->routeIs('agences*') ? 'active' : '' }}"
                {!! request()->routeIs('*agences*') ? 'aria-current="page"' : '' !!}>
                <i class="fa-solid fa-building me-2"></i> Agences
            </a>
        </li>
        <li>
            <a href="{{ route('index.tarifs') }}"
                class="nav-link {{ request()->routeIs('*.tarifs*') || request()->routeIs('tarifs*') ? 'active' : '' }}"
                {!! request()->routeIs('*tarifs*') ? 'aria-current="page"' : '' !!}>
                <i class="fa-solid fa-tags me-2"></i> Tarifs
            </a>
        </li>
        <li>
            <a href="{{ route('index.subscriptions') }}"
                class="nav-link {{ request()->routeIs('*.subscriptions*') || request()->routeIs('subscriptions*') ? 'active' : '' }}"
                {!! request()->routeIs('*subscriptions*') ? 'aria-current="page"' : '' !!}>
                <i class="fa-solid fa-file-signature me-2"></i> Souscriptions
            </a>
        </li>
        <li>
            <a href="{{ route('index.tickets') }}"
                class="nav-link {{ request()->routeIs('*.tickets*') || request()->routeIs('tickets*') ? 'active' : '' }}"
                {!! request()->routeIs('*tickets*') ? 'aria-current="page"' : '' !!}>
                <i class="fa-solid fa-ticket me-2"></i> Tickets
            </a>
        </li>
    </ul>

    <hr class="text-secondary opacity-25 mt-auto">

    <div class="dropdown px-2">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa-solid fa-circle-user fs-4 me-2"></i>
            <strong>Administrateur</strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser">
            <li><a class="dropdown-item" href="{{route('index.profil')}}">Profil</a></li>
            <li><a class="dropdown-item" href="#">Paramètres</a></li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <!-- <li><a class="dropdown-item" href="#">Déconnexion</a></li> -->
            <li>
                <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger w-100 text-start border-0 bg-transparent">
                        <i class="fa-solid fa-right-from-bracket me-2"></i>Déconnexion
                    </button>
                </form>
            </li>
        </ul>
    </div>
</nav>