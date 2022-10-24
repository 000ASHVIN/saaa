<?php

namespace App\Handesk;

use Illuminate\Notifications\Notifiable;

class Requester extends BaseModel
{

    public static function findOrCreate($name, $email = null)
    {
        if (! $email) {
            return self::firstOrCreate(['name' => $name]);
        }

        $requester = self::where('email', '=', $email)->first();
        if($requester) {
            return $requester;
        }
        return self::create(['email' => $email, 'name' => $name]);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function ideas()
    {
        return $this->hasMany(Idea::class);
    }

    public function openTickets()
    {
        return $this->tickets()->where('status', '<', Ticket::STATUS_SOLVED);
    }

    public function solvedTickets()
    {
        return $this->tickets()->where('status', '=', Ticket::STATUS_SOLVED);
    }

    public function closedTickets()
    {
        return $this->tickets()->where('status', '=', Ticket::STATUS_CLOSED);
    }

    public function shouldBeNotified(){
        return $this->no_reply == false;
    }
}
