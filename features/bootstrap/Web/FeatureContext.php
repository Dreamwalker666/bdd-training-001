<?php

namespace Web;

use Acme\Account\AccountRepository;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Mink\Driver\BrowserKitDriver;
use Behat\Mink\Session;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use PHPUnit\Framework\Assert;
use RuntimeException;
use Symfony\Component\HttpKernel\Client;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends RawMinkContext implements KernelAwareContext
{
    /**
     * @var RuntimeException
     */
    private $exception;

    /**
     * @var AccountRepository
     */
    private $currentAccountRepository;

    /**
     * @var AccountRepository
     */
    private $premiumAccountRepository;
    /**
     * @var Kernel
     */
    private $kernel;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     * @param AccountRepository $currentAccountRepository
     * @param AccountRepository $premiumAccountRepository
     */
    public function __construct(AccountRepository $currentAccountRepository, AccountRepository $premiumAccountRepository)
    {
        $this->currentAccountRepository = $currentAccountRepository;
        $this->premiumAccountRepository = $premiumAccountRepository;

    }

    /**
     * @BeforeScenario
     * @param BeforeScenarioScope $scope
     */
    public function setup(BeforeScenarioScope $scope)
    {
        $this->getMink()->registerSession('test', new Session(new BrowserKitDriver(new Client($this->kernel))));
        $this->getMink()->setDefaultSessionName('test');
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

    /**
     * Sets Kernel instance.
     *
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }
}
