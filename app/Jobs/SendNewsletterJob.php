<?php

namespace App\Jobs;

use App\Mail\ThemeMail;
use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendNewsletterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $subscribers;
    protected $campaign;
    protected $subject;

    public function __construct($subscribers, $campaign, $subject)
    {
        $this->subscribers = $subscribers;
        $this->campaign = $campaign;
        $this->subject = $subject;
    }

    public function handle()
    {
        foreach ($this->subscribers as $subscriber) {
            $dataMail = [
                'name' => $subscriber->fullname ?? null,
                'email' => $subscriber->email ?? null,
            ];
            // Gửi email bằng cách xếp hàng
            Mail::to($subscriber->email)->send(new ThemeMail($dataMail, $this->campaign, $this->subject));
        }
    }
}
