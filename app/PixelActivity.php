<?php

namespace AnchorCMS;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PixelActivity extends Model
{
    use SoftDeletes, Uuid;

    protected $table = 'pixel_activity';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    protected $casts = [
        'id' => 'uuid',
        'misc' => 'array'
    ];

    protected $fillable = ['pixel_id', 'activity', 'value', 'misc'];


    public function pixel()
    {
        return $this->belongsTo('AnchorCMS\Pixels', 'pixel_id', 'pixel_id');
    }
}
