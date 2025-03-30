# RKSV Parser PHP

A PHP package for parsing RKSV (Registrierkassen-Sicherheitsverordnung) receipts, specifically designed for Austrian cash register systems.

## Features

- Parse RKSV receipt strings
- Extract receipt details including:
  - Cash register algorithm identifier
  - Cash register ID
  - Receipt number
  - Receipt date and time
  - Tax amounts (normal, reduced rates, zero, special)
  - Turnover counter
  - Certificate serial number
  - Company EU VAT ID
  - Signature values
- Support for SPAR JSON response format
- Calculate total amounts including tax
- Calculate individual tax amounts for different tax rates

## Installation

```bash
composer require bnussbau/rksv-parser-php
```

## Usage

### Basic Usage

```php
use Bnussbau\Rksv\RksvParser;

$rksvString = "_R1-AT0_0003111_0003111001000202404157928_2024-04-15T16:28:54_0,00_13,80_0,00_0,00_0,00_4jJVnlBGlgw=_U:ATU59193205-001_xg2ik+BDjGE=_MRuODBrEHpIbqWbi+JbMg3A8jaCrind4hTi07PpeqwN9i+Anww4pEjrFXQ1+sQ7vi1M6d5a0aN+X0+EMbHt2HA==";
$parser = new RksvParser($rksvString);

// Get receipt details
$receiptDateTime = $parser->getReceiptDateTime();
$totalIncludingTax = $parser->getTotalIncludingTax();
$companyVatId = $parser->getCompanyEuVatId();
```

### Parse from SPAR JSON Response

```php
$jsonResponse = '{"code":"_R1-AT0_0487502_0404875001001202203314713_2022-03-31T17:16:44_0,00_1,89_0,00_0,00_0,00_oIY9ibHmpIc=_U:ATU33803701-3_NEen0fnOY9M=_H+KLP63QRYvxajhywX3JDskJeQdtPzMN6eUOVV7fAMxiRb9WyAWRMB06ynFB7hpeCGFM34adT14BcWpx0PGxnA=="}';
$parser = RksvParser::fromSparJson($jsonResponse);
```

## Available Methods

- `getCashRegisterAlgorithmIdentifier()`: Get the algorithm identifier (e.g., "R1-AT0")
- `getCashRegisterID()`: Get the cash register ID
- `getReceiptNumber()`: Get the unique receipt number
- `getReceiptDateTime()`: Get the receipt date and time as DateTime object
- `getSumTaxSetNormal()`: Get the sum of normal tax rate
- `getSumTaxSetReduced1()`: Get the sum of first reduced tax rate
- `getSumTaxSetReduced2()`: Get the sum of second reduced tax rate
- `getSumTaxSetZero()`: Get the sum of zero tax rate
- `getSumTaxSetSpecial()`: Get the sum of special tax rate
- `getTurnoverCounterAES256ICM()`: Get the turnover counter
- `getCertificateSerialNumber()`: Get the certificate serial number
- `getCompanyEuVatId()`: Get the company EU VAT ID
- `getSignatureValuePreviousReceipt()`: Get the signature value of the previous receipt
- `getSignatureValue()`: Get the current receipt signature value
- `getTotalIncludingTax()`: Get the total amount including tax
- `getAmountTaxSetNormal()`: Get the tax amount for normal rate
- `getAmountTaxSetReduced1()`: Get the tax amount for first reduced rate
- `getAmountTaxSetReduced2()`: Get the tax amount for second reduced rate
- `getAmountTaxSetZero()`: Get the tax amount for zero rate

## Requirements

- PHP 8 or higher

## License

This package is licensed under the MIT License. 