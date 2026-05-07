<?php

namespace App\Http\Controllers;

use App\Models\Pagamento;
use Barryvdh\DomPDF\Facade\Pdf;

class ReciboController extends Controller
{
    public function gerar(Pagamento $pagamento)
    {
        $pagamento->load(['paciente', 'orcamento', 'parcelas']);

        $pdf = Pdf::loadView('recibos.recibo', compact('pagamento'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream("recibo-{$pagamento->id}.pdf");
    }
}