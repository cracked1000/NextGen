<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PurchaseConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $part;
    public $customer;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, $part, $customer)
    {
        $this->order = $order;
        $this->part = $part;
        $this->customer = $customer;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Purchase Confirmation - NextGen Computing')
                    ->view('emails.purchase_confirmation');
    }
}