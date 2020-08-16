<?php
namespace Repos\Lectures;

use App\Lecture;



class UserEnrolls
{
    public function __construct(Lecture $lecture)
    {
        $this->lecture = $lecture;
    }

    public function enroll($user_id, $data)
    {
        if (is_null($this->lecture->checkout_user_id)) {

            if (!$this->lecture->attendees->contains($user_id)) {

                $this->lecture->attendees()->attach($user_id, [
                    "teacher_id" => $this->lecture->teacher_id,
                    "date" => $this->lecture->date,
                    "time_from" => $this->lecture->time_from,
                    "time_to" => $this->lecture->time_to,
                    "type" => ($data->type == 'group') ? 'group' : 'individual',
                    "price" => ($data->type == 'group') ?
                        $this->lecture->teacher->profile->price_info['group'] ?? 0 :
                        $this->lecture->teacher->profile->price_info['individual'] ?? 0
                ]);
            }

            if (isset($data->sub_users)) {
                $detail = [
                    "user_id" => $user_id,
                    "teacher_id" => $this->lecture->teacher_id,
                    "date" => $this->lecture->date,
                    "time_from" => $this->lecture->time_from,
                    "time_to" => $this->lecture->time_to,
                    "type" => 'group',
                    "price" => $this->lecture->teacher->profile->price_info['group'] ?? 0
                ];
                
                $subusers = array_map(function ($item) use ($detail) {
                    $detail['sub_user_id'] = $item;
                    return $detail;
                }, $data->sub_users);


                $old_sub_user_ids = $this->lecture->sub_attendees()
                    ->where('schedules.user_id', $user_id)->pluck('sub_user_id');

                $this->lecture->sub_attendees()->detach($old_sub_user_ids);
                $this->lecture->sub_attendees()->attach($subusers);
            }

            return $this->lecture->attendees()->get()->contains($user_id);
        }

        return [
            "checkedout" => !is_null($this->lecture->checkout_user_id),
            "payed" => !is_null($this->lecture->payed_user_id),
        ];

    }

}
