<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;

class BookingList extends Model
{
    use HasFactory, SoftDeletes, Uuids;

    public $incrementing = false;
    protected $table = 'bookinglist';
    protected $keyType = 'string';
    protected $fillable = ['available', 'status'];

    protected $hidden = [
        'deleted_at',
        'updated_at'
    ];

    public function allbooking()
    {
        return $this->hasMany(Booking::class, 'bookinglist_id')->orderBy('created_at','DESC');
    }

    public function booked()
    {
        return $this->hasOne(Booking::class, 'bookinglist_id','id')->where('confirm', 'Y');
    }
}
