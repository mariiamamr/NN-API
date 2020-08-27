<?php

namespace App\Container\Contracts\UserInfos;

use App\UserInfo;

interface UserInfoReviewsContract
{

    public function __construct(UserInfo $profile);
    public function update($teacher_id, $data);
    public function monthly_review($data);
}
