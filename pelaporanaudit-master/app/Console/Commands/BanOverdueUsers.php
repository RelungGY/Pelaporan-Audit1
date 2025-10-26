<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Transaction;
use Carbon\Carbon;

class BanOverdueUsers extends Command
{
    protected $signature = 'users:ban-overdue';
    protected $description = 'Ban users who have not returned items more than 3 days after the due date';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Ambil semua transaksi yang overdue (belum dikembalikan lebih dari 3 hari)
        $overdueTransactions = Transaction::where('status', 'Selesai Dikirim') // Hanya yang belum dikembalikan
            ->where('deadline', '<', Carbon::now()->subDays(3)) // Lewat 3 hari
            ->get();

        foreach ($overdueTransactions as $transaction) {
            // Ambil user terkait
            $user = $transaction->user;

            if ($user && $user->hukuman !== 'banned') {
                // Update status user menjadi 'banned'
                $user->update(['hukuman' => 'banned']);

                // (Opsional) Kirim notifikasi email ke user
                $this->sendNotification($user);
            }
        }

        $this->info('Overdue users have been banned successfully.');
    }

    protected function sendNotification($user)
    {
        // (Opsional) Logika untuk mengirim email notifikasi ke pengguna
        \Mail::to($user->email)->send(new \App\Mail\OverdueBanNotification($user));
    }
}