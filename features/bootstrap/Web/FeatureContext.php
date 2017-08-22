<?php

namespace Web;

use Acme\Bank;
use Acme\Transfer;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\RawMinkContext;
use PHPUnit\Framework\Assert;
use Acme\BankAccount;
use RuntimeException;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends RawMinkContext
{
    /**
     * @var RuntimeException
     */
    private $exception;

    /**
     * @var array
     */
    private $output;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given the balance on my current account is £:balance
     */
    public function theBalanceOnMyCurrentAccountIsPs(float $balance)
    {
        file_put_contents('current_account', $balance);
    }

    /**
     * @Given the balance on my premium account is £:balance
     */
    public function theBalanceOnMyPremiumAccountIsPs(float $balance)
    {
        file_put_contents('premium_account', $balance);
    }

    /**
     * @When I transfer £:amount from my current account to my premium account
     */
    public function iTransferPsFromMyCurrentAccountToMyPremiumAccount(float $amount)
    {
        $this->getSession()->visit($this->getMinkParameter('base_url') . '/');
        $page = $this->getSession()->getPage();

        $page->fillField('amount', $amount);
        $page->selectFieldOption('from_account', 'current_account');
        $page->selectFieldOption('to_account', 'premium_account');

        $page->pressButton('Transfer');
    }

    /**
     * @Then I should have a closing balance of £:balance on my current account
     */
    public function iShouldHaveAClosingBalanceOfPsOnMyCurrentAccount(float $balance)
    {
        Assert::assertEquals(
            $balance,
            (float) file_get_contents('current_account')
        );
    }

    /**
     * @Then I should have a closing balance of £:balance on my premium account
     */
    public function iShouldHaveAClosingBalanceOfPsOnMyPremiumAccount(float $balance)
    {
        Assert::assertEquals(
            $balance,
            (float) file_get_contents('premium_account')
        );
    }

    /**
     * @Then I should be told that I cannot transfer more money than I have in my account
     */
    public function iShouldBeToldThatICannotTransferMoreMoneyThanIHaveInMyAccount()
    {
        $this->assertSession()->pageTextContains(
            'You do not have enough money in your account to make this transfer'
        );
    }

    /**
     * @When I transfer £:amount from my premium to current account
     */
    public function iTransferPsFromMyPremiumToCurrentAccount(float $amount)
    {
        $this->getSession()->visit($this->getMinkParameter('base_url') . '/');
        $page = $this->getSession()->getPage();

        $page->fillField('amount', $amount);
        $page->selectFieldOption('from_account', 'premium_account');
        $page->selectFieldOption('to_account', 'current_account');

        $page->pressButton('Transfer');
    }
}
