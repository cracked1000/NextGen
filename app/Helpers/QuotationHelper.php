<?php

namespace App\Helpers;

use App\Models\Quotation;
use App\Models\QuotationAction; // Ensure this is the correct namespace for QuotationAction

class QuotationHelper
{
    public static function generateQuotationNumber()
    {
        // Format: QTN-YYYYMMDD-XXXX
        $prefix = 'QTN';
        $date = now()->format('Ymd'); // e.g., 20250413

        // Find the last quotation number for today
        $lastQuotation = QuotationAction::where('quotation_number', 'like', "{$prefix}-{$date}-%")
            ->orderBy('quotation_number', 'desc')
            ->first();

        if ($lastQuotation) {
            // Extract the sequential number and increment it
            $lastNumber = (int) substr($lastQuotation->quotation_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        // Pad the number to 4 digits
        $paddedNumber = str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        return "{$prefix}-{$date}-{$paddedNumber}";
    }
}