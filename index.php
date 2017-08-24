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

<?php

if (!$_POST) {
    exit;
}

require_once 'vendor/autoload.php';

use Acme\Account\FileBasedAccountRepository;
use Acme\Bank;
use Acme\Transfer;
use Acme\BankAccount;

$bank = new Bank();
$transfer = Transfer::amount($_POST['amount'])
    ->from(new BankAccount(new FileBasedAccountRepository($_POST['from_account'])))
    ->to(new BankAccount(new FileBasedAccountRepository($_POST['to_account'])));

try {
    $bank->execute($transfer);
} catch (RuntimeException $e) {
    echo $e->getMessage();
}


