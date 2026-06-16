<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SmCanteenTransaction extends Model
{
    protected $table = 'sm_canteen_transactions';
    protected $guarded = ['id'];

    public function wallet() { return $this->belongsTo(SmCanteenWallet::class, 'wallet_id'); }
    public function student() { return $this->belongsTo(\App\SmStudent::class, 'student_id'); }
    public function item() { return $this->belongsTo(SmCanteenItem::class, 'item_id'); }
}
