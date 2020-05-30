<?php
namespace App\Libraries;

use App\Account;
use App\Transaction;

class CalculateAccountBalance
{
    public $oldTransaction;

    public function setOldTransaction(Transaction $transaction){
        $this->oldTransaction = $transaction;
    }

    public function calculateNewAccountBalance($transaction){
        $account = $transaction->account;

        if ($transaction->type === 'INCOME'){
            $account->balance = $account->balance + $transaction->amount;
            return $account->save();
        }
        $account->balance = $account->balance - $transaction->amount;
        return $account->save();
    }

    public function setAccountBalanceFromOldTransaction(Transaction $transaction): Account{
        $account = $transaction->account;

        if ($this->oldTransaction->type === 'INCOME'){
            $account->balance = $account->balance - $this->oldTransaction->amount;
            return $account;
        }
        $account->balance = $account->balance + $this->oldTransaction->amount;
        return $account;
    }
}
