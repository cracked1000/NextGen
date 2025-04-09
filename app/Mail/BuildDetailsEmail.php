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

    public function __construct($build, $spec, $use_case)
    {
        $this->build = $build;
        $this->spec = $spec;
        $this->use_case = $use_case;
    }

    public function build()
    {
        return $this->subject('Your PC Build Quotation from NextGen Computing')
                    ->view('emails.build_details')
                    ->with([
                        'build' => $this->build,
                        'spec' => $this->spec,
                        'use_case' => $this->use_case,
                    ]);
    }
}