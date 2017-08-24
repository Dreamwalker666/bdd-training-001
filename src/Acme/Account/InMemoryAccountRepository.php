<?php

namespace Acme\Account;

class InMemoryAccountRepository implements AccountRepository
{

    private $balance;

    /**
     * @var string
     */
    private $type;

    /**
     * FileBasedAccountRepository constructor.
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function setBalance(float $balance)
    {
        $this->balance = $balance;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function isPremium(): bool
    {
        return $this->type === AccountRepository::TYPE_PREMIUM;
    }
}
