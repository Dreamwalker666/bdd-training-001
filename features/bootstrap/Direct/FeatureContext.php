<?php

namespace Direct;

use Acme\Account\FileBasedAccountRepository;
use Acme\Account\InMemoryAccountRepository;
use Acme\Bank;
use Acme\Transfer;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use PHPUnit\Framework\Assert;
use Acme\BankAccount;
use RuntimeException;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    /**
     * @var RuntimeException
     */
    private $exception;

    /**
     * @var FileBasedAccountRepository
     */
    private $currentAccountRepository;

    /**
     * @var FileBasedAccountRepository
     */
    private $premiumAccountRepository;


    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->currentAccountRepository = new FileBasedAccountRepository('current_account');
        $this->premiumAccountRepository = new FileBasedAccountRepository('premium_account');

        // Swap to in-memory:
        //   $this->currentAccountRepository = new InMemoryAccountRepository('current_account');
        //   $this->premiumAccountRepository = new InMemoryAccountRepository('premium_account');
    }

    /**
     * @Given the balance on my current account is £:balance
     */
    public function theBalanceOnMyCurrentAccountIsPs(float $balance)
    {
        $this->currentAccountRepository->setBalance($balance);
    }

    /**
     * @Given the balance on my premium account is £:balance
     */
    public function theBalanceOnMyPremiumAccountIsPs(float $balance)
    {
        $this->premiumAccountRepository->setBalance($balance);
    }

    /**
     * @When I transfer £:amount from my current account to my premium account
     */
    public function iTransferPsFromMyCurrentAccountToMyPremiumAccount(float $amount)
    {
        $bank = new Bank();
        $transfer = Transfer::amount($amount)
            ->from(new BankAccount($this->currentAccountRepository))
            ->to(new BankAccount($this->premiumAccountRepository));

        try {
            $bank->execute($transfer);
        } catch (RuntimeException $e) {
            $this->exception = $e;
        }
    }

    /**
     * @Then I should have a closing balance of £:balance on my current account
     */
    public function iShouldHaveAClosingBalanceOfPsOnMyCurrentAccount(float $balance)
    {
        Assert::assertEquals(
            $balance,
            $this->currentAccountRepository->getBalance()
        );
    }

    /**
     * @Then I should have a closing balance of £:balance on my premium account
     */
    public function iShouldHaveAClosingBalanceOfPsOnMyPremiumAccount(float $balance)
    {
        Assert::assertEquals(
            $balance,
            $this->premiumAccountRepository->getBalance()
        );
    }

    /**
     * @Then I should be told that I cannot transfer more money than I have in my account
     */
    public function iShouldBeToldThatICannotTransferMoreMoneyThanIHaveInMyAccount()
    {
        Assert::assertEquals(
            'You do not have enough money in your account to make this transfer',
            $this->exception->getMessage()
        );
    }

    /**
     * @When I transfer £:amount from my premium to current account
     */
    public function iTransferPsFromMyPremiumToCurrentAccount(float $amount)
    {
        $bank = new Bank();
        $transfer = Transfer::amount($amount)
            ->from(new BankAccount($this->premiumAccountRepository))
            ->to(new BankAccount($this->currentAccountRepository));

        try {
            $bank->execute($transfer);
        } catch (RuntimeException $e) {
            $this->exception = $e;
        }
    }
}
