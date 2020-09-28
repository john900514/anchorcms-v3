<?php

namespace AnchorCMS;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pixels extends Model
{
    use SoftDeletes, Uuid;

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    protected $casts = [
        'id' => 'uuid',
        'client_id' => 'uuid'
    ];

    protected $fillable = ['client_id', 'pixel_id', 'ip'];

    public function activity()
    {
        return $this->hasMany('AnchorCMS\PixelActivity', 'pixel_id', 'pixel_id');
    }
}
