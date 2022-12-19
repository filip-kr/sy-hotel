<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\OvernightStay;
use Twig\Environment;
use Knp\Snappy\Pdf;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

final class ReceiptService
{
    public function __construct(
        Environment $twig,
        Pdf $pdf,
    ) 
    {
        $this->twig = $twig;
        $this->pdf = $pdf;
    }

    public function generateReceipt(?OvernightStay $os): void
    {
        $this->pdf->setOption('encoding', 'UTF8');
        header('Content-Type: application/pdf');
        echo $this->pdf->getOutputFromHtml($this->twig->render(
            'private/overnightstays/receipt.html.twig',
            [
                'os' => $os,
                'qrCode' => $this->generateQrCode()
            ]
        ));
    }

    private function generateQrCode(): string
    {
        $qrOptions = new QROptions(
            [
                'eccLevel' => QRCode::ECC_L,
                'outputType' => QRCode::OUTPUT_MARKUP_SVG,
            ]
        );

        $qrCode = (new QRCode($qrOptions))->render('
        Symfony Hotel
        IBAN: HR2825000092198375352
        Ulica Kompozitora 256, Osijek

        github.com/filip-kr
        ');

        return $qrCode;
    }
}
