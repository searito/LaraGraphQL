<?php

namespace App\Observers;

use App\Account;
use App\Transaction;

class TransactionObserver
{
    public function created(Transaction $transaction){
        return $this->calculateNewAccountBalance($transaction);
    }

    public function updating(Transaction $transaction){
        request()->merge([
            'old_transaction'    =>  Transaction::find($transaction->id)->toArray()
        ]);
    }

    public function updated(Transaction $transaction){
        $this->setAccountBalanceFromOldTransaction($transaction);
        return $this->calculateNewAccountBalance($transaction);
    }

    public function setAccountBalanceFromOldTransaction(Transaction $transaction): Account{
        $account = $transaction->account;
        $old_transaction = (object) request()->get('old_transaction');

        if ($old_transaction->type === 'INCOME'){
            $account->balance = $account->balance - $old_transaction->amount;
            return $account;
        }
        $account->balance = $account->balance + $old_transaction->amount;
        return $account;
    }

    public function calculateNewAccountBalance(Transaction $transaction){
        $account = $transaction->account;
        if ($transaction->type === 'INCOME'){
            $account->balance = $account->balance + $transaction->amount;
            return $account->save();
        }
        $account->balance = $account->balance - $transaction->amount;
        return $account->save();
    }
}
