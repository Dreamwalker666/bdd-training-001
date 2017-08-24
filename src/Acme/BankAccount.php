<?php

namespace Acme;

use Acme\Account\AccountRepository;

class BankAccount
{
    /**
     * @var AccountRepository
     */
    private $accountRepository;

    /**
     * @param AccountRepository $accountRepository
     */
    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function debit(float $amount)
    {
        $oldBalance = $this->accountRepository->getBalance();

        $newBalance = $oldBalance - $amount;

        if ($newBalance < 0) {
            throw new \RuntimeException('You do not have enough money in your account to make this transfer');
        }

        // deduct %1 fee
        if ($amount > 100) {
            $newBalance -= $amount * 0.01;
        }

        if ($this->accountRepository->isPremium()) {
            $newBalance -= 0.3;
        }

        $this->accountRepository->setBalance($newBalance);
    }

    public function credit($amount)
    {
        $oldBalance = $this->accountRepository->getBalance();

        $newBalance = $oldBalance + $amount;

        $this->accountRepository->setBalance($newBalance);
    }
}
