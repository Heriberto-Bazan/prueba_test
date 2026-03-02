<?php

namespace App\Service;

use Knp\Snappy\Pdf;
use Twig\Environment;

class PdfGenerator
{
    public function __construct(
        private Pdf $pdf,
        private Environment $twig
    ) {}

    public function generate(array $invoiceData): string
    {
        $html = $this->twig->render('pdf/invoice.html.twig', [
            'invoice' => $invoiceData,
        ]);

        return $this->pdf->getOutputFromHtml($html, [
            'page-size' => 'Letter',
            'margin-top' => 10,
            'margin-bottom' => 10,
            'margin-left' => 10,
            'margin-right' => 10,
            'encoding' => 'UTF-8',
        ]);
    }
}
