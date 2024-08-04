<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'transaction_id', 'amount', 'payment_method'];

    protected static function boot ()
    {
        parent::boot();

        static::creating(function ($payment) {
            $payment->transaction_id = Str::uuid();
        });
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function fees()
    {
        return $this->belongsTo(Fee::class, 'fees_id');
    }
}
