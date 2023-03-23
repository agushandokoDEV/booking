<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;

class Booking extends Model
{
    use HasFactory, SoftDeletes, Uuids;

    public $incrementing = false;
    protected $table = 'booking';
    protected $keyType = 'string';
    protected $fillable = ['bookinglist_id', 'booking_name','email','token','confirm','token_exp','ket'];

    protected $hidden = [
        'deleted_at',
        'updated_at'
    ];
}
