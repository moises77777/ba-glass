<?php

namespace App\Console\Commands;

use App\Mail\WarrantyExpiringMail;
use App\Models\Equipment;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendWarrantyExpiringNotifications extends Command
{
    protected $signature = 'notifications:warranty-expiring {--days=30}';

    protected $description = 'Envía notificaciones por correo de garantías próximas a vencer';

    public function handle(): int
    {
        $days = (int) $this->option('days');

        $equipment = Equipment::with(['category', 'brand', 'currentEmployee'])
            ->whereNotNull('warranty_end_date')
            ->where('warranty_end_date', '<=', now()->addDays($days))
            ->where('warranty_end_date', '>=', now())
            ->orderBy('warranty_end_date')
            ->get();

        if ($equipment->isEmpty()) {
            $this->info('No hay garantías próximas a vencer.');
            return self::SUCCESS;
        }

        // Enviar a administradores
        $recipients = User::active()
            ->whereHas('roles', fn ($q) => $q->where('name', 'Administrador'))
            ->pluck('email')
            ->filter();

        if ($recipients->isEmpty()) {
            $recipients = User::active()->pluck('email')->filter();
        }

        foreach ($recipients as $email) {
            try {
                Mail::to($email)->send(new WarrantyExpiringMail($equipment, $days));
            } catch (\Throwable $e) {
                $this->error("Error al enviar a {$email}: {$e->getMessage()}");
            }
        }

        $this->info("Notificación enviada a {$recipients->count()} destinatario(s) sobre {$equipment->count()} equipo(s).");

        return self::SUCCESS;
    }
}
