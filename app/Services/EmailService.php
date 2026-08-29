<?php

declare(strict_types=1);

namespace App\Services;

//Responsavel pelo envio de emails do sistema
class EmailService
{
    public function sendServiceFinished(
        string $recipientEmail,
        string $recipientName,
        string $description,
        float $price,
        float $commission,
    ): bool {
        if (filter_var($recipientEmail, FILTER_VALIDATE_EMAIL) === false) {
            return false;
        }

        $subject = "Servico finalizado - JM Informatica";

        $message =
            "Olá, {$recipientName}.\n\n" .
            "O serviço \"{$description}\" foi finalizado.\n" .
            'Valor: R$ ' .
            number_format($price, 2, ",", ".") .
            "\n" .
            'Comissão: R$ ' .
            number_format($commission, 2, ",", ".") .
            "\n\nJM Informática";

        $headers = [
            "MIME-Version: 1.0",
            "Content-Type: text/plain; charset=UTF-8",
            "From: JM Informatica <no-reply@localhost>",
        ];

        return mail(
            $recipientEmail,
            $subject,
            $message,
            implode("\r\n", $headers),
        );
    }
}
