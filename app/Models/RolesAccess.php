<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Traits\Uuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;
class RolesAccess extends Model
{
    use HasFactory, SoftDeletes, Uuids;

    public $incrementing = false;
    protected $table = 'roles_access';
    protected $keyType = 'string';
    protected $fillable=['role_id','menu_id','menu_code','allowed'];

    protected $hidden = [
        'deleted_at',
        'updated_at'
    ];
}
