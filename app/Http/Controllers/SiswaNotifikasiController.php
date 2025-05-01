<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SiswaNotifikasiController extends Controller
{
    public function notifCount(Request $request)
    {
        $siswa = $request->user()->siswa;

        return response()->json([
            'unread_count' => $siswa->unreadNotifications->count(),
        ]);
    }
    public function index(Request $request)
    {
        $siswa = $request->user()->siswa;

        return response()->json([
            'notifications' => $siswa->notifications->map(function ($notif) {
                return [
                    'id' => $notif->id,
                    'type' => $notif->data['type'],
                    'judul' => $notif->data['judul'] ?? '-',
                    'tenggat' => $notif->data['tenggat'] ?? null,
                    'read_at' => $notif->read_at,
                    'created_at' => $notif->created_at->toDateTimeString(),
                    'is_active' => $notif->read_at == null,
                ];
            })
        ]);
    }

    // Tandai notifikasi sebagai sudah dibaca
    public function markAsRead(Request $request, $id)
    {
        $notif = $request->user()->siswa->notifications()->findOrFail($id);
        $notif->markAsRead();

        return response()->json([
            'message' => 'Notifikasi ditandai sebagai dibaca.',
            'id' => $id,
        ]);
    }
}
