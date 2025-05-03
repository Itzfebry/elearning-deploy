<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    public function change()
    {
        return view("pages.auth.change_password");
    }
    public function changePassword(Request $request)
    {
        try {
            $request->validate([
                'old_password' => ['required'],
                'new_password' => ['required', 'min:8', 'confirmed'],
            ]);

            $user = Auth::user();

            if (!Hash::check($request->old_password, $user->password)) {
                Alert::error("Terjadi Kesalahan", "Password lama salah.");
                return redirect()->back();
            }

            User::where('id', $user->id)->update(['password' => Hash::make($request->new_password)]);
            Alert::success('Berhasil', 'Password berhasil diubah.');
            if ($user->role == "guru") {
                return redirect()->route("dashboard.guru");
            } else {
                return redirect()->route("/");
            }
        } catch (\Throwable $th) {
            Alert::error($th->getMessage());
            return redirect()->back();
        }
    }

    public function changePasswordApi(Request $request)
    {
        try {
            $request->validate([
                'old_password' => ['required'],
                'new_password' => ['required', 'min:8', 'confirmed'],
            ]);

            $user = Auth::user();

            if (!Hash::check($request->old_password, $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => "Password lama salah.",
                ]);
            }

            User::where('id', $user->id)->update(['password' => Hash::make($request->new_password)]);
            return response()->json([
                'status' => true,
                'message' => "Password berhasil diubah.",
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => false,
                'message' => $th->getMessage(),
            ]);
        }
    }
}
