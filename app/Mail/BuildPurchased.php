<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Build;

class BuildPurchased extends Mailable
{
    use Queueable, SerializesModels;

    public $build;
    public $quotationNumber;
    public $source;

    public function __construct(Build $build, $quotationNumber, $source)
    {
        $this->build = $build;
        $this->quotationNumber = $quotationNumber;
        $this->source = $source;
    }

    public function build()
    {
        return $this->view('emails.build_purchased')
                    ->subject('Your Build Purchase Confirmation - NextGen Computing');
    }
}