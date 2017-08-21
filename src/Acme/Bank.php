<?php

namespace Acme;

use RuntimeException;

class Bank
{
    public function execute(Transfer $transfer)
    {
        $transfer->getFrom()->debit($transfer->getAmount());
        $transfer->getTo()->credit($transfer->getAmount());
    }
}
