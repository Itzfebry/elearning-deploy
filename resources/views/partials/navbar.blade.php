<nav id="navbar-main" class="navbar is-fixed-top" style="background: linear-gradient(90deg, #4527A0 0%, #673AB7 100%); box-shadow: 0 2px 20px rgba(0,0,0,0.2);">
    <div class="navbar-brand">
        <a class="navbar-item mobile-aside-button hover:bg-indigo-700" style="transition: all 0.3s ease; border-radius: 8px; margin: 4px;">
            <span class="icon text-white" style="background: rgba(255,255,255,0.1); padding: 6px; border-radius: 8px;"><i class="mdi mdi-forwardburger mdi-24px"></i></span>
        </a>
    </div>
    <div class="navbar-brand is-right">
        <a class="navbar-item --jb-navbar-menu-toggle hover:bg-indigo-700" data-target="navbar-menu" style="transition: all 0.3s ease; border-radius: 8px; margin: 4px;">
            <span class="icon text-white" style="background: rgba(255,255,255,0.1); padding: 6px; border-radius: 8px;"><i class="mdi mdi-dots-vertical mdi-24px"></i></span>
        </a>
    </div>
    <div class="navbar-menu" id="navbar-menu" style="background: linear-gradient(90deg, #4527A0 0%, #673AB7 100%);">
        <div class="navbar-end">
            <div class="navbar-item dropdown-wrapper">
                <!-- Hidden checkbox to control dropdown state -->
                <input type="checkbox" id="dropdown-toggle" class="dropdown-toggle-checkbox">
                
                <!-- Dropdown Trigger / Label -->
                <label for="dropdown-toggle" class="navbar-link hover:bg-indigo-700 dropdown-trigger" style="transition: all 0.3s ease; background-color: transparent; border-radius: 8px; margin: 4px; cursor: pointer; display: flex; align-items: center;">
                    <div class="user-avatar" style="display: flex; align-items: center; justify-content: center;">
                        <div style="background: linear-gradient(45deg, #7E57C2, #673AB7); padding: 2px; border-radius: 50%; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;">
                            <img src="{{ asset('template/img/user.jpg') }}" class="rounded-full" style="width: 24px; height: 24px; border: 1px solid rgba(255,255,255,0.8);">
                        </div>
                    </div>
                    @if (Auth::user()->role == "admin")
                    <div class="is-user-name"><span class="text-white text-sm ml-2" style="text-shadow: 0 1px 2px rgba(0,0,0,0.2);">{{ Auth::user()->admin->nama }}</span></div>
                    @else
                    <div class="is-user-name"><span class="text-white text-sm ml-2" style="text-shadow: 0 1px 2px rgba(0,0,0,0.2);">{{ Auth::user()->guru->nama }}</span></div>
                    @endif
                    <span class="icon text-white chevron-icon ml-1"><i class="mdi mdi-chevron-down"></i></span>
                </label>
                
                <!-- Dropdown Menu -->
                <div class="dropdown-content" style="background: linear-gradient(135deg, #4527A0 0%, #673AB7 100%); border-top: 1px solid rgba(255,255,255,0.1); border-radius: 0 0 12px 12px; box-shadow: 0 15px 30px rgba(0,0,0,0.3); padding: 0.5rem; min-width: 200px;">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <hr class="navbar-divider" style="background-color: rgba(255,255,255,0.1);">
                        <a href="{{ route('change-password') }}" class="navbar-item text-white hover:bg-indigo-700 dropdown-menu-item" style="border-radius: 6px; margin: 5px; display: flex; align-items: center; padding: 0.6rem 1rem;">
                            <span class="icon" style="background: rgba(255,255,255,0.1); padding: 6px; border-radius: 8px;"><i class="mdi mdi-lock"></i></span>
                            <span class="ml-2">Ubah Password</span>
                        </a>
                        <button type="submit" class="navbar-item text-white hover:bg-indigo-700 w-full text-left dropdown-menu-item" style="border-radius: 6px; margin: 5px; display: flex; align-items: center; padding: 0.6rem 1rem;">
                            <span class="icon" style="background: rgba(255,255,255,0.1); padding: 6px; border-radius: 8px;"><i class="mdi mdi-logout"></i></span>
                            <span class="ml-2">Log Out</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

<style>
/* Checkbox Dropdown Styling */
.dropdown-wrapper {
    position: relative;
}

/* Hide checkbox */
.dropdown-toggle-checkbox {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

/* Style the dropdown content */
.dropdown-content {
    position: absolute;
    top: 100%;
    right: 0;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s cubic-bezier(0.68, -0.55, 0.27, 1.55);
    z-index: 999;
    overflow: hidden;
}

/* When checkbox is checked, show dropdown */
.dropdown-toggle-checkbox:checked + .dropdown-trigger .chevron-icon i {
    transform: rotate(180deg);
    transition: transform 0.3s ease;
}

.dropdown-toggle-checkbox:checked ~ .dropdown-content {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

/* Dropdown menu items animation */
.dropdown-menu-item {
    position: relative;
    overflow: hidden;
    transition: background-color 0.3s ease;
    opacity: 0;
    transform: translateX(-10px);
    animation: slide-in 0.3s forwards;
}

.dropdown-toggle-checkbox:checked ~ .dropdown-content .dropdown-menu-item:nth-child(2) {
    animation-delay: 0.05s;
}

.dropdown-toggle-checkbox:checked ~ .dropdown-content .dropdown-menu-item:nth-child(3) {
    animation-delay: 0.1s;
}

.dropdown-toggle-checkbox:checked ~ .dropdown-content .dropdown-menu-item:nth-child(4) {
    animation-delay: 0.15s;
}

@keyframes slide-in {
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Hover effect */
.dropdown-menu-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.1);
    transform: translateX(-100%);
    transition: transform 0.3s ease;
    z-index: -1;
}

.dropdown-menu-item:hover::before {
    transform: translateX(0);
}

/* Chevron icon transition */
.chevron-icon i {
    transition: transform 0.3s ease;
}
</style>