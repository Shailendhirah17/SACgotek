<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SmCanteenWallet extends Model
{
    protected $table = 'sm_canteen_wallets';
    protected $guarded = ['id'];

    public function student() { return $this->belongsTo(\App\SmStudent::class, 'student_id'); }
    public function parent() { return $this->belongsTo(\App\User::class, 'parent_id'); }
    public function transactions() { return $this->hasMany(SmCanteenTransaction::class, 'wallet_id'); }
}
