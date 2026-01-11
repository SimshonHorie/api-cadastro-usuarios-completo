<?php
namespace App\Jobs;

use App\Models\User;
use App\Mail\BoasVindasMail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class EnviarEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    public function __construct(protected User $user) {}

    public function handle(): void
    {
        Mail::to($this->user->email)->send(new BoasVindasMail($this->user));
    }
}