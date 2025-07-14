<aside class="aside is-placed-left is-expanded sidebar-animated" style="background: linear-gradient(45deg, #4527A0 0%, #673AB7 50%, #9575CD 100%); box-shadow: 0 4px 25px rgba(0, 0, 0, 0.3);">

    <div class="aside-tools" style="background-color: rgba(0,0,0,0.2); padding: 1.5rem; border-bottom: 1px solid rgba(255, 255, 255, 0.664);">
        <div class="flex items-center justify-center">
            <div class="p-2 rounded-full bg-white shadow-lg logo-container" style="background: linear-gradient(45deg, #7E57C2, #673AB7, #4527A0); padding: 0.2rem;">
                <img src="{{ asset('template/img/logo.png') }}" alt="Logo" style="width: 30px; height: 30px; border-radius: 10px; border: 2px solid rgba(255,255,255,0.8);">
            </div>
            <div class="ml-3">
                <span class="text-lg font-bold text-white" style="text-shadow: 0 2px 4px rgba(0,0,0,0.3);">SkoLearn</span> 
               
            </div>
        </div>
    </div>
    <div class="menu is-menu-main">
        <p class="menu-label text-white opacity-70 px-4 mt-4 mb-2 text-xs font-bold uppercase" style="letter-spacing: 1.5px;">General</p>
        <ul class="menu-list">
            <li class="--set-active-index-html menu-item-animation">
                @if (Auth::user()->role == "admin")
                <a href="{{ url('/') }}" class="sidebar-link hover:bg-indigo-700 {{ Request::is('/') ? 'bg-indigo-800 border-l-4 border-blue-300 active-link' : '' }}" style="transition: all 0.3s ease; border-radius: 0 8px 8px 0; margin: 4px 8px 4px 0;">
                    <span class="icon-container" style="background: rgba(255, 255, 255, 0.189); padding: 10px; border-radius: 10px; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                        <i class="mdi mdi-desktop-mac" style="font-size: 18px;"></i>
                    </span>
                    <span class="menu-item-label ml-3">Dashboard</span>
                </a>
                @else
                <a href="{{ route('dashboard.guru') }}" class="sidebar-link hover:bg-indigo-700 {{ Request::is('dashboard-guru') ? 'bg-indigo-800 border-l-4 border-blue-300 active-link' : '' }}" style="transition: all 0.3s ease; border-radius: 0 8px 8px 0; margin: 4px 8px 4px 0;">
                    <span class="icon-container" style="background: rgba(255,255,255,0.1); padding: 10px; border-radius: 10px; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                        <i class="mdi mdi-desktop-mac" style="font-size: 18px;"></i>
                    </span>
                    <span class="menu-item-label ml-3">Dashboard</span>
                </a>
                @endif
            </li>
        </ul>

        @if (Auth::user()->role == "admin")
        <p class="menu-label text-white opacity-70 px-4 mt-5 mb-2 text-xs font-bold uppercase" style="letter-spacing: 1.5px;">Master</p>
        <ul class="menu-list">
            <li class="menu-item-animation {{ Request::is('tahun-ajaran') || Request::is('tahun-ajaran/*') ? 'active' : '' }}">
                <a href="{{ route('tahun-ajaran') }}" class="sidebar-link hover:bg-indigo-700 {{ Request::is('tahun-ajaran*') ? 'bg-indigo-800 border-l-4 border-blue-300 active-link' : '' }}" style="transition: all 0.3s ease; border-radius: 0 8px 8px 0; margin: 4px 8px 4px 0;">
                    <span class="icon-container" style="background: rgba(255,255,255,0.1); padding: 10px; border-radius: 10px; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                        <i class="mdi mdi-calendar" style="font-size: 18px;"></i>
                    </span>
                    <span class="menu-item-label ml-3">Tahun Ajaran</span>
                </a>
            </li>
            <li class="menu-item-animation {{ Request::is('kelas') || Request::is('kelas/*') ? 'active' : '' }}">
                <a href="{{ route('kelas') }}" class="sidebar-link hover:bg-indigo-700 {{ Request::is('kelas*') ? 'bg-indigo-800 border-l-4 border-blue-300 active-link' : '' }}" style="transition: all 0.3s ease; border-radius: 0 8px 8px 0; margin: 4px 8px 4px 0;">
                    <span class="icon-container" style="background: rgba(255,255,255,0.1); padding: 10px; border-radius: 10px; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                        <i class="mdi mdi-home-variant" style="font-size: 18px;"></i>
                    </span>
                    <span class="menu-item-label ml-3">Kelas</span>
                </a>
            </li>
            <li class="menu-item-animation {{ Request::is('guru') || Request::is('guru/*') ? 'active' : '' }}">
                <a href="{{ route('guru') }}" class="sidebar-link hover:bg-indigo-700 {{ Request::is('guru*') ? 'bg-indigo-800 border-l-4 border-blue-300 active-link' : '' }}" style="transition: all 0.3s ease; border-radius: 0 8px 8px 0; margin: 4px 8px 4px 0;">
                    <span class="icon-container" style="background: rgba(255,255,255,0.1); padding: 10px; border-radius: 10px; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                        <i class="mdi mdi-account-tie" style="font-size: 18px;"></i>
                    </span>
                    <span class="menu-item-label ml-3">Guru</span>
                </a>
            </li>
            <li class="menu-item-animation {{ Request::is('siswa') || Request::is('siswa/*') ? 'active' : '' }}">
                <a href="{{ route('siswa') }}" class="sidebar-link hover:bg-indigo-700 {{ Request::is('siswa*') ? 'bg-indigo-800 border-l-4 border-blue-300 active-link' : '' }}" style="transition: all 0.3s ease; border-radius: 0 8px 8px 0; margin: 4px 8px 4px 0;">
                    <span class="icon-container" style="background: rgba(255,255,255,0.1); padding: 10px; border-radius: 10px; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                        <i class="mdi mdi-account-group" style="font-size: 18px;"></i>
                    </span>
                    <span class="menu-item-label ml-3">Siswa</span>
                </a>
            </li>
            <li class="menu-item-animation {{ Request::is('wali-kelas') || Request::is('wali-kelas/*') ? 'active' : '' }}">
                <a href="{{ route('wali-kelas') }}" class="sidebar-link hover:bg-indigo-700 {{ Request::is('wali-kelas*') ? 'bg-indigo-800 border-l-4 border-blue-300 active-link' : '' }}" style="transition: all 0.3s ease; border-radius: 0 8px 8px 0; margin: 4px 8px 4px 0;">
                    <span class="icon-container" style="background: rgba(255,255,255,0.1); padding: 10px; border-radius: 10px; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                        <i class="mdi mdi-account-child" style="font-size: 18px;"></i>
                    </span>
                    <span class="menu-item-label ml-3">Wali Kelas</span>
                </a>
            </li>
            <li class="menu-item-animation {{ Request::is('admin') || Request::is('admin/*') ? 'active' : '' }}">
                <a href="{{ route('admin') }}" class="sidebar-link hover:bg-indigo-700 {{ Request::is('admin*') ? 'bg-indigo-800 border-l-4 border-blue-300 active-link' : '' }}" style="transition: all 0.3s ease; border-radius: 0 8px 8px 0; margin: 4px 8px 4px 0;">
                    <span class="icon-container" style="background: rgba(21, 176, 153, 0.1); padding: 10px; border-radius: 10px; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                        <i class="mdi mdi-shield-account" style="font-size: 18px;"></i>
                    </span>
                    <span class="menu-item-label ml-3">Admin</span>
                </a>
            </li>
            <li class="menu-item-animation {{ Request::is('admin/pindah-data') ? 'active' : '' }}">
                <a href="{{ route('admin.pindah-data') }}" class="sidebar-link hover:bg-indigo-700 {{ Request::is('admin/pindah-data') ? 'bg-indigo-800 border-l-4 border-blue-300 active-link' : '' }}" style="transition: all 0.3s ease; border-radius: 0 8px 8px 0; margin: 4px 8px 4px 0;">
                    <span class="icon-container" style="background: rgba(255,255,255,0.1); padding: 10px; border-radius: 10px; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                        <i class="mdi mdi-swap-horizontal" style="font-size: 18px;"></i>
                    </span>
                    <span class="menu-item-label ml-3">Pemindahan Data</span>
                </a>
            </li>
            <li class="menu-item-animation {{ Request::is('admin/audit-log') ? 'active' : '' }}">
                <a href="{{ route('admin.audit-log') }}" class="sidebar-link hover:bg-indigo-700 {{ Request::is('admin/audit-log') ? 'bg-indigo-800 border-l-4 border-blue-300 active-link' : '' }}" style="transition: all 0.3s ease; border-radius: 0 8px 8px 0; margin: 4px 8px 4px 0;">
                    <span class="icon-container" style="background: rgba(255,255,255,0.1); padding: 10px; border-radius: 10px; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                        <i class="mdi mdi-file-document-box-search" style="font-size: 18px;"></i>
                    </span>
                    <span class="menu-item-label ml-3">Audit Log</span>
                </a>
            </li>
            <li class="menu-item-animation {{ Request::is('mata-pelajaran') || Request::is('mata-pelajaran/*') ? 'active' : '' }}">
                <a href="{{ route('mata-pelajaran') }}" class="sidebar-link hover:bg-indigo-700 {{ Request::is('mata-pelajaran*') ? 'bg-indigo-800 border-l-4 border-blue-300 active-link' : '' }}" style="transition: all 0.3s ease; border-radius: 0 8px 8px 0; margin: 4px 8px 4px 0;">
                    <span class="icon-container" style="background: rgba(255,255,255,0.1); padding: 10px; border-radius: 10px; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                        <i class="mdi mdi-book-open-variant" style="font-size: 18px;"></i>
                    </span>
                    <span class="menu-item-label ml-3">Mata Pelajaran</span>
                </a>
            </li>
        </ul>
        @else
        <p class="menu-label text-white opacity-70 px-4 mt-5 mb-2 text-xs font-bold uppercase" style="letter-spacing: 1.5px;">OPERASIONAL</p>
        <ul class="menu-list">
            <li class="menu-item-animation {{ Request::is('materi') || Request::is('materi/*') ? 'active' : '' }}">
                <a href="{{ route('materi') }}" class="sidebar-link hover:bg-indigo-700 {{ Request::is('materi*') ? 'bg-indigo-800 border-l-4 border-blue-300 active-link' : '' }}" style="transition: all 0.3s ease; border-radius: 0 8px 8px 0; margin: 4px 8px 4px 0;">
                    <span class="icon-container" style="background: rgba(255,255,255,0.1); padding: 10px; border-radius: 10px; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                        <i class="mdi mdi-file-document" style="font-size: 18px;"></i>
                    </span>
                    <span class="menu-item-label ml-3">Materi</span>
                </a>
            </li>
            <li class="menu-item-animation {{ Request::is('tugas') || Request::is('tugas/*') ? 'active' : '' }}">
                <a href="{{ route('tugas') }}" class="sidebar-link hover:bg-indigo-700 {{ Request::is('tugas*') ? 'bg-indigo-800 border-l-4 border-blue-300 active-link' : '' }}" style="transition: all 0.3s ease; border-radius: 0 8px 8px 0; margin: 4px 8px 4px 0;">
                    <span class="icon-container" style="background: rgba(255,255,255,0.1); padding: 10px; border-radius: 10px; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                        <i class="mdi mdi-clipboard-text" style="font-size: 18px;"></i>
                    </span>
                    <span class="menu-item-label ml-3">Tugas</span>
                </a>
            </li>
            <li class="menu-item-animation {{ Request::is('quiz') || Request::is('quiz/*') ? 'active' : '' }}">
                <a href="{{ route('quiz') }}" class="sidebar-link hover:bg-indigo-700 {{ Request::is('quiz*') ? 'bg-indigo-800 border-l-4 border-blue-300 active-link' : '' }}" style="transition: all 0.3s ease; border-radius: 0 8px 8px 0; margin: 4px 8px 4px 0;">
                    <span class="icon-container" style="background: rgba(255, 255, 255, 0.189); padding: 10px; border-radius: 10px; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                        <i class="mdi mdi-help-circle" style="font-size: 18px;"></i>
                    </span>
                    <span class="menu-item-label ml-3">Quiz</span>
                </a>
            </li>
            <li class="menu-item-animation {{ Request::is('rekap-quiz') || Request::is('rekap-quiz/*') ? 'active' : '' }}">
                <a href="{{ route('rekap.quiz') }}" class="sidebar-link hover:bg-indigo-700 {{ Request::is('rekap-quiz*') ? 'bg-indigo-800 border-l-4 border-blue-300 active-link' : '' }}" style="transition: all 0.3s ease; border-radius: 0 8px 8px 0; margin: 4px 8px 4px 0;">
                    <span class="icon-container" style="background: rgba(255, 255, 255, 0.189); padding: 10px; border-radius: 10px; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                        <i class="mdi mdi-clipboard-text-outline" style="font-size: 18px;"></i>
                    </span>
                    <span class="menu-item-label ml-3">Rekap Quiz</span>
                </a>
            </li>
            <li class="menu-item-animation {{ Request::is('rekap-tugas') || Request::is('rekap-tugas/*') ? 'active' : '' }}">
                <a href="{{ route('rekap.tugas') }}" class="sidebar-link hover:bg-indigo-700 {{ Request::is('rekap-tugas*') ? 'bg-indigo-800 border-l-4 border-blue-300 active-link' : '' }}" style="transition: all 0.3s ease; border-radius: 0 8px 8px 0; margin: 4px 8px 4px 0;">
                    <span class="icon-container" style="background: rgba(255, 255, 255, 0.189); padding: 10px; border-radius: 10px; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                        <i class="mdi mdi-clipboard-check" style="font-size: 18px;"></i>
                    </span>
                    <span class="menu-item-label ml-3">Rekap Tugas</span>
                </a>
            </li>
        </ul>
        @endif
        
        <!-- Spacer and version info at bottom -->
        <div class="mt-auto">
            <div class="px-4 py-3 text-xs" style="position: absolute; bottom: 0; width: 100%; background: rgba(0,0,0,0.2); color: rgba(255,255,255,0.7); border-top: 1px solid rgba(255,255,255,0.05);">
                <div class="flex items-center justify-center">
                    <span class="icon mr-2"><i class="mdi mdi-information-outline"></i></span>
                    <span>SKODA-LEARNING</span>
                </div>
            </div>
        </div>
    </div>
</aside>

<style>
/* Performance optimized animations for the sidebar */
.sidebar-animated {
    transition: all 0.2s ease;
    will-change: transform;
    background: linear-gradient(45deg, #4527A0 0%, #673AB7 50%, #9575CD 100%) !important;
}

.logo-container {
    transition: all 0.3s ease;
    background: linear-gradient(45deg, #7E57C2, #673AB7, #4527A0) !important;
}

/* Remove the animation initially and add it after page load */
.menu-item-animation {
    opacity: 0;
    transform: translateX(-10px);
    will-change: transform, opacity;
}

/* Single animation for all menu items */
@keyframes slide-in {
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.sidebar-link {
    display: flex;
    align-items: center;
    padding: 12px 16px;
    transition: background-color 0.2s ease;
    position: relative;
    will-change: transform;
}

.sidebar-link:hover .icon-container {
    transform: scale(1.05);
    background: rgba(255, 255, 255, 0.3) !important;
}

.sidebar-link:hover {
    background-color: rgba(126, 87, 194, 0.5) !important;
}

.sidebar-link:hover .menu-item-label {
    transform: translateX(2px);
}

.icon-container {
    transition: transform 0.2s ease;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
    will-change: transform;
}

.active-link .icon-container {
    background: linear-gradient(45deg, #7E57C2, #9575CD, #B39DDB) !important;
    transform: scale(1.05);
}

.active-link {
    background-color: rgba(69, 39, 160, 0.8) !important;
    border-left: 4px solid #B39DDB !important;
}

.menu-item-label {
    transition: transform 0.2s ease;
    font-weight: 500;
    letter-spacing: 0.5px;
}

.active-link .menu-item-label {
    font-weight: 600;
}

/* Responsive collapsing */
@media (max-width: 768px) {
    .is-collapsed .menu-item-label,
    .is-collapsed .menu-label {
        display: none;
    }
    
    .is-collapsed .icon-container {
        margin: 0 auto;
    }
    
    .is-collapsed {
        width: 70px;
    }
    
    .is-collapsed .sidebar-link {
        justify-content: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Apply animations with a small delay after page load
    setTimeout(() => {
        const menuItems = document.querySelectorAll('.menu-item-animation');
        menuItems.forEach((item, index) => {
            item.style.animation = `slide-in 0.2s ease forwards ${index * 0.03}s`;
        });
    }, 100);
    
    // Simplified ripple effect
    const sidebarLinks = document.querySelectorAll('.sidebar-link');
    
    sidebarLinks.forEach(link => {
        link.addEventListener('mousedown', function(e) {
            const ripple = document.createElement('div');
            const rect = link.getBoundingClientRect();
            
            ripple.className = 'ripple';
            ripple.style.cssText = `
                position: absolute;
                background: rgba(255, 255, 255, 0.3);
                border-radius: 50%;
                pointer-events: none;
                width: 10px;
                height: 10px;
                left: ${e.clientX - rect.left}px;
                top: ${e.clientY - rect.top}px;
                animation: ripple-animation 0.4s ease-out;
                will-change: transform, opacity;
            `;
            
            link.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 400);
        });
    });
    
    // Handle mobile toggle
    const mobileToggle = document.querySelector('.mobile-aside-button');
    const sidebar = document.querySelector('.sidebar-animated');
    
    if (mobileToggle && sidebar) {
        mobileToggle.addEventListener('click', function() {
            sidebar.classList.toggle('is-collapsed');
        });
    }
});

// Simplified keyframes for ripple effect
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple-animation {
        to {
            transform: scale(3);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script> 