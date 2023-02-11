<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\OvernightStay;
use Twig\Environment;
use Knp\Snappy\Pdf;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final class ReceiptService
{
    /**
     * @param Environment $twig
     * @param Pdf $pdf
     */
    public function __construct(
        private Environment $twig,
        private Pdf         $pdf
    )
    {
    }

    /**
     * @param OvernightStay|null $os
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
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

    /**
     * @return string
     */
    private function generateQrCode(): string
    {
        $qrOptions = new QROptions(
            [
                'eccLevel' => QRCode::ECC_L,
                'outputType' => QRCode::OUTPUT_MARKUP_SVG,
            ]
        );

        return (new QRCode($qrOptions))->render('
        Symfony Hotel
        IBAN: HR2825000092198375352
        Ulica Kompozitora 256, Osijek

        github.com/filip-kr
        ');
    }
}
