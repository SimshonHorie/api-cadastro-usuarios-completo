<?php
namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EnviarEmailBoasVindas implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private User $user) 
    {
        $this->queue = 'emails';
    }

    public function handle(): void
    {
        Mail::raw("Olá {$this->user->nome}, bem-vindo ao sistema!", function ($message) {
            $message->to($this->user->email)
                    ->subject('Boas-vindas ao Desafio!');
        });

        Log::info("E-mail de boas-vindas enviado para: " . $this->user->email);
    }
}