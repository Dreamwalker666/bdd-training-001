<?php

namespace Acme\Account;

class FileBasedAccountRepository implements AccountRepository
{
    const BASE_DIR = __DIR__ .  '/../../../';

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
        file_put_contents(static::BASE_DIR.$this->type, $balance);
    }

    public function getBalance(): float
    {
        return (float) file_get_contents(static::BASE_DIR.$this->type);
    }

    public function isPremium(): bool
    {
        return $this->type === AccountRepository::TYPE_PREMIUM;
    }
}
