<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = ['account_id', 'description', 'amount', 'type'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $casts = [
        'id'        =>  'Int',
        'user_id'   =>  'Int',
        'amount'    =>  'Float'
    ];

    public function account(): BelongsTo {
        return $this->belongsTo(Account::class);
    }

    public function scopeByLoggedInUser($query){
        if (!request()->user()){
            return $query;
        }
        return $query->whereHas('account', function($query){
            $query->where('user_id', request()->user()->id);
        });
    }
}
