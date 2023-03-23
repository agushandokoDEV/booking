<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Traits\Uuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;

class Menu extends Model
{
    use HasFactory,SoftDeletes, Uuids;

    public $incrementing = false;
    protected $table='menu';
    protected $keyType = 'string';
    protected $fillable=['title','code','url','sorting','parent_id','description','active','is_menu'];

    protected $hidden = [
        'deleted_at',
        'updated_at'
    ];
}
