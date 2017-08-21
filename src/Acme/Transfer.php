<?php
/**
 * Created by PhpStorm.
 * User: jacker
 * Date: 21/08/2017
 * Time: 10:45
 */

namespace Acme;


class Transfer
{
    private $amount;

    /**
     * @var BankAccount
     */
    private $from;


    /**
     * @var BankAccount
     */
    private $to;

    public static function amount(float $amount)
    {

        $transfer =  new static();
        $transfer->amount = $amount;

        return $transfer;
    }

    public function from(BankAccount $account)
    {
        $this->from = $account;

        return $this;
    }

    public function to(BankAccount $account)
    {
        $this->to = $account;

        return $this;
    }

    /**
     * @return BankAccount
     */
    public function getFrom(): BankAccount
    {
        return $this->from;
    }

    /**
     * @return BankAccount
     */
    public function getTo(): BankAccount
    {
        return $this->to;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }
}
