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
                @if (Auth::user()->role == "admin")
                <a href="{{ url('/') }}">
                    <span class="icon"><i class="mdi mdi-desktop-mac"></i></span>
                    <span class="menu-item-label">Dashboard</span>
                </a>
                @else
                <a href="{{ route('dashboard.guru') }}">
                    <span class="icon"><i class="mdi mdi-desktop-mac"></i></span>
                    <span class="menu-item-label">Dashboard</span>
                </a>
                @endif

            </li>
        </ul>

        @if (Auth::user()->role == "admin")
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
            @else
            <p class="menu-label">OPERASIONAL</p>
            <ul class="menu-list">
                <li class="{{ Request::is('materi') || Request::is('materi/*') ? 'active' : '' }}">
                    <a href="{{ route('materi') }}">
                        <span class="icon"><i class="mdi mdi-lock"></i></span>
                        <span class="menu-item-label">Materi</span>
                    </a>
                </li>
                <li class="{{ Request::is('tugas') || Request::is('tugas/*') ? 'active' : '' }}">
                    <a href="{{ route('tugas') }}">
                        <span class="icon"><i class="mdi mdi-lock"></i></span>
                        <span class="menu-item-label">Tugas</span>
                    </a>
                </li>
                <li class="{{ Request::is('quiz') || Request::is('quiz/*') ? 'active' : '' }}">
                    <a href="{{ route('quiz') }}">
                        <span class="icon"><i class="mdi mdi-lock"></i></span>
                        <span class="menu-item-label">Quiz</span>
                    </a>
                </li>
            </ul>
            </p>
            @endif
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