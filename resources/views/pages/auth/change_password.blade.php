<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - E-Learning Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('template/img/logo.jpg') }}" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .form-animation {
            animation: fadeIn 0.6s ease-in-out;
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
    </style>
</head>

<body class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 flex items-center justify-center min-h-screen p-4">
    <div class="w-full max-w-md">
        <!-- Logo and branding area -->
        <div class="text-center mb-8">
            <div class="inline-block p-3 bg-white rounded-full shadow-md mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">E-Learning</h1>
            <p class="text-gray-500 mt-2">Manage your account security</p>
        </div>

        <!-- Change password form card -->
        <div class="form-animation bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="p-8">
                <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Change Password</h2>
                
                <form action="{{ route('change-password.store') }}" method="POST" id="passwordForm" class="space-y-6">
                    @csrf
                    
                    <!-- Old password field -->
                    <div>
                        <label for="old_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input type="password" id="old_password" name="old_password"
                                class="input-focus pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200"
                                required>
                        </div>
                    </div>
                    
                    <!-- New password field -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                            </div>
                            <input type="password" id="password" name="new_password"
                                class="input-focus pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200"
                                required>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Use at least 8 characters with a mix of letters, numbers & symbols</p>
                    </div>
                    
                    <!-- Confirm password field -->
                    <div>
                        <label for="confirmPassword" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <input type="password" id="confirmPassword" name="new_password_confirmation"
                                class="input-focus pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none transition-all duration-200"
                                required>
                        </div>
                        <div id="passwordMatch" class="mt-1 text-xs hidden">
                            <span class="text-green-600">✓ Passwords match</span>
                        </div>
                        <div id="passwordMismatch" class="mt-1 text-xs hidden">
                            <span class="text-red-600">✗ Passwords don't match</span>
                        </div>
                    </div>

                    <!-- Submit button -->
                    <div>
                        <button type="submit" id="submitButton"
                            class="btn-gradient w-full text-white py-3 px-4 rounded-lg font-medium text-sm tracking-wide shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 flex items-center justify-center">
                            <span>Update Password</span>
                            <span id="loader" class="hidden ml-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Footer -->
            <div class="py-4 px-8 bg-gray-50 border-t border-gray-100 text-center">
                <a href="{{ Auth::user()->role == 'admin' ? url('/') : route('dashboard.guru') }}" class="text-sm text-indigo-600 hover:text-indigo-800 hover:underline flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Dashboard
                </a>
            </div>
        </div>
        
        <!-- Additional information -->
        <div class="mt-6 text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} E-Learning Platform. All rights reserved.
        </div>
    </div>

    @include('sweetalert::alert')

    <script>
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirmPassword');
        const passwordMatch = document.getElementById('passwordMatch');
        const passwordMismatch = document.getElementById('passwordMismatch');
        const submitButton = document.getElementById('submitButton');
        const loader = document.getElementById('loader');
        const form = document.getElementById('passwordForm');

        // Check if passwords match
        function validatePassword() {
            if (confirmPassword.value === '') {
                passwordMatch.classList.add('hidden');
                passwordMismatch.classList.add('hidden');
                return false;
            }
            
            if (password.value === confirmPassword.value) {
                passwordMatch.classList.remove('hidden');
                passwordMismatch.classList.add('hidden');
                return true;
            } else {
                passwordMatch.classList.add('hidden');
                passwordMismatch.classList.remove('hidden');
                return false;
            }
        }

        // Add event listeners
        password.addEventListener('keyup', validatePassword);
        confirmPassword.addEventListener('keyup', validatePassword);

        // Handle form submission
        form.addEventListener('submit', function(e) {
            if (!validatePassword() && confirmPassword.value !== '') {
                e.preventDefault();
                confirmPassword.focus();
                return false;
            }
            
            // Show loader
            loader.classList.remove('hidden');
            submitButton.disabled = true;
        });
    </script>
</body>

</html>