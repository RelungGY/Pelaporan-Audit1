<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function sendAlert(Request $request, $id)
    {
        // Cari transaksi berdasarkan ID
        $transaction = Transaction::findOrFail($id);

        // Cek apakah transaksi sudah "confirmed" dan status "perlu dikirim"
        if ($transaction->status_confirm === 'confirmed' && $transaction->status === 'perlu dikirim') {
            // Cari seller_id berdasarkan item_id
            $seller_id = $transaction->item->seller_id;

            // Simpan notifikasi baru untuk seller
            Notification::create([
                'user_id' => $seller_id, // ID seller yang menerima notifikasi
                'sender_id' => auth()->id(), // ID admin yang mengirim notifikasi
                'type' => 'alert',
                'message' => 'Segera kirimkan barang.',
                'is_read' => false,
            ]);

            return response()->json(['success' => true, 'message' => 'Notifikasi berhasil dikirim.']);
        }

        return response()->json(['success' => false, 'message' => 'Transaksi tidak valid untuk mengirim notifikasi.'], 400);
    }

    public function getNotifications()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        $unreadCount = Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }

    public function markAsRead(Request $request)
    {
        $notification = Notification::findOrFail($request->notification_id);

        if ($notification->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $notification->update(['is_read' => true]);

        return response()->json(['message' => 'Notification marked as read']);
    }

    public function getAll()
{
    // Get alerts and messages
    $alerts = Notification::where('type', 'alert')->latest()->get();
    $messages = Notification::where('type', 'message')->latest()->get();

    return response()->json([
        'alerts' => $alerts,
        'messages' => $messages,
    ]);
}

public function markAsReadUser($id)
{
    $notification = Notification::findOrFail($id);
    $notification->update(['is_read' => true]);

    return redirect()->back()->with('success', 'Notifikasi telah ditandai sebagai dibaca.');
}

public function sendAlertToUser(Request $request, $id)
    {
        // Cari transaksi berdasarkan ID
        $transaction = Transaction::findOrFail($id);

        // Cek apakah transaksi sudah "confirmed" dan status "perlu dikirim"

        // Simpan notifikasi baru untuk seller
        Notification::create([
            'user_id' => $transaction->user_id, 
            'sender_id' => auth()->id(),
            'type' => 'alert',
            'message' => 'Peringatan untuk mengembalikan barang.',
            'is_read' => false,
        ]);

        return response()->json(['success' => true, 'message' => 'Notifikasi berhasil dikirim.']);
    }
}