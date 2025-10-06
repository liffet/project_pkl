<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Item;
use App\Helpers\TelegramHelper;
use Carbon\Carbon;

class CheckMaintenanceItems extends Command
{
    protected $signature = 'maintenance:check';
    protected $description = 'Mengirim laporan harian maintenance barang ke Telegram';

    public function handle()
    {
        $today = Carbon::today();

        // Kategorisasi barang
        $totalItems = Item::count();

        $soonList = Item::whereDate('replacement_date', '>', $today)
                        ->whereDate('replacement_date', '<=', $today->copy()->addDays(30))
                        ->get();

        $mustList = Item::whereDate('replacement_date', '<=', $today)->get();

        // Format daftar nama barang hampir maintenance
        $soonText = $soonList->count() > 0
            ? implode("\n", $soonList->map(fn($i) => "- [#{$i->id}] {$i->name}")->toArray())
            : "Tidak ada barang yang hampir maintenance ✅";

        // Format daftar nama barang harus maintenance
        $mustText = $mustList->count() > 0
            ? implode("\n", $mustList->map(fn($i) => "- [#{$i->id}] {$i->name}")->toArray())
            : "Tidak ada barang yang harus maintenance ✅";

        // Format pesan Telegram
        $message = "🛠️ <b>Laporan Harian Maintenance Perangkat</b>\n";
        $message .= "📅 <b>" . $today->format('d-m-Y') . "</b>\n\n";
        $message .= "📦 <b>Total Barang:</b> {$totalItems}\n";
        $message .= "⚠️ <b>Hampir Maintenance:</b> {$soonList->count()}\n";
        $message .= "❌ <b>Harus Maintenance:</b> {$mustList->count()}\n\n";
        $message .= "⚠️ <b>Daftar Hampir Maintenance:</b>\n{$soonText}\n\n";
        $message .= "❌ <b>Daftar Harus Maintenance:</b>\n{$mustText}\n\n";
        $message .= "📡 Pesan ini dikirim otomatis oleh sistem.";

        TelegramHelper::sendMessage($message);

        $this->info('Notifikasi maintenance berhasil dikirim ke Telegram.');
    }
}
