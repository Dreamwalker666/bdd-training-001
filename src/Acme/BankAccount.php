<?php

namespace Acme;

class BankAccount
{
    const TYPE_PREMIUM = 'premium_account';
    const TYPE_CURRENT = 'current_account';

    const BASE_DIR = __DIR__ .  '/../../';
    /**
     * @var string
     */
    private $type;

    /**
     * @param string $type
     * @internal param string $string
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public static function current()
    {
        return new static(static::TYPE_CURRENT);
    }

    public static function premium()
    {
        return new static(static::TYPE_PREMIUM);
    }

    public function debit(float $amount)
    {
        $oldBalance = (float) file_get_contents(static::BASE_DIR.$this->type);

        $newBalance = $oldBalance - $amount;

        if ($newBalance < 0) {
            throw new \RuntimeException('You do not have enough money in your account to make this transfer');
        }

        // deduct %1 fee
        if ($amount > 100) {
            $newBalance -= $amount * 0.01;
        }

        if ($this->type === static::TYPE_PREMIUM) {
            $newBalance -= 0.3;
        }

        file_put_contents($this->type, $newBalance);
    }

    public function credit($amount)
    {
        $oldBalance = (float) file_get_contents(static::BASE_DIR.$this->type);

        $newBalance = $oldBalance + $amount;

        file_put_contents($this->type, $newBalance);
    }
}
