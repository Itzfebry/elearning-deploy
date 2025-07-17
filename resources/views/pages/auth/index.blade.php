<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Skolearn</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            position: relative;
        }
        
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("{{ asset('template/img/wallpaperlogin.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            filter: blur(2px);
            -webkit-filter: blur(90px);
            z-index: -1;
        }
        
        .login-animation {
            animation: fadeIn 1s ease-in-out;
            background-color: rgba(255, 255, 255, 0.35);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .btn-gradient {
            background: linear-gradient(to right, #4776E6, #8E54E9);
            transition: all 0.3s ease;
        }
        
        .btn-gradient:hover {
            background: linear-gradient(to right, #3A67D7, #7B43DA);
            transform: translateY(-1px);
            box-shadow: 0 7px 14px rgba(78, 107, 230, 0.25);
        }
        
        .input-focus:focus {
            border-color: #4776E6;
            box-shadow: 0 0 0 3px rgba(71, 118, 230, 0.2);
        }
        
        .gradient-text {
            background: linear-gradient(to right, #4800ff, #6200ff3a);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .transparent-footer {
            background-color: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen p-4">
    <div class="w-full max-w-md">
        <!-- Logo and branding area -->
        <div class="text-center mb-8">
            <div class="inline-block p-3 bg-white rounded-full shadow-md mb-4">
               <img src="{{ asset('template/img/logo2.png') }}" alt="E-Learning Logo" class="h-12 w-12 object-contain">
            </div>
            <h1 class="text-3xl font-bold gradient-text">SkoLearn</h1>
            <p class="text-mt-2">Login untuk mengakses informasi detail Dashboard dan Manajemen Data</p>
        </div>

        <!-- Login form card -->
        <div class="login-animation rounded-2xl shadow-xl overflow-hidden">
            <div class="p-8">
                <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Selamat Datang</h2>
                <form action="{{ route('login.action') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label for="nip" class="block text-sm font-medium text-gray-700 mb-1">Email atau NIP</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input type="text" id="nip" name="login" placeholder="Enter your Email or NIP"
                                class="input-focus pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200"
                                required value="{{ old('login') }}">
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            {{-- <a href="#" class="text-sm text-indigo-600 hover:text-indigo-800 hover:underline">Forgot password?</a> --}}
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input type="password" id="password" name="password" placeholder="••••••••"
                                class="input-focus pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200"
                                required>
                        </div>
                    </div>

                    @if (session('error'))
                    <div class="p-4 mb-1 text-sm text-red-700 rounded-lg bg-red-50 border border-red-200" role="alert">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="font-medium">{{ session('error') }}</span>
                        </div>
                    </div>
                    @endif

                    <!-- Remember me -->
                    <div class="flex items-center">
                        <input id="remember_me" name="remember_me" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                            Remember me
                        </label>
                    </div>

                    <!-- Submit -->
                    <div>
                        <button type="submit"
                            class="btn-gradient w-full text-white py-3 px-4 rounded-lg font-medium text-sm tracking-wide shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Sign In
                        </button>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="py-4 px-8 transparent-footer border-t border-gray-100 text-center">
                <p class="text-sm text-gray-700">
                    Don't have an account? 
                    <a href="https://wa.me/6288231806421" target="_blank" class="font-medium text-indigo-600 hover:text-indigo-800 hover:underline">
                        Contact admin
                    </a>
                </p>
            </div>
        </div>

        <!-- Section: Informasi Kredensial Login -->
        <div class="mt-8 mb-4 p-4 rounded-xl shadow-md bg-white/80 backdrop-blur-md">
            <h3 class="font-semibold text-lg mb-2 text-indigo-700">Informasi Kredensial Login</h3>
            <div class="mb-2">
                <span class="font-medium text-gray-800">Dosen/Admin:</span>
                <ul class="ml-4 text-sm text-gray-700">
                    <li>Email: <span class="font-mono">admin@gmail.com</span></li>
                    <li>Password: <span class="font-mono">12345</span></li>
                </ul>
            </div>
            <div>
                <span class="font-medium text-gray-800">Siswa:</span>
                <ul class="ml-4 text-sm text-gray-700">
                    <li>NISN: <span class="font-mono">1234567890</span></li>
                    <li>Password: <span class="font-mono">1234567890</span></li>
                </ul>
            </div>
        </div>

        <!-- Additional information -->
        <div class="mt-6 text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} SDN Koncer 2 Bondowoso. All rights reserved.
        </div>
    </div>
</body>

</html>