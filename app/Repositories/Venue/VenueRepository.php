<?php
/**
 * Created by PhpStorm.
 * User: Tiaan Theunissen
 * Date: 2/2/2017
 * Time: 5:42 PM
 */

namespace App\Repositories\Venue;


use App\AppEvents\Venue;

class VenueRepository
{
    private $venue;
    public function __construct(Venue $venue)
    {
        $this->venue = $venue;
    }

    public function findVenue($id)
    {
        return $this->venue->find($id);
    }

    public function createVenue($data)
    {
        $venue = $this->venue->create([
            'name' => $data['name'],
            'type' => $data['type'],
            'city' => $data['city'],
            'country' => $data['country'],
            'province' => $data['province'],
            'is_active' => $data['is_active'],
            'area_code' => $data['area_code'],
            'address_line_two' => $data['address_line_two'],
            'address_line_one' => $data['address_line_one'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
        ]);
        return $venue;
    }


}