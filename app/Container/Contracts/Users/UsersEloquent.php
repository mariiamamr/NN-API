<?php


namespace App\Container\Contracts\Users;


use App\User;
// //use App\Admin;
use App\File;
// use App\Models\Certificate;
use App\Container\Contracts\Users\UsersContract;
use CApp\Container\ontracts\UserInfos\UserInfosContract;
// use Contracts\Files\FilesContract;
use App\Models\Grade;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
// use App\Container\Contracts\Roles\RolesContract;
// use App\Models\Permission;
// //use App\Notifications\Admin\AdminNotification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Notification;
use App\Models\Subject;

class UsersRepo implements UsersContract
{
    public $number_of_pages = 10;

    protected $addCertificationValidationRules  = [
        'certifications.*.thumb' => 'mimes:jpeg,jpg,png,pdf,svg',
    ];

    protected $addCertificationValidationMessages = [
        'certifications.*.thumb.mimes' => 'The Certifications File must be a file of type: jpeg, jpg, png, pdf, svg.',
    ];


    public function __construct(
        User $user,
        UserInfosContract $user_info,
        FilesContract $file,
        Admin $admin,
        Permission $permission,
        RolesContract $role,
        Subject $subject
    ) {
        $this->user = $user;
        $this->user_info = $user_info;
        $this->file = $file;
        $this->number_of_pages = config('static.pagination.rowsPerPage');
        $this->admin = $admin;
        $this->permission = $permission;
        $this->role = $role;
        $this->subject = $subject;
    }

    public function get($id)
    {
        return $this->user->with('profile', 'specialist_in', 'languages', 'edu_systems', 'latestReviews', 'grades')->find($id);
    }

    public function getAll($type = '')
    {
        return $this->user->where('type', 'like', '%' . $type . '%')->all();
    }

    public function set($data, $social = false)
    {
        $row = [
            'username' => $data->username,
            'email' => $data->email,
            "gender" => (isset($data->gender)) ? $data->gender : "male",
            "type" => (isset($data->type)) ? $data->type : "s",
        ];

        $user = $this->user->create($row);

        return $user;
    }

    public function update($id, $data)
    {
        return $this->get($id)->update([
            'username' => $data->username,
            'email' => $data->email,
            "gender" => (isset($data->gender)) ? $data->gender : "male",
            "type" => (isset($data->type)) ? $data->type : "s",
        ]);
    }

    public function delete($id)
    {
        return $this->get($id)->delete();
    }

    public function getByEmail($email)
    {
        return $this->user->where('email', $email)->first();
    }

    public function login($data)
    {
        if (isset($data->provider)) {
            $user = $this->findOrCreateSocialUserAPI($data);
            if (gettype($user) == 'intege') {
                return $user;
            }
        } else {
            $user = $this->getByEmail($data->email);

            if (is_null($user)) {
                return config('api.response_code.wrong_credentials');
            }

            //check if current password are the same

            if (!password_verify($data->password, $user->password)) {
                return config("api.response_code.wrong_credentials");
            }
        }
        // all good so return the token
        return $user;
    }

    public function register($data)
    {
        if (isset($data->provider)) {
            $user = $this->findOrCreateSocialUserAPI($data);
            if (gettype($user) == ' integer') {
                return $user;
            }
        } else {
            $user = $this->set($data);
        }
        return $user;
    }

    public function changeUserStatus($id, $status)
    {
        return $this->get($id)->update([
            "status" => (bool) $status
        ]);
    }

    public function paginate($type = ' ', $search = ' ')
    {
        $user = $this->user->where('type', 'like', '%' . $type . '%')->with('profile', 'specialist_in');

        if (isset($search->key) && !empty($search->key)) {
            $user->where(function ($query) use ($search) {
                return $query->where('username', 'like', '%' . $search->key . '%')
                    ->orWhere('full_name', 'like', '%' . $search->key . '%');
            });
        }

        if (isset($search->phone) && $search->phone == 1) {
            $user = $user->whereHas('profile', function ($query) {
                $query->whereNotNull("phone");
            });
        }

        if (isset($search->image) && $search->image == 1) {
            $user = $user->whereNotNull("image_url");
        }

        if (isset($search->status) && $search->status == 1) {
            $user = $user->where("status", 0);
        }

        if (isset($search->price_status) && $search->price_status == 1) {
            $user = $user->whereHas('profile', function ($query) {
                $query->whereJsonLength('price_info->pending', '>', 1);
            });
        }

        if (isset($search->uploads) && $search->uploads == 1) {
            $user = $user->whereHas('profile', function ($query) {
                $query->whereNotNull('certifications');
            });
        }

        if (isset($search->phone_number) && !empty($search->phone_number)) {
            $user->whereHas('profile', function ($query) use ($search) {
                return $query->where('phone', 'like', $search->phone_number . '%')->whereNotNull('phone');
            });
        }

        if (isset($search->subjects) && $search->subjects == 1) {
            $user = $user->whereHas('specialist_in');
        }

        $user = $user->paginate($this->number_of_pages);
        $user = $user->appends([
            'key' => $search->key, 
            'phone' => $search->phone, 
            'image' => $search->image, 
            'status' => $search->status, 
            'price_status' => $search->price_status,
            'uploads' => $search->uploads,
            'phone_number' => $search->phone_number
        ]);
        return $user;
    }

    public function mostRatedTeachersMonthly($limit = 3)
    {
        /*  return collect($this->user_info->mostRatedTeachersMonthly($limit))->transform(function ($profile) {
            return $profile->users;
        }); */

        return $this->user->where('type', 't')
            ->where('status', true)
            ->whereHas('profile', function ($query) use ($limit) {
                return $query
                    ->where('month_rate', ">", 0)
                    ->orderBy('month_rate', 'DESC')
                    ->take($limit);
            })->get();
    }

    public function updateStudentProfile($id, $data)
    {
        \tap($user = $this->get($id))->update([
            'full_name' => $data->full_name,
            'birth' => $data->birth,
            "gender" => (isset($data->gender)) ? $data->gender : "male",
        ]);
        $user->grades()->sync($data->grade);
        return $user;
    }

    public function addSubUsers($id, $data)
    {
        return $this->get($id)->subusers()->create([
            "first_name" => $data->first_name,
            "last_name" => $data->last_name,
            "name" => $data->name,
            "password" => Hash::make($data->password)
        ]);
    }

    public function approvedTeachersPaginate($search = '')
    {
        return $this->user->where('status', 1)
            ->where('type', 't')
            ->where(function ($query) use ($search) {
                return $query->where('username', 'like', '%' . $search . '%')
                    ->orWhere('full_name', 'like', '%' . $search . '%');
            })
            ->with('profile', 'specialist_in')
            ->paginate('6');
    }

    public function approvedFilterdTeachersPaginate($subject)
    {
        if ($subject == 'all') {
            $teachersFilterd = $this->user->where('status', 1)
                ->where('type', 't')
                ->with('profile', 'specialist_in')
                ->paginate('6');
        } else {
            $teachersFilterd = $this->user->where('status', 1)
                ->where('type', 't')
                ->whereHas('specialist_in', function ($query) use ($subject) {
                    $query->where('slug', $subject);
                })
                ->with('profile', 'specialist_in')
                ->paginate('6');
        }

        return $teachersFilterd;
    }

    public function uploadImageProfile($id, $data)
    {
        $user = $this->get($id);
        if (isset($data->image)) {
            $image = $data->image;
            if ($image->isValid()) {
                $user->image_url = $this->file->uploadTeacherProfile($image);
            }
            $user->save();
            return $user;
        }
    }

    public function searchForTeachers($data, $limit = null)
    {
        $query = $this->user
            ->where('status', 1)
            ->where('active', 1)
            ->where('type', 't');

        if (isset($data->available)) {
            $query = $query->where(function ($query) {
                $query->whereHas('profile', function ($q) {
                    return $q->whereNotNull('weekly');
                })
                    ->orWhereHas('lectures', function ($q) {
                        return $q->where('started', false)
                            ->whereNull('checkout_user_id')
                            ->whereNull('payed_user_id')
                            ->where('date', '>=', date('Y-m-d'));
                    });
            });
        }

        if (isset($data->lang) && !empty($data->lang)) {
            $query = $query->whereHas('languages', function ($query) use ($data) {
                return $query->where("languages.id", $data->lang);
            });
        }

        if (isset($data->subject) && !empty($data->subject)) {
            $query = $query->whereHas('specialist_in', function ($query) use ($data) {
                return $query->where('subjects.id', $data->subject);
            });
        }

        if (isset($data->grade) && !empty($data->grade)) {
            $query = $query->whereHas('grades', function ($query) use ($data) {
                return $query->where("grades.id", $data->grade);
            });
        }

        if (isset($data->rate) && !empty($data->rate)) {
            $query = $query->whereHas('profile', function ($q) use ($data) {
                $q = $q->orderBy('month_rate', 'DESC');
                if (isset($data->rate) && !empty($data->rate)) {
                    $q = $q->where('month_rate', ">=", $data->rate);
                }
                return $q;
            });
        }

        if (isset($data->price) && !empty($data->price)) {
            $query = $query->whereHas('profile', function ($q) use ($data) {
                $q = $q->orderBy(DB::raw('JSON_VALUE(`price_info`," $.individual")'), 'DESC')
                    ->where(function ($q) use ($data) {
                        return $q->whereRaw('JSON_VALUE(`price_info`," $.individual") = ' . $data->price)->orWhereRaw('JSON_VALUE(`price_info`," $.group") = ' . $data->price);
                    });
                return $q;
            });
        }
        return (is_null($limit)) ? $query->paginate($this->number_of_pages) : $query->limit($limit)->get();
    }


    public function searchBannerForTeachers($data)
    {
        $query = $this->user
            ->where('status', 1)
            ->where('active', 1)
            ->where('type', 't')
            ->with('profile', 'specialist_in', 'languages', 'grades');


        if (isset($data->lang) && !empty($data->lang)) {
            $query = $query->whereHas('languages', function ($query) use ($data) {
                return $query->where("languages.id", $data->lang);
            });
        }

        if (isset($data->subject) && !empty($data->subject)) {
            $query = $query->whereHas('specialist_in', function ($query) use ($data) {
                $query->where("subjects.id", $data->subject);
            });
        }

        if (isset($data->grade) && !empty($data->grade)) {
            $query = $query->whereHas('grades', function ($query) use ($data) {
                return $query->where("grades.id", $data->grade);
            });
        }

        if (isset($data->rate) && !empty($data->rate)) {
            $query = $query->whereHas('profile', function ($q) use ($data) {
                $q = $q->orderBy('month_rate', 'DESC');
                if (isset($data->rate) && !empty($data->rate)) {
                    $q = $q->where('month_rate', ">=", $data->rate);
                }
                return $q;
            });
        }

        if (isset($data->price) && !empty($data->price)) {
            $query = $query->whereHas('profile', function ($q) use ($data) {
                $q = $q->orderBy(DB::raw('JSON_VALUE(`price_info`," $.individual")'), 'DESC')
                    ->where(function ($q) use ($data) {
                        return $q->whereRaw('JSON_VALUE(`price_info`," $.individual") = ' . $data->price)->orWhereRaw('JSON_VALUE(`price_info`," $.group") = ' . $data->price);
                    });
                return $q;
            });
        }

        if (isset($data->available)) {
            $query = $query->where(function ($query) {
                $query->whereHas('profile', function ($q) {
                    return $q->whereNotNull('weekly');
                })
                    ->orWhereHas('lectures', function ($q) {
                        return $q->where('started', false)
                            ->whereNull('checkout_user_id')
                            ->whereNull('payed_user_id')
                            ->where('date', '>=', date('Y-m-d'));
                    });
            });
        }

        return $query->paginate('6');
    }

    public function updateTeacherProfile($id, $data)
    {
        $user = $this->get($id);
        if (isset($data->full_name)) {
            \tap($user->update([
                'full_name' => $data->full_name,
                'birth' => $data->date,
                "gender" => (isset($data->gender)) ? $data->gender : "male",
            ]));
        }

        if (isset($data->image)) {
            $image = $data->image;
            $user->image_url = $this->file->uploadTeacherProfile($image);
            $user->save();
        }

        if (isset($data->subjects)) {
            $subjects = array_values(array_filter($data->subjects, function ($item) {
                return  preg_replace('/[^\d]/', '', $item);
            }));
            $data['suggested_subjects'] = array_values(array_filter($data->subjects, function ($item) {
                return !preg_replace('/[^\d]/', '', $item) && !is_null($item);
            }));

            $user->specialist_in()->sync(array_filter($subjects));
        }

        if (isset($data->languages)) {
            $user->languages()->sync(array_filter($data->languages));
        }

        if (isset($data->grades)) {
            $user->grades()->sync(array_filter($data->grades));
        }


        if (isset($data->edu_systems)) {
            $user->edu_systems()->sync(array_filter($data->edu_systems));
        }

        if (isset($data->certifications)) {

            //Validation for uploading file extenstions
            $data->validate($this->addCertificationValidationRules, $this->addCertificationValidationMessages);

            // Array for save the ids of files in uploading
            $certificates_array = [];
            foreach ($data->certifications as $key => $certificate) {
                if (isset($certificate['thumb'])) {
                    $file = $certificate['thumb'];
                    if ($file->isValid()) {
                        $fileName = $this->file->uploadCertificate($file);
                        $certificates_array[$key] = [
                            'certificate_id' => $certificate['certificate_id'],
                            'thumb_name' => $fileName
                        ];
                    }
                } else {
                    $certificates_array[$key] = [
                        'certificate_id' => $certificate['certificate_id'] ? $certificate['certificate_id'] : null,
                        'thumb_name' => $certificate['thumb_name'] ? $certificate['thumb_name'] : null
                    ];
                }
            }
            $data->certifications = json_encode($certificates_array);
            $user->profile->update([
                'certifications' => $data->certifications
            ]);
        }

        if (isset($data->price_info)) {
            $permission = $this->permission->where('name', 'teacher-price_approval')->first();
            $roles = $permission->roles()->get()->pluck('id')->toArray();
            $admins = $this->admin->whereIn('role_id', $roles)->get();
            $message = 'Price approval of teacher needing for approval!';
            Notification::send($admins, new AdminNotification($message));
        }

        if (isset($data->payment_info)) {
            $payment = $data->payment_info;
            $payment_array = [];
            $payment_array[] = [
                'bank_name' => $payment['bank_name'] ? $payment['bank_name'] : null,
                'full_payment_name' => $payment['full_payment_name'] ? $payment['full_payment_name'] : null,
                'code' => $payment['code'] ? $payment['code'] : null,
                'account_number' => $payment['account_number'] ? $payment['account_number'] : null,
                'bank_address' => $payment['bank_address'] ? $payment['bank_address'] : null,
                'bank_city' => $payment['bank_city'] ? $payment['bank_city'] : null,
                'bank_country' => $payment['bank_country'] ? $payment['bank_country'] : null,
                'personal_address' => $payment['personal_address'] ? $payment['personal_address'] : null,
            ];

            $data->payment_info = json_encode($payment_array);
            $user->profile->update([
                'payment_info' => $data->payment_info
            ]);
        }

        if (isset($data->other_subjects)) {
            foreach ($data->other_subjects as $subject) {
                $subjects =  $this->subject->create(['title' => $subject, 'slug' => make_slug($subject)]);
            }

            $subjectsIds = $this->subject->whereIn('title', $data->other_subjects)->get()->pluck('id')->toArray();

            $user->specialist_in()->attach(array_filter($subjectsIds));


            $other_subjects = json_encode($data->other_subjects);
            $user->profile->update([
                'other_subjects' => $other_subjects
            ]);
        }

        $user->profile = $this->user_info->updateByUserID($user->id, $data, $user->status);

        return $user;
    }

    public function getWith($id, $array)
    {
        return $this->user->with($array)->find($id);
    }

    public function teacherWithProfile($search = ' ')
    {
        return $this->user->where('type', 't')->where(function ($query) use ($search) {
            return $query->where('username', 'like', '%' . $search . '%')
                ->orWhere('full_name', 'like', '%' . $search . '%');
        })->whereHas('profile')->paginate($this->number_of_pages);
    }

    public function paidSessions($user)
    {
        return $user->registeredSessions()->where('payed', true)->get();
    }
}
