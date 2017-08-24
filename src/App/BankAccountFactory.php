<?php

namespace App;

use Acme\Account\AccountRepository;
use Acme\BankAccount;

class BankAccountFactory
{
    /**
     * @var AccountRepository
     */
    private $premiumAccountRepository;
    /**
     * @var AccountRepository
     */
    private $currentAccountRepository;

    /**
     * BankAccountFactory constructor.
     * @param AccountRepository $premiumAccountRepository
     * @param AccountRepository $currentAccountRepository
     */
    public function __construct(AccountRepository $premiumAccountRepository, AccountRepository $currentAccountRepository)
    {
        $this->premiumAccountRepository = $premiumAccountRepository;
        $this->currentAccountRepository = $currentAccountRepository;
    }

    public function create(string $type)
    {
        if ($type === AccountRepository::TYPE_PREMIUM) {
            return new BankAccount($this->premiumAccountRepository);
        }

        return new BankAccount($this->currentAccountRepository);
    }
}
