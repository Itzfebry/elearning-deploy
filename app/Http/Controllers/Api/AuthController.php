<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponse;
use App\Models\Admin;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponse;

    public function login(Request $request)
    {
        $request->validate([
            "login" => "required|string",
            "password" => "required|string",
        ]);

        // Temukan user berdasarkan email atau NIP
        $login = $request->login;
        $user = User::where('email', $login)->first();

        if (!$user) {
            $siswa = Siswa::where('nisn', $login)->first();
            if ($siswa)
                $user = $siswa->user;

            if (!$user) {
                $guru = Guru::where('nip', $login)->first();
                if ($guru)
                    $user = $guru->user;
            }
        }

        $token = DB::table('personal_access_tokens')->where('tokenable_id', $user->id)->first();
        if (!is_null($token)) {
            return $this->errorApiResponse("User sudah login di device lain. Harap logout terlebih dahulu.");
        }


        // Jika user ditemukan dan password cocok
        if ($user && Hash::check($request->password, $user->password)) {
            $token = $user->createToken('Mobile')->plainTextToken;
            $user = $user->role == "siswa" ? $siswa : $guru;

            return $this->okApiResponse([
                'token' => $token,
                'user' => $user,

            ]);
        }

        return $this->errorApiResponse('Login gagal. Cek kembali kredensial Anda.');
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->okApiResponse([], 'Berhsasil Logout.');
    }


    public function user(Request $request)
    {
        $data = [];
        $user = $request->user();
        if ($user->role == 'siswa') {
            $data = Siswa::where('user_id', $user->id);
        } else {
            $data = Guru::where('user_id', $user->id);
        }

        $data = $data->with('user')->first();

        return $this->okApiResponse([
            'user' => $data,
        ]);

    }
}
