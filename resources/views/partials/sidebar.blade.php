<aside class="aside is-placed-left is-expanded">
    <div class="aside-tools">
        <div>
            E-Elearning <b class="font-black">SD</b>
        </div>
    </div>
    <div class="menu is-menu-main">
        <p class="menu-label">General</p>
        <ul class="menu-list">
            <li class="--set-active-index-html">
                <a href="{{ url('/') }}">
                    <span class="icon"><i class="mdi mdi-desktop-mac"></i></span>
                    <span class="menu-item-label">Dashboard</span>
                </a>
            </li>
        </ul>
        <p class="menu-label">Master</p>
        <ul class="menu-list">
            <li class="{{ Request::is('tahun-ajaran') || Request::is('tahun-ajaran/*') ? 'active' : '' }}">
                <a href="{{ route('tahun-ajaran') }}">
                    <span class="icon"><i class="mdi mdi-lock"></i></span>
                    <span class="menu-item-label">Tahun Ajaran</span>
                </a>
            </li>
            <li class="{{ Request::is('kelas') || Request::is('kelas/*') ? 'active' : '' }}">
                <a href="{{ route('kelas') }}">
                    <span class="icon"><i class="mdi mdi-lock"></i></span>
                    <span class="menu-item-label">Kelas</span>
                </a>
            </li>
            <li class="{{ Request::is('guru') || Request::is('guru/*') ? 'active' : '' }}">
                <a href="{{ route('guru') }}">
                    <span class="icon"><i class="mdi mdi-lock"></i></span>
                    <span class="menu-item-label">Guru</span>
                </a>
            </li>
            <li class="{{ Request::is('siswa') || Request::is('siswa/*') ? 'active' : '' }}">
                <a href="{{ route('siswa') }}">
                    <span class="icon"><i class="mdi mdi-lock"></i></span>
                    <span class="menu-item-label">Siswa</span>
                </a>
            </li>
            <li class="{{ Request::is('wali-kelas') || Request::is('wali-kelas/*') ? 'active' : '' }}">
                <a href="{{ route('wali-kelas') }}">
                    <span class="icon"><i class="mdi mdi-lock"></i></span>
                    <span class="menu-item-label">Wali Kelas</span>
                </a>
            </li>
            <li class="{{ Request::is('admin') || Request::is('admin/*') ? 'active' : '' }}">
                <a href="{{ route('admin') }}">
                    <span class="icon"><i class="mdi mdi-lock"></i></span>
                    <span class="menu-item-label">Admin</span>
                </a>
            </li>
            <li class="{{ Request::is('mata-pelajaran') || Request::is('mata-pelajaran/*') ? 'active' : '' }}">
                <a href="{{ route('mata-pelajaran') }}">
                    <span class="icon"><i class="mdi mdi-lock"></i></span>
                    <span class="menu-item-label">Mata Pelajaran</span>
                </a>
            </li>
            {{-- <li>
                <a class="dropdown">
                    <span class="icon"><i class="mdi mdi-view-list"></i></span>
                    <span class="menu-item-label">Submenus</span>
                    <span class="icon"><i class="mdi mdi-plus"></i></span>
                </a>
                <ul>
                    <li>
                        <a href="#">
                            <span>Sub-item One</span>
                        </a>
                    </li>
                </ul>
            </li> --}}
        </ul>
    </div>
</aside>