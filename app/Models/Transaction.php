<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'payer_id', 
        'payee_id', 
        'amount'
    ];

    public function payer()
    {
        return $this->hasOne(User::class, 'payer_id');
    }

    public function payee()
    {
        return $this->hasOne(User::class, 'payee_id');
    }
}
