<?php

require_once 'vendor/autoload.php';

use Behat\Mink\Driver\Selenium2Driver;
use Behat\Mink\Mink,
    Behat\Mink\Session,
    Behat\Mink\Driver\GoutteDriver,
    Behat\Mink\Driver\Goutte\Client as GoutteClient;



$mink = new Mink(array(
    'goutte' => new Session(new GoutteDriver(new GoutteClient())),
    'selenium' => new Session(new Selenium2Driver('chrome')),
));

$mink->setDefaultSessionName('goutte');

$mink->getSession()->visit('http://localhost:8000');

$page = $mink->getSession()->getPage();

$page->fillField('amount', 10);

$page->pressButton('Transfer');
