<?php
namespace App\Traits;

use Facades\Str;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid as Generator;

trait Uuids
{

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            try {
                $model->id = Generator::uuid4()->toString();
            } catch (UnsatisfiedDependencyException $e) {
                abort(500, $e->getMessage());
            }
        });
    }
    // public static function bootUuids()
    // {
    //     static::creating(function ($model) {
    //         $model->keyType = 'string';
    //         $model->incrementing = false;

    //         $model->{$model->getKeyName()} = $model->{$model->getKeyName()} ?: (string) Str::orderedUuid();
    //     });
    // }
    
    // public function getIncrementing()
    // {
    //     return false;
    // }
    
    // public function getKeyType()
    // {
    //     return 'string';
    // }
}