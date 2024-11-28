<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payments;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function exportInvoice($reservation_id)
    {
        
        $payment = Payments::with(['employee', 'reservation.customer'])
                ->where('reservation_id', $reservation_id)
                ->firstOrFail();

        $invoiceData = [
            'payment' => $payment,
            'customer' => $payment->reservation->customer,
            'employee' => $payment->employee,
            'formatted_price' => number_format($payment->payment_price, 0, ',', '.'),
        ];

        $pdf = PDF::loadView('customer.payment.invoice', $invoiceData);
        // $fileName = ('HoaDonThanhToan_' . $payment->payment_id . '.pdf');
        // $filePath = public_path('invoices/' . $fileName);

        // $pdf->save($filePath);

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Invoice created successfully.',
        //     'invoice_url' => asset('invoices/' . $fileName),
        // ]);
        return $pdf->download('HoaDonThanhToan_' . $payment->payment_id . '.pdf');

    }
}