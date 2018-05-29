<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Housekeeping extends Model
{

    protected $fillable
        = [
            'guest_id',
            'order_date',
            'bed_sheets',
            'cleaning',
            'minibar',
            'blanket',
            'toiletries',
            'pillow',
            'price',
        ];

    protected $attributes
        = [
            'service_id' => 6,
        ];
}