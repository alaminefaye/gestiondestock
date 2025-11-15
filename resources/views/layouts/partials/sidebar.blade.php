<!-- [ Sidebar Menu ] start -->
<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header flex items-center py-4 px-6 h-header-height">
            <a href="{{ route('dashboard') }}" class="b-brand flex items-center gap-3">
                <img src="{{ asset('assets/images/logo-white.svg') }}" class="img-fluid logo logo-lg" alt="logo" />
                <img src="{{ asset('assets/images/favicon.svg') }}" class="img-fluid logo logo-sm" alt="logo" />
            </a>
        </div>
        <div class="navbar-content h-[calc(100vh_-_74px)] py-2.5">
            <ul class="pc-navbar">
                <li class="pc-item pc-caption">
                    <label>Navigation</label>
                </li>
                <li class="pc-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="pc-link">
                        <span class="pc-micon">
                            <i data-feather="home"></i>
                        </span>
                        <span class="pc-mtext">Dashboard</span>
                    </a>
                </li>
                
                <li class="pc-item pc-caption">
                    <label>Gestion</label>
                    <i data-feather="box"></i>
                </li>
                <li class="pc-item {{ request()->routeIs('produits.*') ? 'active' : '' }}">
                    <a href="{{ route('produits.index') }}" class="pc-link">
                        <span class="pc-micon">
                            <i data-feather="package"></i>
                        </span>
                        <span class="pc-mtext">Produits</span>
                    </a>
                </li>
                <li class="pc-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <a href="{{ route('categories.index') }}" class="pc-link">
                        <span class="pc-micon">
                            <i data-feather="grid"></i>
                        </span>
                        <span class="pc-mtext">Catégories</span>
                    </a>
                </li>
                <li class="pc-item {{ request()->routeIs('fournisseurs.*') ? 'active' : '' }}">
                    <a href="{{ route('fournisseurs.index') }}" class="pc-link">
                        <span class="pc-micon">
                            <i data-feather="truck"></i>
                        </span>
                        <span class="pc-mtext">Fournisseurs</span>
                    </a>
                </li>
                
                <li class="pc-item pc-caption">
                    <label>Mouvements</label>
                    <i data-feather="activity"></i>
                </li>
                <li class="pc-item {{ request()->routeIs('mouvements.*') || request()->routeIs('entrees.*') ? 'active' : '' }}">
                    <a href="{{ route('mouvements.create', ['type' => 'entree']) }}" class="pc-link">
                        <span class="pc-micon">
                            <i data-feather="arrow-down-circle"></i>
                        </span>
                        <span class="pc-mtext">Entrées Stock</span>
                    </a>
                </li>
                <li class="pc-item {{ request()->routeIs('sorties.*') ? 'active' : '' }}">
                    <a href="{{ route('mouvements.create', ['type' => 'sortie']) }}" class="pc-link">
                        <span class="pc-micon">
                            <i data-feather="arrow-up-circle"></i>
                        </span>
                        <span class="pc-mtext">Sorties Stock</span>
                    </a>
                </li>
                <li class="pc-item {{ request()->routeIs('mouvements.index') ? 'active' : '' }}">
                    <a href="{{ route('mouvements.index') }}" class="pc-link">
                        <span class="pc-micon">
                            <i data-feather="list"></i>
                        </span>
                        <span class="pc-mtext">Historique</span>
                    </a>
                </li>
                
                <li class="pc-item pc-caption">
                    <label>Rapports</label>
                    <i data-feather="bar-chart"></i>
                </li>
                <li class="pc-item {{ request()->routeIs('rapports.mouvements-stock') ? 'active' : '' }}">
                    <a href="{{ route('rapports.mouvements-stock') }}" class="pc-link">
                        <span class="pc-micon">
                            <i data-feather="trending-up"></i>
                        </span>
                        <span class="pc-mtext">Mouvements de Stock</span>
                    </a>
                </li>
                
                <li class="pc-item pc-caption">
                    <label>Paramètres</label>
                    <i data-feather="settings"></i>
                </li>
                <li class="pc-item {{ request()->routeIs('utilisateurs.*') ? 'active' : '' }}">
                    <a href="{{ route('utilisateurs.index') }}" class="pc-link">
                        <span class="pc-micon">
                            <i data-feather="users"></i>
                        </span>
                        <span class="pc-mtext">Utilisateurs</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- [ Sidebar Menu ] end -->
