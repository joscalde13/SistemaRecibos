<?php

namespace App\Services;

class MoneyToWordsGtService
{
    /**
     * Converts quetzal amounts to uppercase words.
     */
    public function toWords(float $amount): string
    {
        $amount = round($amount, 2);
        $integerPart = (int) floor($amount);
        $cents = (int) round(($amount - $integerPart) * 100);

        $words = $this->convertNumber($integerPart);
        $words = $words === '' ? 'CERO' : $words;

        if ($cents === 0) {
            return trim($words.' QUETZALES EXACTOS');
        }

        return trim($words.' QUETZALES CON '.str_pad((string) $cents, 2, '0', STR_PAD_LEFT).'/100');
    }

    private function convertNumber(int $number): string
    {
        if ($number === 0) {
            return '';
        }

        if ($number < 0) {
            return 'MENOS '.$this->convertNumber(abs($number));
        }

        if ($number <= 29) {
            return $this->units($number);
        }

        if ($number <= 99) {
            return $this->tens($number);
        }

        if ($number <= 999) {
            return $this->hundreds($number);
        }

        if ($number <= 999999) {
            return $this->thousands($number);
        }

        if ($number <= 999999999) {
            return $this->millions($number);
        }

        return (string) $number;
    }

    private function units(int $number): string
    {
        $map = [
            0 => 'CERO',
            1 => 'UNO',
            2 => 'DOS',
            3 => 'TRES',
            4 => 'CUATRO',
            5 => 'CINCO',
            6 => 'SEIS',
            7 => 'SIETE',
            8 => 'OCHO',
            9 => 'NUEVE',
            10 => 'DIEZ',
            11 => 'ONCE',
            12 => 'DOCE',
            13 => 'TRECE',
            14 => 'CATORCE',
            15 => 'QUINCE',
            16 => 'DIECISEIS',
            17 => 'DIECISIETE',
            18 => 'DIECIOCHO',
            19 => 'DIECINUEVE',
            20 => 'VEINTE',
            21 => 'VEINTIUNO',
            22 => 'VEINTIDOS',
            23 => 'VEINTITRES',
            24 => 'VEINTICUATRO',
            25 => 'VEINTICINCO',
            26 => 'VEINTISEIS',
            27 => 'VEINTISIETE',
            28 => 'VEINTIOCHO',
            29 => 'VEINTINUEVE',
        ];

        return $map[$number] ?? '';
    }

    private function tens(int $number): string
    {
        if ($number <= 29) {
            return $this->units($number);
        }

        $tensMap = [
            30 => 'TREINTA',
            40 => 'CUARENTA',
            50 => 'CINCUENTA',
            60 => 'SESENTA',
            70 => 'SETENTA',
            80 => 'OCHENTA',
            90 => 'NOVENTA',
        ];

        $ten = (int) floor($number / 10) * 10;
        $unit = $number % 10;

        if ($unit === 0) {
            return $tensMap[$ten];
        }

        return $tensMap[$ten].' Y '.$this->units($unit);
    }

    private function hundreds(int $number): string
    {
        if ($number === 100) {
            return 'CIEN';
        }

        $hundredsMap = [
            100 => 'CIENTO',
            200 => 'DOSCIENTOS',
            300 => 'TRESCIENTOS',
            400 => 'CUATROCIENTOS',
            500 => 'QUINIENTOS',
            600 => 'SEISCIENTOS',
            700 => 'SETECIENTOS',
            800 => 'OCHOCIENTOS',
            900 => 'NOVECIENTOS',
        ];

        $hundred = (int) floor($number / 100) * 100;
        $rest = $number % 100;

        if ($rest === 0) {
            return $hundredsMap[$hundred];
        }

        return $hundredsMap[$hundred].' '.$this->convertNumber($rest);
    }

    private function thousands(int $number): string
    {
        $thousandPart = (int) floor($number / 1000);
        $rest = $number % 1000;

        $prefix = $thousandPart === 1
            ? 'MIL'
            : $this->convertNumber($thousandPart).' MIL';

        if ($rest === 0) {
            return $prefix;
        }

        return $prefix.' '.$this->convertNumber($rest);
    }

    private function millions(int $number): string
    {
        $millionPart = (int) floor($number / 1000000);
        $rest = $number % 1000000;

        $prefix = $millionPart === 1
            ? 'UN MILLON'
            : $this->convertNumber($millionPart).' MILLONES';

        if ($rest === 0) {
            return $prefix;
        }

        return $prefix.' '.$this->convertNumber($rest);
    }
}
