 Feature: Simple transfer

     Rules:
     - A customer has two accounts Current and Premium
     - Balance on either is not allowed to go below 0
     - Customer can transfer money from one to the other
     - Transferring money from Current to premium has no fee
     - Transfers over £100 are charged a %1 fee (deducted from origination account balance)

     - Transferring money from Premium to Current incurs a £0.3 flat fee
     - If transfer amount is over £100 and its from P to C -> %1 is charged first, then 30p deducted
     - Transfers must be of a minimum £5

    @javascript
    Scenario: Simple transfer of money from Current to premium
        Given the balance on my current account is £15
        And the balance on my premium account is £5
        When I transfer £7 from my current account to my premium account
        Then I should have a closing balance of £8 on my current account
        And I should have a closing balance of £12 on my premium account

    Scenario: Transfer from current to premium when I don't have enough in current
        Given the balance on my current account is £5
        And the balance on my premium account is £10
        When I transfer £7 from my current account to my premium account
        Then I should be told that I cannot transfer more money than I have in my account

    Scenario: Incurring %1 fee for transfers over £100
        Given the balance on my current account is £150
        And the balance on my premium account is £100
        When I transfer £110 from my current account to my premium account
        Then I should have a closing balance of £38.9 on my current account
        And I should have a closing balance of £210 on my premium account

    Scenario: Including a fee when transferring from premium to current
        Given the balance on my current account is £100
        And the balance on my premium account is £200
        When I transfer £100 from my premium to current account
        And I should have a closing balance of £99.7 on my premium account
        And I should have a closing balance of £200 on my current account

     Scenario: Charging 1% fee for over £100 transfer plus fee for transferring from premium to current
         Given the balance on my current account is £100
         And the balance on my premium account is £200
         When I transfer £110 from my premium to current account
         And I should have a closing balance of £88.60 on my premium account
         And I should have a closing balance of £210 on my current account

