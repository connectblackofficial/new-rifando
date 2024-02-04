<!-- Add icons to the links using the .nav-icon class
           with font-awesome or any other icon font library -->
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>
            Painel
        </p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('home') }}" class="nav-link" id="home">
        <i class="far fa-circle nav-icon"></i>
        <p>Home</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('profile') }}" class="nav-link" id="perfil">
        <i class="far fa-circle nav-icon"></i>
        <p>Meu perfil</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('admin.products.index') }}"
       class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}"
       id="meus-sorteios">
        <i class="far fa-clone nav-icon"></i>
        <p>Minhas Rifas</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('admin.customers.index') }}"
       class="nav-link {{ request()->is('admin.customers.*') ? 'active' : '' }}"
       id="clientes">
        <i class="fas fa-users"></i>
        <p>Clientes</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('painel.ganhadores') }}"
       class="nav-link {{ request()->is('admin-ganhadores*') ? 'active' : '' }}"
       id="meus-sorteios">
        <i class="fas fa-trophy nav-icon"></i>
        <p>Ganhadores</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('wpp.index') }}"
       class="nav-link {{ request()->is('wpp-mensagens*') ? 'active' : '' }}"
       id="wpp-msgs">
        <i class="fab fa-whatsapp"></i>
        <p>Whatsapp mensagens</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('tutoriais') }}"
       class="nav-link {{ request()->is('tutoriais*') ? 'active' : '' }}"
       id="wpp-msgs">
        <i class="fas fa-list"></i>
        <p>Tutoriais</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('afiliados') }}"
       class="nav-link {{ request()->is('lista-afiliados*') ? 'active' : '' }}"
       id="wpp-msgs">
        <i class="fas fa-people-arrows"></i>
        <p>Afiliados</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('painel.solicitacaoAfiliados') }}"
       class="nav-link {{ request()->is('solicitacao-pagamento*') ? 'active' : '' }}"
       id="wpp-msgs">
        <i class="fas fa-dollar-sign"></i>
        <p>Solicitação de Pgto</p>
    </a>
</li>