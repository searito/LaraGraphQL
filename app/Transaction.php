<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = ['account_id', 'description', 'amount', 'type'];
    protected $hidden = ['created_at', 'updated_at'];

    public function account(): BelongsTo {
        return $this->belongsTo(Account::class);
    }
}
