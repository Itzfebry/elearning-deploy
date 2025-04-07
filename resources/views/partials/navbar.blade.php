<nav id="navbar-main" class="navbar is-fixed-top">
    <div class="navbar-brand">
        <a class="navbar-item mobile-aside-button">
            <span class="icon"><i class="mdi mdi-forwardburger mdi-24px"></i></span>
        </a>
    </div>
    <div class="navbar-brand is-right">
        <a class="navbar-item --jb-navbar-menu-toggle" data-target="navbar-menu">
            <span class="icon"><i class="mdi mdi-dots-vertical mdi-24px"></i></span>
        </a>
    </div>
    <div class="navbar-menu" id="navbar-menu">
        <div class="navbar-end">
            <div class="navbar-item dropdown has-divider has-user-avatar">
                <a class="navbar-link">
                    <div class="user-avatar">
                        <img src="{{ asset('template/img/user.jpg') }}" class="rounded-full">
                    </div>
                    <div class="is-user-name red"><span>{{ Auth::user()->admin->nama }}</span></div>
                    <span class="icon text-white"><i class="mdi mdi-chevron-down"></i></span>
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <div class="navbar-dropdown">
                        <hr class="navbar-divider">
                        <button type="submit" class="navbar-item">
                            <span class="icon"><i class="mdi mdi-logout"></i></span>
                            <span>Log Out</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</nav>