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

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    public static function getAllHousekeepingOrders()
    {
        $housekeepings = Housekeeping::all();
        if (count($housekeepings) > 0) {
            $serviceName = Services::getServiceName($housekeepings[0]->service_id);
            foreach ($housekeepings as $key => $housekeeping) {
                $housekeeping->serviceName = $serviceName;
                $housekeeping->roomNumber  = ($housekeeping->guest->rooms[0]->number) ? $housekeeping->guest->rooms[0]->number
                    : 'Housekeeping id:' . $housekeeping->id;
            }
        } else {
            $housekeeping = [];
        }

        return $housekeeping;
    }
}
