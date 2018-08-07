<?php

namespace einfachArchiv\Rechnungsmail;

use CommerceGuys\Intl\Currency\CurrencyRepository;

class Rechnungsmail
{
    /**
     * The email body.
     *
     * @var string
     */
    protected $email;

    /**
     * @param string $email The email body
     */
    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * Checks if the email uses the rechnungsmail.de format.
     *
     * @return bool
     */
    public function isValid()
    {
        return false !== strpos($this->email, 'rechnungsmail.de');
    }

    /**
     * Extracts the invoice id from the email body.
     *
     * @return mixed
     */
    public function getInvoiceId()
    {
        preg_match('/^Rechnungsnummer:\s+(.+)$/im', $this->email, $matches);

        return $matches[1] ?? null;
    }

    /**
     * Extracts the invoice date from the email body.
     *
     * @return mixed
     */
    public function getInvoiceDate()
    {
        preg_match('/^Rechnungsdatum:\s+([0-9]{2}\.[0-9]{2}\.[0-9]{4}|[0-9]{4}-[0-9]{2}-[0-9]{2})$/im', $this->email, $matches);

        return isset($matches[1]) && false !== strtotime($matches[1]) ? $matches[1] : null;
    }

    /**
     * Extracts the invoice amount from the email body.
     *
     * @return mixed
     */
    public function getInvoiceAmount()
    {
        preg_match('/^Rechnungsbetrag:\s+(-?[0-9]+,?[0-9]*)\s([A-Z]{3})$/im', $this->email, $matches);

        return isset($matches[2]) && array_key_exists($matches[2], (new CurrencyRepository())->getList()) ? [
            'amount' => (float) str_replace(',', '.', $matches[1]),
            'currency' => $matches[2],
        ] : null;
    }

    /**
     * Extracts the customer id from the email body.
     *
     * @return mixed
     */
    public function getCustomerId()
    {
        preg_match('/^Kundennummer:\s+(.+)$/im', $this->email, $matches);

        return $matches[1] ?? null;
    }

    /**
     * Extracts the net amount from the email body.
     *
     * @return mixed
     */
    public function getNetAmount()
    {
        preg_match('/^Nettobetrag\s([0-9]{1,2},?[0-9]*)%:\s+(-?[0-9]+,?[0-9]*)\s([A-Z]{3})$/im', $this->email, $matches);

        return isset($matches[3]) && array_key_exists($matches[3], (new CurrencyRepository())->getList()) ? [
            'amount' => (float) str_replace(',', '.', $matches[2]),
            'currency' => $matches[3],
            'taxRate' => (float) str_replace(',', '.', $matches[1]),
        ] : null;
    }

    /**
     * Extracts the tax from the email body.
     *
     * @return mixed
     */
    public function getTax()
    {
        preg_match('/^Umsatzsteuer\s([0-9]{1,2},?[0-9]*)%:\s+(-?[0-9]+,?[0-9]*)\s([A-Z]{3})$/im', $this->email, $matches);

        return isset($matches[3]) && array_key_exists($matches[3], (new CurrencyRepository())->getList()) ? [
            'amount' => (float) str_replace(',', '.', $matches[2]),
            'currency' => $matches[3],
            'taxRate' => (float) str_replace(',', '.', $matches[1]),
        ] : null;
    }

    /**
     * Extracts the sender name from the email body.
     *
     * @return mixed
     */
    public function getSenderName()
    {
        preg_match('/^Absender-Name:\s+(.+)$/im', $this->email, $matches);

        return $matches[1] ?? null;
    }

    /**
     * Extracts the order id from the email body.
     *
     * @return mixed
     */
    public function getOrderId()
    {
        preg_match('/^Ihre Auftragsnummer:\s+(.+)$/im', $this->email, $matches);

        return $matches[1] ?? null;
    }

    /**
     * Extracts the contract id from the email body.
     *
     * @return mixed
     */
    public function getContractId()
    {
        preg_match('/^Vertragsnummer:\s+(.+)$/im', $this->email, $matches);

        return $matches[1] ?? null;
    }
}
