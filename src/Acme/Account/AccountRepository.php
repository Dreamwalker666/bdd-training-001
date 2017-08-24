<?php

namespace Acme\Account;

interface AccountRepository
{
    const TYPE_PREMIUM = 'premium_account';

    const TYPE_CURRENT = 'current_account';

    public function setBalance(float $balance);
    
    public function getBalance(): float;

    public function isPremium(): bool;
}
