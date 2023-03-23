<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;

class Roles extends Model
{
    use HasFactory, SoftDeletes, Uuids;

    public $incrementing = false;
    protected $table = 'roles';
    protected $keyType = 'string';
    protected $fillable=['name','description'];

    protected $hidden = [
        'deleted_at',
        'updated_at'
    ];
}
