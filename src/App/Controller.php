<?php

namespace App;

use Acme\Account\FileBasedAccountRepository;
use Acme\Bank;
use Acme\BankAccount;
use Acme\Transfer;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Controller
{
    const TEMPLATE = <<<HTML
<html>
    <form method="POST">
        <label>Amount <input name="amount"></label>
        <label>From <select name="from_account">
                <option>current_account</option>
                <option>premium_account</option>
            </select>
        </label>

        <label>To <select name="to_account">
                <option>premium_account</option>
                <option>current_account</option>
            </select>
        </label>

        <input type="submit" value="Transfer">
    </form>
</html>
HTML;
    /**
     * @var BankAccountFactory
     */
    private $accountFactory;

    /**
     * @param BankAccountFactory $accountFactory
     */
    public function __construct(BankAccountFactory $accountFactory)
    {
        $this->accountFactory = $accountFactory;
    }


    public function index(Request $request)
    {
        if ($request->isMethod('POST')) {
            $bank = new Bank();
            $transfer = Transfer::amount($request->request->get('amount'))
                ->from($this->accountFactory->create($request->request->get('from_account')))
                ->to($this->accountFactory->create($request->request->get('to_account')));

            try {
                $bank->execute($transfer);
            } catch (RuntimeException $e) {
                return new Response($e->getMessage(), 500);
            }
        }

        return new Response(static::TEMPLATE);
    }
}
