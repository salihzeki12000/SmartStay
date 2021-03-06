<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{

    protected $fillable = ['firstname', 'lastname', 'nif', 'email', 'telephone', 'balance'];

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'guest_room', 'guest_id', 'room_id')
            ->withPivot('checkin_date', 'checkout_date')
            ->withTimestamps();
    }

    public function alarms()
    {
        return $this->hasMany(Alarm::class);
    }

    public function taxis()
    {
        return $this->hasMany(Taxi::class);
    }

    public function restaurants()
    {
        return $this->hasMany(Restaurant::class);
    }

    public function snacks()
    {
        return $this->hasMany(SnacksAndDrink::class);
    }

    public function houseKeepings()
    {
        return $this->hasMany(Housekeeping::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    public function petCares()
    {
        return $this->hasMany(PetCare::class);
    }

    public function spas()
    {
        return $this->hasMany(SpaAppointment::class);
    }


    //Métodos del modelo Guest
    public static function getGuestsByCheckoutDate()
    {
        $guests = Guest::whereHas('rooms', function ($q) {
            $q->where('checkout_date', '=', Carbon::today()->toDateString());
        })->paginate(15);

        return $guests;
    }

    public static function getGuestsByCheckinDate()
    {
        $guests = Guest::whereHas('rooms', function ($q) {
            $q->where('checkin_date', '=', Carbon::today()->toDateString());
        })->paginate(15);

        return $guests;
    }

    public static function getLastFiveDaysCheckinDate()
    {
        $lastCheckin = [];
        for ($i = 5; $i >= 0; $i--) {
            $day = (new \Carbon\Carbon)->subDays($i);
            $guests = Guest::whereHas('rooms', function ($q) use ($day) {
                $q->where('checkin_date', '=', $day->toDateString());
            })->get();
            $lastCheckin[$day->format('D')] = $guests->count();
        }
        return $lastCheckin;
    }

    public static function getLastFiveDaysCheckoutDate()
    {
        $lastCheckin = [];
        for ($i = 5; $i >= 0; $i--) {
            $day = (new \Carbon\Carbon)->subDays($i);
            $guests = Guest::whereHas('rooms', function ($q) use ($day) {
                $q->where('checkout_date', '=', $day->toDateString());
            })->get();
            $lastCheckin[$day->format('D')] = $guests->count();
        }
        return $lastCheckin;
    }

    public static function getRoomByGuestId($id)
    {
        $room = self::find($id)->rooms[0];

        return $room;
    }

    public static function getCheckoutByGuestId($id)
    {
        return self::find($id)->rooms[0]->pivot->checkout_date;
    }

    public static function updateBalance($service)
    {
        if ($service->status == 2) {
            $guest = self::find($service->guest_id);
            $guest->balance += $service->price;
            $guest->save();
        }
    }

    public static function reduceBalance($service)
    {
        $guest = self::find($service->guest_id);
        $guest->balance -= $service->price;
        $guest->save();
    }
}
