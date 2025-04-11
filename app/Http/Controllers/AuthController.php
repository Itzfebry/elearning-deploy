<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Guru;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("pages.role_admin.auth.index");
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                "login" => "required|string",
                "password" => "required|string",
            ]);

            $login = $request->login;
            $user = User::where('email', $login)->first();

            if (!$user) {
                $admin = Admin::where('nip', $login)->first();
                if ($admin) {
                    $user = $admin->user;
                }

                if (!$user) {
                    $guru = Guru::where('nip', $login)->first();
                    if ($guru) {
                        $user = $guru->user;
                    }
                }
            }

            if ($user && Hash::check($request->password, $user->password)) {
                Auth::login($user);
                $request->session()->regenerate();

                if ($user->role == 'admin') {
                    return redirect()->route('/');
                } elseif ($user->role == 'guru') {
                    return redirect()->route('/');
                }
            }

            return redirect()->route('login')->with('error', "Nip atau Password anda salah!");
        } catch (Exception $e) {
            Log::error("Error saat login: " . $e->getMessage());
            return redirect()->route("login")->with("error", "Terjadi kesalahan sistem. Silahkan coba lagi. $e");
        }
    }

    public function logout(Request $request)
    {
        try {
            Auth::logout();
            $request->session()->invalidate();
            // DB::table('sessions')->where('user_id', Auth::user()->nip)->delete();
            $request->session()->regenerateToken();
            return redirect()->route('login');
        } catch (Exception $e) {
            Log::error("Error saat login: " . $e->getMessage());
        }
    }
}
