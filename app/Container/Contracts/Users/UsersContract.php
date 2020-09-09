<?php

/**
 * Created by PhpStorm.
 * User: Backend Dev
 * Date: 5/17/2018
 * Time: 11:30 AM
 */

namespace App\Container\Contracts\Users;


use App\User;
use App\Admin;
use App\Container\Contracts\UserInfos\UserInfosContract;
use App\Container\Contracts\Files\FilesContract;
use App\Container\Contracts\Roles\RolesContract;
use App\Models\Permission;
use App\Notifications\Admin\AdminNotification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Notification;
use App\Models\Subject;

interface UsersContract
{
    public function __construct(
        User $user,
        UserInfosContract $user_info,
        FilesContract $file,
        Admin $admin,
        Permission $permission,
        RolesContract $role,
        Subject $subject
    );

    public function get($id);

    public function getAll($type = "");

    public function set($data);

    public function update($id, $data);

    public function delete($id);

    public function getByEmail($email);

    public function login($data);

    public function register($data);

    public function paginate($type = '', $search = '');

    public function changeUserStatus($id, $status);

    public function mostRatedTeachersMonthly($limit = 3);

    public function updateStudentProfile($id, $data);

    public function addSubUsers($id, $data);

    public function approvedTeachersPaginate($search = ' ');

    public function uploadImageProfile($id, $data);

    public function searchForTeachers($data);

    public function getWith($id, $array);

    public function teacherWithProfile($search = ' ');

    public function paidSessions($user);

    public function searchBannerForTeachers($data);
}
