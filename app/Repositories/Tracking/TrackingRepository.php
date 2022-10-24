<?php
/**
 * Created by PhpStorm.
 * User: Tiaan-Pc
 * Date: 11/19/2018
 * Time: 11:15 AM
 */

namespace App\Repositories\Tracking;


use App\Note;
use Carbon\Carbon;

class TrackingRepository
{
    public function trackProfileView($type = null, $user, $description, $who)
    {
        $this->AddEntry($type, $user, $description, $who);
    }

    /**
     * @param $type
     * @param $user
     * @param $description
     * @param $who
     * @throws \Exception
     */
    private function AddEntry($type, $user, $description, $who)
    {
        \DB::transaction(function () use ($type, $user, $description, $who) {
            if (! $user->notes->contains('type', $type)) {
                $note = new Note([
                    'type' => ($type ? $type : "general"),
                    'user_id' => $user->id,
                    'logged_by' => ucwords($who->first_name.' '.$who->last_name),
                    'description' => $description,
                ]);
                $note->save();
            } else {

                $notes = $user->notes->where('type', $type);

                if (! $notes->contains('logged_by', ucwords($who->first_name.' '.$who->last_name)) && $notes->where('created_at', 'LIKE', Carbon::now())){
                    $note = new Note([
                        'type' => ($type ? $type : "general"),
                        'user_id' => $user->id,
                        'logged_by' => ucwords($who->first_name.' '.$who->last_name),
                        'description' => $description,
                    ]);
                    $note->save();
                };
            }
        });
    }
}