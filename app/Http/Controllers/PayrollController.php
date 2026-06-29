<?php

namespace App\Http\Controllers;

use App\Models\Payrolls;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function slip(Payrolls $payroll)
    {
        $pdf = Pdf::loadView(
            'payrolls.slip',
            compact('payroll')
        );

        return $pdf->stream(
            'slip-gaji-'.$payroll->id.'.pdf'
        );
    }
}
