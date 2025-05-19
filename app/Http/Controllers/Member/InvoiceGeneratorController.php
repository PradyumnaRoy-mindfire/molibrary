<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
// use PDF;

class InvoiceGeneratorController extends Controller
{
    //
    public function generateInvoice($payment)
    {

        $invoiceNumber = 'INV-' . date('Ymd') . '-' . $payment->id;
        
        // Set data for the invoice
       
        $data = [
            'name' => $payment->metadata->name,
            'email' => $payment->receipt_email,
            'invoice_number' => $invoiceNumber,
            'amount' => $payment->amount /100,
            'date' => date('Y-m-d'),
            'user' => Auth::user(),
            'payment' => $payment,
            // Add any other data you need for the invoice
        ];
        
        $pdf = PDF::loadView('pdfs.invoice', $data);
        
        // return $pdf->download('invoice-' . $invoiceNumber . '.pdf');
        
        return [
            'pdf' => $pdf,
            'filename' => 'invoice-' . $invoiceNumber . '.pdf',
            'invoice_number' => $invoiceNumber,
            'amount' => $payment->amount /100,
            'date' => date('Y-m-d'),
            'receipt_email' => $payment->receipt_email
        ];
    }
}
