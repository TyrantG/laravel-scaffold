<?php

namespace TyrantG\LaravelScaffold\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class AsyncLogger implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    private string $channel;

    private string $type;

    private string $message;

    private ?array $context;

    public function __construct(string $channel, string $type, string $message, ?array $context = null)
    {
        $this->channel = $channel;
        $this->type = $type;
        $this->message = $message;
        $this->context = $context;
    }

    public function handle(): void
    {
        Log::channel($this->channel)->{$this->type}($this->message, $this->context);
    }
}
