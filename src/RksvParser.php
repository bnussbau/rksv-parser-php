<?php

namespace Bnussbau\Rksv;
class RksvParser
{
    protected array $data;

    public function __construct($rksvString)
    {
        $this->parseRksvString($rksvString);
    }

    private function parseRksvString($rksvString): void
    {
        $parts = explode('_', $rksvString);
        if (count($parts) < 13) {
            throw new \InvalidArgumentException('Invalid Rksv string format.');
        }

        $this->data = [
            'cashRegisterAlgorithmIdentifier' => $parts[1], // Registrierkassenalgorithmuskennzeichen
            'cashRegisterID' => $parts[2], // Kassen-ID
            'receiptNumber' => $parts[3], // Belegnummer
            'receiptDateTime' => $parts[4], // Beleg-Datum-Uhrzeit
            'sumTaxSetNormal' => $parts[5], // Betrag-Satz-Normal
            'sumTaxSetReduced1' => $parts[6], // Betrag-Satz-Ermäßigt1
            'sumTaxSetReduced2' => $parts[7], // Betrag-Satz-Ermäßigt2
            'sumTaxSetZero' => $parts[8], // Betrag-Satz-Null
            'sumTaxSetSpecial' => $parts[9], // Betrag-Satz-Besonders
            'turnoverCounterAES256ICM' => $parts[10], // Stand-Umsatz-Zähler-AES256-ICM
            'certificateSerialNumber' => $parts[11], // Zertifikat-Seriennummer
            'companyIDCleaned' => $this->extractCompanyId($parts[11]),
            'signatureValuePreviousReceipt' => $parts[12], // Sig-Voriger-Beleg
            'signatureValue' => $parts[13], // Sig-Wert
        ];
    }

    private function extractCompanyId(string $certificateSerialNumber): ?string
    {
        $companyId = preg_replace('/-.*$/', '', $certificateSerialNumber);
        $companyId = str_replace('U:', '', $companyId);
        
        return str_contains($companyId, 'ATU') ? $companyId : null;
    }

    public function getCashRegisterAlgorithmIdentifier()
    {
        return $this->data['cashRegisterAlgorithmIdentifier'];
    }

    public function getCertificateSerialNumber()
    {
        return $this->data['certificateSerialNumber'];
    }

    public function getCashRegisterId()
    {
        return $this->data['cashRegisterID'];
    }

    public function getKassenId()
    {
        return $this->getCashRegisterId();
    }

    public function getReceiptNumber()
    {
        return $this->data['receiptNumber'];
    }

    public function getBelegnummer()
    {
        return $this->getReceiptNumber();
    }

    public function getReceiptDateTime() : \DateTime
    {
        return \DateTime::createFromFormat('Y-m-d\TH:i:s', $this->data['receiptDateTime']);
    }

    public function getBelegDatumUhrzeit(): \DateTime
    {
        return $this->getReceiptDateTime();
    }

    public function getSumTaxSetNormal(): float
    {
        return $this->floatvalue($this->data['sumTaxSetNormal']);
    }

    public function getBetragSatzNormal(): float
    {
        return $this->getSumTaxSetNormal();
    }

    public function getSumTaxSetReduced1() : float
    {
        return $this->floatvalue($this->data['sumTaxSetReduced1']);
    }

    public function getBetragSatzErmaessigt1(): float
    {
        return $this->getSumTaxSetReduced1();
    }

    public function getSumTaxSetReduced2(): float
    {
        return $this->floatvalue($this->data['sumTaxSetReduced2']);
    }

    public function getBetragSatzErmaessigt2(): float
    {
        return $this->getSumTaxSetReduced2();
    }

    public function getSumTaxSetZero(): float
    {
        return $this->floatvalue($this->data['sumTaxSetZero']);
    }

    public function getBetragSatzNull(): float
    {
        return $this->getSumTaxSetZero();
    }

    public function getSumTaxSetSpecial(): float
    {
        return $this->floatvalue($this->data['sumTaxSetSpecial']);
    }

    public function getBetragSatzBesonders(): float
    {
        return $this->getSumTaxSetSpecial();
    }

    public function getTurnoverCounterAES256ICM()
    {
        return $this->data['turnoverCounterAES256ICM'];
    }

    public function getStandUmsatzZaehlerAES256ICM()
    {
        return $this->getTurnoverCounterAES256ICM();
    }

    public function getCompanyEuVatId()
    {
        return $this->data['companyIDCleaned'];
    }

    public function getSignatureValuePreviousReceipt()
    {
        return $this->data['signatureValuePreviousReceipt'];
    }

    public function getSignatureValue()
    {
        return $this->data['signatureValue'];
    }

    public function getTotalIncludingTax() : float
    {
        return $this->getSumTaxSetNormal() + $this->getSumTaxSetReduced1() + $this->getSumTaxSetReduced2() + $this->getSumTaxSetZero() + $this->getSumTaxSetSpecial();
    }

    public function getAmountTaxSetNormal(): float
    {
        return $this->calculateTaxAmount($this->getSumTaxSetNormal(), TaxRates::TAX_SET_NORMAL);
    }

    public function getAmountTaxSetReduced1(): float
    {
        return $this->calculateTaxAmount($this->getSumTaxSetReduced1(), TaxRates::TAX_SET_REDUCED_1);
    }

    public function getAmountTaxSetReduced2(): float
    {
        return $this->calculateTaxAmount($this->getSumTaxSetReduced2(), TaxRates::TAX_SET_REDUCED_2);
    }

    public function getAmountTaxSetZero(): float
    {
        return $this->calculateTaxAmount($this->getSumTaxSetZero(), TaxRates::TAX_SET_ZERO);
    }

    public function calculateTaxAmount($grossAmount, $taxRate): float
    {
        $taxBase = $grossAmount / (1 + $taxRate / 100);
        $taxAmount = $grossAmount - $taxBase;

        return round($taxAmount, 2);
    }
    private function floatvalue($val): float
    {
        $val = str_replace(",",".",$val);
        $val = preg_replace('/\.(?=.*\.)/', '', $val);
        return floatval($val);
    }

    /**
     * Creates a RksvParser instance from a SPAR API JSON response
     * 
     * @param string $jsonResponse The JSON response from SPAR API
     * @return self The RksvParser instance
     * @throws \RuntimeException If the response format is invalid
     */
    public static function fromSparJson(string $jsonResponse): self
    {
        $data = json_decode($jsonResponse, true);
        if (!is_array($data) || !isset($data['code']) || !is_string($data['code'])) {
            throw new \RuntimeException('Invalid response format from SPAR URL');
        }
        return new self($data['code']);
    }
}


