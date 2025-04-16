<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BuildDetailsEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $build;
    public $spec;
    public $use_case;
    public $quotationNumber;
    public $source;

    public function __construct($build, $spec, $use_case, $quotationNumber, $source)
    {
        $this->build = $build;
        $this->spec = $spec;
        $this->use_case = $use_case;
        $this->quotationNumber = $quotationNumber;
        $this->source = $source;
    }

    public function build()
    {
        return $this->view('emails.build_details')
                    ->subject("Your {$this->spec} PC Build Quotation for {$this->use_case} - NextGen Computing");
    }
}