<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;
use Carbon\Carbon;

class BookingListTime extends Model
{
    use HasFactory, SoftDeletes, Uuids;

    public $incrementing = false;
    protected $table = 'bookinglist_time';
    protected $keyType = 'string';
    protected $fillable = ['available_at','bookinglist_id', 'status', 'can_book'];

    protected $hidden = [
        'deleted_at',
        'updated_at'
    ];

    public function getAvailableAtAttribute($value)
    {
        return Carbon::parse($value)->format('H:i');
    }

    public function booked()
    {
        return $this->belongsTo(Booking::class, 'bookinglisttime_id', 'id')->where('confirm', 'Y');
    }

    public function allbooking()
    {
        return $this->hasMany(Booking::class, 'bookinglisttime_id');
    }
}
