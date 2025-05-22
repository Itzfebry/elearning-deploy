<footer class="footer" style="background: linear-gradient(90deg, #4527A0 0%, #673AB7 100%); box-shadow: 0 -2px 20px rgba(0,0,0,0.2); color: white; padding: 1rem; position: relative; overflow: hidden;">
    <div class="flex flex-col md:flex-row items-center justify-between space-y-2 md:space-y-0">
        <div class="flex items-center justify-start space-x-3">
            <div class="flex items-center">
                <div style="background: linear-gradient(45deg, #7E57C2, #673AB7); padding: 4px; border-radius: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                    <img src="{{ asset('template/img/logo.png') }}" alt="Logo" style="width: 18px; height: 18px; border-radius: 3px; border: 1px solid rgba(255,255,255,0.8);">
                </div>
                <span class="text-xs ml-2" style="text-shadow: 0 1px 2px rgba(0,0,0,0.2);">© 2025 Skolearn.</span>
            </div>
        </div>
        <div class="flex items-center space-x-4">
            <a href="#" class="text-gray-300 hover:text-white transition-all duration-300 hover:scale-110 transform" style="display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.1); width: 28px; height: 28px; border-radius: 50%;">
                <i class="mdi mdi-web text-lg"></i>
            </a>
            <a href="#" class="text-gray-300 hover:text-white transition-all duration-300 hover:scale-110 transform" style="display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.1); width: 28px; height: 28px; border-radius: 50%;">
                <i class="mdi mdi-youtube text-lg"></i>
            </a>
            <a href="#" class="text-gray-300 hover:text-white transition-all duration-300 hover:scale-110 transform" style="display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.1); width: 28px; height: 28px; border-radius: 50%;">
                <i class="mdi mdi-instagram text-lg"></i>
            </a>
        </div>
    </div>
    
    <!-- Running text at the bottom -->
    <div class="running-text-container" style="position: absolute; bottom: 0; left: 0; right: 0; background-color: rgba(0,0,0,0.15); padding: 4px 0; margin-top: 8px; border-top: 1px solid rgba(255,255,255,0.1); overflow: hidden;">
        <div class="running-text">
            <span class="text-xs font-bold" style="text-shadow: 0 1px 2px rgba(0,0,0,0.3); white-space: nowrap;">SDN NEGERI KONCER 2 BONDOWOSO</span>
        </div>
    </div>
</footer>

<style>
.running-text-container {
    position: relative;
    overflow: hidden;
    height: 20px;
    display: flex;
    align-items: center;
}

.running-text {
    position: absolute;
    animation: running-text 15s linear infinite;
    white-space: nowrap;
}

@keyframes running-text {
    0% {
        transform: translateX(100%);
    }
    100% {
        transform: translateX(-100%);
    }
}
</style>