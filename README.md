# Extract billing data according to rechnungsmail.de

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

This package provides an easy way to extract billing data according to [rechnungsmail.de](http://rechnungsmail.de).

einfachArchiv is a German SaaS product to organize your documents in one place and meet all legal requirements. You'll find us [on our website](https://www.einfacharchiv.com).

## Requirements

PHP 7.0 and later.

## Installation

You can install this package via [Composer](http://getcomposer.org/). Run the following command:

```bash
composer require einfacharchiv/rechnungsmail.de
```

## Usage

Extracting billing data is easy.

```php
$email = <<<EOE
Sehr geehrte Frau Mustermann, 

anbei erhalten Sie Rechnung-Nr. 4711 vom 20.11.2077. 
Die Rechnung ist im PDF-Format erstellt worden. Sollten 
Sie Fragen oder Probleme haben, stehen wir Ihnen 
jederzeit zur Verfügung. 

-- 
Mit freundlichen Grüßen,
Max Müller

Rechnungsdaten nach rechnungsmail.de:
Rechnungsnummer:     4711
Rechnungsdatum:      20.11.2077
Rechnungsbetrag:     119,00 EUR
Kundennummer:        123456
Nettobetrag 19%:     100,00 EUR
Umsatzsteuer 19%:    19,00 EUR
Absender-Name:       Max Müller GbR
Ihre Auftragsnummer: 4500006789
Vertragsnummer:      V-4711
EOE;

$rechnungsmail = new \einfachArchiv\Rechnungsmail\Rechnungsmail($email);

// Check if the email contains billing data according to rechnungsmail.de
if ($rechnungsmail->isValid()) {
    $rechnungsmail->getInvoiceId();
    $rechnungsmail->getInvoiceDate();
    $rechnungsmail->getInvoiceAmount(); // Returns an array
    $rechnungsmail->getCustomerId();
    $rechnungsmail->getNetAmount(); // Returns an array
    $rechnungsmail->getTax(); // Returns an array
    $rechnungsmail->getSenderName();
    $rechnungsmail->getOrderId();
    $rechnungsmail->getContractId();
}
```

If a field is not present or invalid, the method returns `null`.

The method `->getInvoiceAmount()` returns an array like this one:

```php
[
    'amount' => 119,
    'currency' => 'EUR',
];
```

The methods `->getNetAmount()` and `->getTax()` return arrays like this one:

```php
[
    'amount' => 100,
    'currency' => 'EUR',
    'taxRate' => 19
];
```

All amounts are returned as `floats`.

The invoice date is, if present, a valid date and can be used like this:

```php
$invoiceDate = $rechnungsmail->getInvoiceDate();

if ($invoiceDate) {
    \Carbon\Carbon::parse($invoiceDate)->toDateString();
}
```

## Contributing
Contributions are **welcome**.

We accept contributions via Pull Requests on [Github](https://github.com/einfachArchiv/rechnungsmail.de).

Find yourself stuck using the package? Found a bug? Do you have general questions or suggestions for improvement? Feel free to [create an issue on GitHub](https://github.com/einfachArchiv/rechnungsmail.de/issues), we'll try to address it as soon as possible.

If you've found a security issue, please email [support@einfacharchiv.com](mailto:support@einfacharchiv.com) instead of using the issue tracker.

**Happy coding**!

## Credits

- [Philip Günther](https://github.com/Pag-Man)
- [All Contributors](https://github.com/einfachArchiv/rechnungsmail.de/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
