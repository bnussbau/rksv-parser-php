<?php

class RksvParserTest extends \PHPUnit\Framework\TestCase
{
    private \Bnussbau\Rksv\RksvParser $parser;
    private \Bnussbau\Rksv\RksvParser $parser2;

    protected function setUp(): void
    {
        $rksvString = "_R1-AT0_0003111_0003111001000202404157928_2024-04-15T16:28:54_0,00_13,80_0,00_0,00_0,00_4jJVnlBGlgw=_U:ATU59193205-001_xg2ik+BDjGE=_MRuODBrEHpIbqWbi+JbMg3A8jaCrind4hTi07PpeqwN9i+Anww4pEjrFXQ1+sQ7vi1M6d5a0aN+X0+EMbHt2HA==";
        $this->parser = new \Bnussbau\Rksv\RksvParser($rksvString);

        $rksvString2 = "_R1-AT0_633/029_003-2024-04-13T08:54:54MSR7520_2024-04-13T08:54:54_5,20_22,98_11,37_0,00_0,00_H7tLtJNt8y4=_U:ATU78172745-2_iUIdqDTkZTA=_RVYFIO0aQa1myJC5VPlFQPZv0xz+T4Sf2PkxGG46r0c+Xn6p7OLG36/IUY9we2c4S3NkVJuEPGBqs4QBzoqjaQ==";
        $this->parser2 = new \Bnussbau\Rksv\RksvParser($rksvString2);
    }

    public function testCanGetCashRegisterAlgorithmIdentifier()
    {
        $this->assertEquals("R1-AT0", $this->parser->getCashRegisterAlgorithmIdentifier());
    }

    public function testCanGetCashRegisterID()
    {
        $this->assertEquals("0003111", $this->parser->getCashRegisterID());
    }

    public function testCanGetReceiptNumber()
    {
        $this->assertEquals("0003111001000202404157928", $this->parser->getReceiptNumber());
    }

    public function testCanGetReceiptDateTime()
    {
        $dateTime = \DateTime::createFromFormat('Y-m-d\TH:i:s', $this->data['receiptDateTime']  = "2024-04-15T16:28:54");
        $this->assertEquals($dateTime, $this->parser->getReceiptDateTime());
    }

    public function testCanGetSumTaxSetNormal()
    {
        $this->assertEquals(0.00, $this->parser->getSumTaxSetNormal());
    }

    public function testCanGetSumTaxSetReduced1()
    {
        $this->assertEquals(13.80, $this->parser->getSumTaxSetReduced1());
    }

    public function testCanGetSumTaxSetReduced2()
    {
        $this->assertEquals(0, $this->parser->getSumTaxSetReduced2());
    }

    public function testCanGetSumTaxSetZero()
    {
        $this->assertEquals(0, $this->parser->getSumTaxSetZero());
    }

    public function testCanGetSumTaxSetSpecial()
    {
        $this->assertEquals(0, $this->parser->getSumTaxSetSpecial());
    }

    public function testCanGetTurnoverCounterAES256ICM()
    {
        $this->assertEquals("4jJVnlBGlgw=", $this->parser->getTurnoverCounterAES256ICM());
    }

    public function testCanGetCertificateSerialNumber()
    {
        $this->assertEquals("U:ATU59193205-001", $this->parser->getCertificateSerialNumber());
    }

    public function testCanGetCompanyEuVatId()
    {
        $this->assertEquals("ATU59193205", $this->parser->getCompanyEuVatId());
    }

    public function testCanGetSignatureValuePreviousReceipt()
    {
        $this->assertEquals("xg2ik+BDjGE=", $this->parser->getSignatureValuePreviousReceipt());
    }

    public function testCanGetSignatureValue()
    {
        $this->assertEquals("MRuODBrEHpIbqWbi+JbMg3A8jaCrind4hTi07PpeqwN9i+Anww4pEjrFXQ1+sQ7vi1M6d5a0aN+X0+EMbHt2HA==", $this->parser->getSignatureValue());
    }

    public function testCanGetTotalIncludingTax()
    {
        $this->assertEquals(39.55, $this->parser2->getTotalIncludingTax());
    }

    public function testCanGetAmountTaxSetNormal()
    {
        $this->assertEquals(0.87, $this->parser2->getAmountTaxSetNormal());
    }

    public function testCanGetAmountTaxSetReduced1()
    {
        $this->assertEquals(2.09, $this->parser2->getAmountTaxSetReduced1());
    }

    public function testCanGetAmountTaxSetReduced2()
    {
        $this->assertEquals(1.31, $this->parser2->getAmountTaxSetReduced2());
    }

    public function testCanGetAmountTaxSetZero()
    {
        $this->assertEquals(0, $this->parser2->getAmountTaxSetZero());
    }
}