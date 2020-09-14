---
title: API Reference

language_tabs:
- bash
- javascript

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Info

Welcome to the generated API reference.
[Get Postman Collection](http://localhost/docs/collection.json)

<!-- END_INFO -->

#Home

Searches for teachers using Subject, language, grade or all of them. Gets the home page if no ilters are applied.
<!-- START_2b349f7f0ce1ce2ae13b3d385ae6e476 -->
## Search

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/home" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"lang":6,"subject":17,"grade":8}'

```

```javascript
const url = new URL(
    "http://localhost/api/home"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "lang": 6,
    "subject": 17,
    "grade": 8
}

fetch(url, {
    method: "GET",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "teachers": {
        "current_page": 1,
        "data": [
            {
                "id": 17,
                "email": "test.1999@gmail.com",
                "email_verified_at": null,
                "created_at": "2020-08-27 14:31:25",
                "updated_at": "2020-08-27 14:31:25",
                "full_name": "wrcefc",
                "type": "t",
                "active": 1,
                "birth": "2000-01-01",
                "gender": "female",
                "status": 1,
                "image_url": null,
                "username": "ayakolj",
                "profile": {
                    "id": 1,
                    "user_id": 17,
                    "nationality": null,
                    "phone": null,
                    "postal_code": null,
                    "exp_years": null,
                    "exp_desc": null,
                    "payment_info": null,
                    "avg_rate": 3,
                    "month_rate": 0,
                    "rank": 0,
                    "rates_count": 1,
                    "courses": null,
                    "certifications": null,
                    "master_degree": null,
                    "weekly": null,
                    "university_degree_id": null,
                    "price_info": {
                        "pending": {
                            "individual": 50,
                            "group": 100
                        }
                    },
                    "national_id": null,
                    "phones": null,
                    "suggested_subjects": null,
                    "other_subjects": null
                },
                "specialist_in": [
                    {
                        "id": 1,
                        "slug": "sub",
                        "title": "subj_test",
                        "created_at": null,
                        "updated_at": null,
                        "is_active": 1,
                        "pivot": {
                            "user_id": 17,
                            "subject_id": 1
                        }
                    },
                    {
                        "id": 1,
                        "slug": "sub",
                        "title": "subj_test",
                        "created_at": null,
                        "updated_at": null,
                        "is_active": 1,
                        "pivot": {
                            "user_id": 17,
                            "subject_id": 1
                        }
                    }
                ],
                "languages": [
                    {
                        "id": 1,
                        "slug": "english",
                        "title": "english",
                        "pivot": {
                            "user_id": 17,
                            "language_id": 1
                        }
                    }
                ],
                "grades": [
                    {
                        "id": 1,
                        "slug": "kg1",
                        "title": "kg1",
                        "created_at": "2020-08-31 16:04:27",
                        "updated_at": "2020-08-31 16:04:27",
                        "pivot": {
                            "user_id": 17,
                            "grade_id": 1
                        }
                    }
                ]
            }
        ],
        "first_page_url": "http:\/\/localhost:8000\/api\/home?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "http:\/\/localhost:8000\/api\/home?page=1",
        "next_page_url": null,
        "path": "http:\/\/localhost:8000\/api\/home",
        "per_page": "6",
        "prev_page_url": null,
        "to": 1,
        "total": 1
    }
}
```

### HTTP Request
`GET api/home`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `lang` | integer |  optional  | the selected language ID if exists.
        `subject` | integer |  optional  | the selected subject ID if exists.
        `grade` | integer |  optional  | the selected grade ID if exists.
    
<!-- END_2b349f7f0ce1ce2ae13b3d385ae6e476 -->

#Notifications

used to get the notification history for the currently logged in user.
<!-- START_e65df2963c4f1f0bfdd426ee5170e8b7 -->
## Notification History

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/notifications" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/notifications"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "notifications": [
        {
            "id": "79947688-9376-4b3f-95fd-6e3f4c31c1f4",
            "type": "App\\Notifications\\ReportNotification",
            "notifiable_type": "App\\User",
            "notifiable_id": 17,
            "data": {
                "message": "Your report has been submitted successfully"
            },
            "read_at": null,
            "created_at": "2020-09-14 19:27:31",
            "updated_at": "2020-09-14 19:27:31"
        },
        {
            "id": "66354ef2-9168-4efc-a13d-942cef01ab87",
            "type": "App\\Notifications\\Admin\\AdminNotification",
            "notifiable_type": "App\\User",
            "notifiable_id": 17,
            "data": {
                "message": "Price approval of teacher needing for approval!"
            },
            "read_at": null,
            "created_at": "2020-09-08 19:10:14",
            "updated_at": "2020-09-08 19:10:14"
        },
        {
            "id": "c5bbb766-cfa9-4b37-9a4e-bc144eb0298f",
            "type": "App\\Notifications\\Admin\\AdminNotification",
            "notifiable_type": "App\\User",
            "notifiable_id": 17,
            "data": {
                "message": "Price approval of teacher needing for approval!"
            },
            "read_at": null,
            "created_at": "2020-09-08 19:06:49",
            "updated_at": "2020-09-08 19:06:49"
        },
        {
            "id": "23ff3d46-bc8f-47cf-9f60-c3c72682c2db",
            "type": "App\\Notifications\\BookSessionStudent",
            "notifiable_type": "App\\User",
            "notifiable_id": 17,
            "data": {
                "message": "Dear wrcefc:\r\n      This is to confirm that you have booked a session:2020-10-20 at 04:00:00\r\n"
            },
            "read_at": null,
            "created_at": "2020-09-02 14:43:50",
            "updated_at": "2020-09-02 14:43:50"
        },
        {
            "id": "07706623-f57b-4d60-95b1-d8e93dbc0093",
            "type": "App\\Notifications\\BookSession",
            "notifiable_type": "App\\User",
            "notifiable_id": 17,
            "data": {
                "message": "Dear wrcefc:\r\n      wrcefc has booked a session with you:\r\n      2020-10-20 at 04:00:00\r\n"
            },
            "read_at": null,
            "created_at": "2020-09-02 14:43:48",
            "updated_at": "2020-09-02 14:43:48"
        },
        {
            "id": "3d346d9f-0d81-4f70-ac8d-94aa6f9207c2",
            "type": "App\\Notifications\\ReportNotification",
            "notifiable_type": "App\\User",
            "notifiable_id": 17,
            "data": {
                "message": "Your report has been submitted successfully"
            },
            "read_at": null,
            "created_at": "2020-09-01 14:46:52",
            "updated_at": "2020-09-01 14:46:52"
        },
        {
            "id": "e40a4875-2ad6-4ea4-aa1e-49e78f6b29a8",
            "type": "App\\Notifications\\ReportNotification",
            "notifiable_type": "App\\User",
            "notifiable_id": 17,
            "data": {
                "message": "Your report has been submitted successfully"
            },
            "read_at": null,
            "created_at": "2020-09-01 14:34:11",
            "updated_at": "2020-09-01 14:34:11"
        },
        {
            "id": "097f3495-b9b3-456f-9f9b-5b5aae0a0f7f",
            "type": "App\\Notifications\\RatingNewTeacher",
            "notifiable_type": "App\\User",
            "notifiable_id": 17,
            "data": {
                "student_id": 18,
                "student_name": "wrcefc",
                "student_image": null,
                "session_date": "2020-10-20",
                "session_time": "04:00:00"
            },
            "read_at": null,
            "created_at": "2020-08-27 18:50:42",
            "updated_at": "2020-08-27 18:50:42"
        }
    ]
}
```

### HTTP Request
`GET api/notifications`


<!-- END_e65df2963c4f1f0bfdd426ee5170e8b7 -->

#Ratings

Used by the student to rate a teacher.
<!-- START_800ad2bc8558de3f527f6ef8b8fcf842 -->
## Rating by student

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X POST \
    "http://localhost/api/ratingbystudent" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"lecture_id":9,"teacher_id":15,"rate":4,"content":"non"}'

```

```javascript
const url = new URL(
    "http://localhost/api/ratingbystudent"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "lecture_id": 9,
    "teacher_id": 15,
    "rate": 4,
    "content": "non"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "message": "rating added"
}
```

### HTTP Request
`POST api/ratingbystudent`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `lecture_id` | integer |  optional  | the ID of the lecture about which the teacher will be rated.
        `teacher_id` | integer |  optional  | the ID of the teacher to be rated.
        `rate` | integer |  optional  | the rating value.
        `content` | string |  optional  | rating message/ comments.
    
<!-- END_800ad2bc8558de3f527f6ef8b8fcf842 -->

<!-- START_472f74a214bfb1bc280784c41dbc5ab4 -->
## Rating by teacher

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X POST \
    "http://localhost/api/ratingbyteacher" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"lecture_id":16,"student_id":7,"rate":6,"content":"eaque"}'

```

```javascript
const url = new URL(
    "http://localhost/api/ratingbyteacher"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "lecture_id": 16,
    "student_id": 7,
    "rate": 6,
    "content": "eaque"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "message": "rating added"
}
```

### HTTP Request
`POST api/ratingbyteacher`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `lecture_id` | integer |  optional  | the ID of the lecture about which the student will be rated.
        `student_id` | integer |  optional  | the ID of the student to be rated.
        `rate` | integer |  optional  | the rating value.
        `content` | string |  optional  | rating message/ comments.
    
<!-- END_472f74a214bfb1bc280784c41dbc5ab4 -->

#Report

used to report a teacher
<!-- START_513d4e19011ae1f92bd8858b5eb059b2 -->
## Report teacher

> Example request:

```bash
curl -X POST \
    "http://localhost/api/report" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"content":"nam","teacher_id":13}'

```

```javascript
const url = new URL(
    "http://localhost/api/report"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "content": "nam",
    "teacher_id": 13
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "message": {
        "email": "ayaelsac.1999@gmail.com",
        "content": "byhjn",
        "user_id": 17,
        "teacher_id": 20,
        "updated_at": "2020-09-14 19:27:06",
        "created_at": "2020-09-14 19:27:06",
        "id": 4
    }
}
```

### HTTP Request
`POST api/report`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `content` | string |  required  | the report message.
        `teacher_id` | integer |  required  | the ID of the teacher to be reported.
    
<!-- END_513d4e19011ae1f92bd8858b5eb059b2 -->

#Sessions

used to get past sessions of the students
<!-- START_a43f2788c329b827d52d8040d628e47c -->
## get past sessions for students

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/getpastsessionsforstudents" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/getpastsessionsforstudents"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "Past sessions": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "user_id": 17,
                "lecture_id": 8,
                "teacher_id": 17,
                "price": 1,
                "date": "2020-04-04",
                "time_from": "05:30:00",
                "time_to": "06:30:00",
                "payed": 1,
                "sub_user_id": null,
                "type": "individual",
                "status": "success",
                "teachers": {
                    "id": 17,
                    "email": "aya_1999_mahmoud@hotmail.com",
                    "email_verified_at": null,
                    "created_at": "2020-08-23 22:13:11",
                    "updated_at": "2020-08-25 19:51:11",
                    "full_name": "Aya Mahmoud",
                    "type": "t",
                    "active": 1,
                    "birth": "1999-09-09",
                    "gender": "female",
                    "status": null,
                    "image_url": "C:\\Users\\ENG MAHMOUD\\Desktop\\API\\NN-API\\public\/users\/images\/3315277565246255_avatar.jpg",
                    "username": null
                }
            }
        ],
        "first_page_url": "http:\/\/localhost:8000\/api\/getpastsessionsforstudents?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "http:\/\/localhost:8000\/api\/getpastsessionsforstudents?page=1",
        "next_page_url": null,
        "path": "http:\/\/localhost:8000\/api\/getpastsessionsforstudents",
        "per_page": 15,
        "prev_page_url": null,
        "to": 1,
        "total": 1
    }
}
```

### HTTP Request
`GET api/getpastsessionsforstudents`


<!-- END_a43f2788c329b827d52d8040d628e47c -->

<!-- START_f5a3e58e95eb661b04b28462c4a77da4 -->
## get upcoming sessions for students

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/getupcomingsessionsforstudents" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/getupcomingsessionsforstudents"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "Upcoming sessions": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "user_id": 17,
                "lecture_id": 8,
                "teacher_id": 17,
                "price": 1,
                "date": "2020-04-04",
                "time_from": "05:30:00",
                "time_to": "06:30:00",
                "payed": 1,
                "sub_user_id": null,
                "type": "individual",
                "status": "success",
                "teachers": {
                    "id": 17,
                    "email": "aya_1999_mahmoud@hotmail.com",
                    "email_verified_at": null,
                    "created_at": "2020-08-23 22:13:11",
                    "updated_at": "2020-08-25 19:51:11",
                    "full_name": "Aya Mahmoud",
                    "type": "t",
                    "active": 1,
                    "birth": "1999-09-09",
                    "gender": "female",
                    "status": null,
                    "image_url": "C:\\Users\\ENG MAHMOUD\\Desktop\\API\\NN-API\\public\/users\/images\/3315277565246255_avatar.jpg",
                    "username": null
                }
            }
        ],
        "first_page_url": "http:\/\/localhost:8000\/api\/getupcomingsessionsforstudents?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "http:\/\/localhost:8000\/api\/getupcomingsessionsforstudents?page=1",
        "next_page_url": null,
        "path": "http:\/\/localhost:8000\/api\/getupcomingsessionsforstudents",
        "per_page": 15,
        "prev_page_url": null,
        "to": 1,
        "total": 1
    }
}
```

### HTTP Request
`GET api/getupcomingsessionsforstudents`


<!-- END_f5a3e58e95eb661b04b28462c4a77da4 -->

<!-- START_be60b66154a7cd9515263863ccb00e4c -->
## get past sessions for students

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/getpastsessionsforteachers" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/getpastsessionsforteachers"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "Past sessions": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "user_id": 17,
                "lecture_id": 8,
                "teacher_id": 17,
                "price": 1,
                "date": "2020-04-04",
                "time_from": "05:30:00",
                "time_to": "06:30:00",
                "payed": 1,
                "sub_user_id": null,
                "type": "individual",
                "status": "success",
                "teachers": {
                    "id": 17,
                    "email": "aya_1999_mahmoud@hotmail.com",
                    "email_verified_at": null,
                    "created_at": "2020-08-23 22:13:11",
                    "updated_at": "2020-08-25 19:51:11",
                    "full_name": "Aya Mahmoud",
                    "type": "t",
                    "active": 1,
                    "birth": "1999-09-09",
                    "gender": "female",
                    "status": null,
                    "image_url": "C:\\Users\\ENG MAHMOUD\\Desktop\\API\\NN-API\\public\/users\/images\/3315277565246255_avatar.jpg",
                    "username": null
                }
            }
        ],
        "first_page_url": "http:\/\/localhost:8000\/api\/getpastsessionsforteachers?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "http:\/\/localhost:8000\/api\/getpastsessionsforteachers?page=1",
        "next_page_url": null,
        "path": "http:\/\/localhost:8000\/api\/getpastsessionsforteachers",
        "per_page": 15,
        "prev_page_url": null,
        "to": 1,
        "total": 1
    }
}
```

### HTTP Request
`GET api/getpastsessionsforteachers`


<!-- END_be60b66154a7cd9515263863ccb00e4c -->

<!-- START_57152e11da29697d08eba23ed262bc9e -->
## get upcoming sessions for students

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/getupcomingsessionsforteachers" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/getupcomingsessionsforteachers"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "Upcoming sessions": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "user_id": 17,
                "lecture_id": 8,
                "teacher_id": 17,
                "price": 1,
                "date": "2020-04-04",
                "time_from": "05:30:00",
                "time_to": "06:30:00",
                "payed": 1,
                "sub_user_id": null,
                "type": "individual",
                "status": "success",
                "teachers": {
                    "id": 17,
                    "email": "aya_1999_mahmoud@hotmail.com",
                    "email_verified_at": null,
                    "created_at": "2020-08-23 22:13:11",
                    "updated_at": "2020-08-25 19:51:11",
                    "full_name": "Aya Mahmoud",
                    "type": "t",
                    "active": 1,
                    "birth": "1999-09-09",
                    "gender": "female",
                    "status": null,
                    "image_url": "C:\\Users\\ENG MAHMOUD\\Desktop\\API\\NN-API\\public\/users\/images\/3315277565246255_avatar.jpg",
                    "username": null
                }
            }
        ],
        "first_page_url": "http:\/\/localhost:8000\/api\/getupcomingsessionsforteachers?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "http:\/\/localhost:8000\/api\/getupcomningsessionsforteachers?page=1",
        "next_page_url": null,
        "path": "http:\/\/localhost:8000\/api\/getupcomingsessionsforteachers",
        "per_page": 15,
        "prev_page_url": null,
        "to": 1,
        "total": 1
    }
}
```

### HTTP Request
`GET api/getupcomingsessionsforteachers`


<!-- END_57152e11da29697d08eba23ed262bc9e -->

<!-- START_5c84cfafc1c6c4b6b4cefe787dc19ae2 -->
## Create a new session

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X POST \
    "http://localhost/api/createsession" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"time_from":"sunt","date":"id","weekly":false}'

```

```javascript
const url = new URL(
    "http://localhost/api/createsession"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "time_from": "sunt",
    "date": "id",
    "weekly": false
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "message": "session created"
}
```
> Example response (401):

```json
{
    "error": "unauthenticated"
}
```
> Example response (401):

```json
{
    "error": "user must be a teacher"
}
```
> Example response (403):

```json
{
    "error": "less than two hours left"
}
```

### HTTP Request
`POST api/createsession`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `time_from` | time |  required  | The session's start time in the format hh:mm
        `date` | date |  required  | The session's date in the format YYYY-MM-DD
        `weekly` | boolean |  required  | whether the session should be repeated every week or not.
    
<!-- END_5c84cfafc1c6c4b6b4cefe787dc19ae2 -->

<!-- START_e41f0ab610af68db8cad9c40c185607d -->
## Update an upcoming session

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X POST \
    "http://localhost/api/updatesession" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"new":"{\"time_from\": \"05:00\", \"date\":\"2020-12-30\", \"weekly\":\"false\"}","old":"{\"time_from\": \"07:00\", \"date\":\"2020-12-29\", \"weekly\":\"false\"}"}'

```

```javascript
const url = new URL(
    "http://localhost/api/updatesession"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "new": "{\"time_from\": \"05:00\", \"date\":\"2020-12-30\", \"weekly\":\"false\"}",
    "old": "{\"time_from\": \"07:00\", \"date\":\"2020-12-29\", \"weekly\":\"false\"}"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "slot": {
        "date": "2020-07-06",
        "time_from": "01:30",
        "time_to": "02:30"
    }
}
```
> Example response (401):

```json
{
    "error": "unauthenticated"
}
```
> Example response (401):

```json
{
    "error": "user must be a teacher"
}
```
> Example response (403):

```json
{
    "error": "less than two hours left"
}
```

### HTTP Request
`POST api/updatesession`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `new` | JSON |  required  | The session's old details: time_from (hh:mm), date (YYYY-MM-DD), and weekly (boolean).
        `old` | JSON |  required  | The session's new details: time_from (hh:mm), date (YYYY-MM-DD), and weekly (boolean).
    
<!-- END_e41f0ab610af68db8cad9c40c185607d -->

<!-- START_f81b4274b229ac53c3565b9761a85ff6 -->
## Delete session

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X DELETE \
    "http://localhost/api/deletesession" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"lecture_id":"et"}'

```

```javascript
const url = new URL(
    "http://localhost/api/deletesession"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "lecture_id": "et"
}

fetch(url, {
    method: "DELETE",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "message": "session deleted"
}
```
> Example response (401):

```json
{
    "error": "session can't be deleted"
}
```
> Example response (404):

```json
null
```

### HTTP Request
`DELETE api/deletesession`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `lecture_id` | required |  optional  | Integer
    
<!-- END_f81b4274b229ac53c3565b9761a85ff6 -->

<!-- START_eefc9bff9711c3e809c2a9cd1589a8da -->
## enroll session

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X POST \
    "http://localhost/api/enrollsession" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"lecture_id":"consequatur","teacher_id":"debitis"}'

```

```javascript
const url = new URL(
    "http://localhost/api/enrollsession"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "lecture_id": "consequatur",
    "teacher_id": "debitis"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "result": {
        "checkedout": true,
        "payed": true
    }
}
```

### HTTP Request
`POST api/enrollsession`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `lecture_id` | required |  optional  | Integer the lecture the student wants to enroll
        `teacher_id` | required |  optional  | Integer the teacher the student want to enroll the lecture with
    
<!-- END_eefc9bff9711c3e809c2a9cd1589a8da -->

<!-- START_75aeedc9835147dbac6904a443cffb60 -->
## used to check available days in a certain month and year

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/availabledays" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"teacher_id":"dolorum","month":"sed","year":"labore"}'

```

```javascript
const url = new URL(
    "http://localhost/api/availabledays"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "teacher_id": "dolorum",
    "month": "sed",
    "year": "labore"
}

fetch(url, {
    method: "GET",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "result": [
        {
            "date": "2020-07-29",
            "badge": false
        }
    ]
}
```

### HTTP Request
`GET api/availabledays`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `teacher_id` | required |  optional  | Integer the id of the teacher
        `month` | required |  optional  | Integer
        `year` | required |  optional  | Integer
    
<!-- END_75aeedc9835147dbac6904a443cffb60 -->

<!-- START_18730964c304801367eb526b42cf7a52 -->
## used to check available slots in a certain date

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/availableslots" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"teacher":"voluptatum","date":"suscipit"}'

```

```javascript
const url = new URL(
    "http://localhost/api/availableslots"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "teacher": "voluptatum",
    "date": "suscipit"
}

fetch(url, {
    method: "GET",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "date": {
        "date": "Wednesday, July 29, 2020",
        "slots": [
            {
                "date": "2020-07-29",
                "time_from": "02:30",
                "time_to": "03:40",
                "lecture_id": null
            }
        ]
    },
    "slots": [
        {
            "id": null,
            "title": "02:30 To 03:40",
            "time_from": "02:30",
            "time_to": "03:40",
            "date": "2020-07-29"
        }
    ]
}
```

### HTTP Request
`GET api/availableslots`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `teacher` | required |  optional  | Integer the id of the teacher
        `date` | required |  optional  | date
    
<!-- END_18730964c304801367eb526b42cf7a52 -->

#authentication

used to login and create token
<!-- START_c3fa189a6c95ca36ad6ac4791a873d23 -->
## Login

> Example request:

```bash
curl -X POST \
    "http://localhost/api/login" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"username":"natus","password":"quam","remember_me":"et"}'

```

```javascript
const url = new URL(
    "http://localhost/api/login"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "username": "natus",
    "password": "quam",
    "remember_me": "et"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiZTg1M2U3MjNmMjBlNDg3MDBlNDhkYTU2ZmU2MDQ3MGU3ZGFmNjM2YTRmNmM3NTAyYWY3NGM3YTQzYzQyZWM2NmY0NDEzYTY2MTczMTdlZWIiLCJpYXQiOjE1OTk1MDAyMzgsIm5iZiI6MTU5OTUwMDIzOCwiZXhwIjoxNjMxMDM2MjM4LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.Fo0udtMETBRLa4hYX99uErc7eOxTkPAFvaUffpogHnBo2xAMAwRyq-u15L2Hx510kQS2RqlHhOdzuSvIbtPIYJ6OyjlbP9XQxBSbVEKo3Pcbr9twTrAmwPifpEgc3zT9q_NRrnm9UabzfMy3-5tCwvGdNAv3yZet4CjVqTF-7lmFIt2MjSH1Si2WxlGa8Y3DMzvr0t4PuA8_ju8MK5Ql8ylNF10DQyi2YbbULXVHNJXKYIqDRElsAhzN185GTxYHvudvH_VIPOHMCkUeR4i5FAPHkhB_PGSrF9nde6CfbAQ7GIkiC5q9-wB4_Dt5sYjAX1y0VqUiL-y0V99XKS88_1AWkue2W1YfsxI76hcmTIGUR_57IxWVJPNlGXPzpUGdsHlKBmyH7mIHmo8wVMIq3woEy2ilfCLqyVAMIca-94nqY7iqmjhlrE_rBgvfpRz19n2AOWgI9Q33SrNYR4MM_g9XONXpYsjbpAz5BzahWbLRALTqGQNgKy7GNJbMld6Q0jKrZqek0T7Tb6sP1jSgWQaLz5VBhUJvZRDW2zO6-acBg3yQvRTqyMVeFigZaG4Rx9CnH-xd40WeeEjhA--uyCj0XD2zfhdPxNLhYvFa3tCYCJJwuffogpkAcd0pwuUsPS1Rvw75z5AqObFWiYqmwWDbwyrpF_xsVOUWIrqHxX0",
    "token_type": "bearer",
    "expires_at": "YYYY-MM-DD"
}
```
> Example response (401):

```json
{
    "error": "Unauthorized"
}
```

### HTTP Request
`POST api/login`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `username` | required |  optional  | "this will be username or email"
        `password` | required |  optional  | 
        `remember_me` | required |  optional  | boolen with value 1 or 0
    
<!-- END_c3fa189a6c95ca36ad6ac4791a873d23 -->

<!-- START_d7b7952e7fdddc07c978c9bdaf757acf -->
## Signup

> Example request:

```bash
curl -X POST \
    "http://localhost/api/register" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"username":"et","email":"deleniti","password":"ipsum","type":"s","birth":"consequatur","gender":"female","full_name":"molestiae","password_confirmation":"modi"}'

```

```javascript
const url = new URL(
    "http://localhost/api/register"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "username": "et",
    "email": "deleniti",
    "password": "ipsum",
    "type": "s",
    "birth": "consequatur",
    "gender": "female",
    "full_name": "molestiae",
    "password_confirmation": "modi"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": {
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMzA0NWJlMmY0MjNhZTU0YTI3NjFjNDYyNWM1ZDM2ZmRmYzk5MWJiYjRkOWZlZDRiMTQ5YmQ4MjAwODExOWZkZDBhMWMyNjE3MDhkZGYwNGUiLCJpYXQiOjE1OTk1MDg4NzAsIm5iZiI6MTU5OTUwODg3MCwiZXhwIjoxNjMxMDQ0ODcwLCJzdWIiOiI3Iiwic2NvcGVzIjpbXX0.EarO8YHGHKdsDD3QVcBoT0L4bIztSJRlzE0jLM15xOJWOsUe1-1Xguolc9aPi4nADQ-spMstP0INNVE9Tyw6T-AuwqIP6I2YhFGh8eEVUFTXqaC29SR-CH53Bch5k-pXpwEdWoGgePSes-EC6ZntlmfGXEmAMhcmhvB_iddXqVaxSzxph-PBX9q78pYsozxSZVeZd0WwndCjzZOS5wJkBD70W6tQuINYVc3pgz60w585Ns2bzIDxBJZHDtyOcnZyOYbGiJDIu-0c8dyorj8q8XfnjHnd43ImBYjgZR5dIM7Ymo37Q62CD_lv1ex9zJFXhCSFn3QYSDesPlvy_l-7tugSwHMKVIqDBfJBxG9gQxO_yjoZwWYJmysqmA6crcHjZLcW-HVoOPnx6cak6MyyTvAcX7mWDBJ0Te5HV3ALK8ZFHhPae2qmB-H1TvPyxlHdY0cYIkEiGfIgX1pijIbbbeBq7EcP9dO6JAEWaD5SDKvc0tn7Se09AXS1fygxgAWgvazev4V06aZG-_t5F0S6jbCBnKHmx9f9NsDZahAAppnUX7VZ2mA5yOGzo3UEJxz3tA9EIGv2zPgrKPZE4AMaJzsR-_vL7KfHxJfmoberYs0pbGyUMi_K-GtaOnAqdE0XDNsfLRs9ACkH2rCeTuwwCgRHlLy9R9DIQ5nIeRrmQsc",
        "name": "mariam"
    }
}
```
> Example response (401):

```json
{
    "error": "a specific error will be displayed here"
}
```

### HTTP Request
`POST api/register`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `username` | required |  optional  | Unique string for every user
        `email` | required |  optional  | Unique string for every user
        `password` | required |  optional  | Minimun 6 char
        `type` | required |  optional  | The type of the user teacher "t" or student "s".
        `birth` | required |  optional  | Must be in format "YYYY-MM-DD"
        `gender` | required |  optional  | The gender of the user "male" or "female".
        `full_name` | required |  optional  | Must be a string
        `password_confirmation` | required |  optional  | Must be a the same as the password
    
<!-- END_d7b7952e7fdddc07c978c9bdaf757acf -->

<!-- START_55223d515da2d77462d233ebb97e3c40 -->
## login with facebook

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/login/facebook?type=s" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/login/facebook"
);

let params = {
    "type": "s",
};
Object.keys(params)
    .forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (302):

```json
null
```

### HTTP Request
`GET api/login/facebook`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    `type` |  required  | The type of the user teacher "t" or student "s".

<!-- END_55223d515da2d77462d233ebb97e3c40 -->

<!-- START_89b9a98360ef8fa1d3520efdf619cec0 -->
## redirected from the login/facebook request

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/login/facebook/callback" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/login/facebook/callback"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIzIiwianRpIjoiOTI1NDRmNGNjZDI4NmUwY2Y5MGUyMzU4NjllMmRhMTU5ODI4MTk2Nzg0MWYwZDhhMDUyZGRjNTM0YTRmY2E2YTU2NWE5NDA5YjA2ZTU2NTEiLCJpYXQiOjE1OTgwNTM4NTQsIm5iZiI6MTU5ODA1Mzg1NCwiZXhwIjoxNjI5NTg5ODUzLCJzdWIiOiI2Iiwic2NvcGVzIjpbXX0.lqlGeL1mF6Xt8mTXqnl3HMLi6KkSK2C519qUZkxcrNhKD6dQ60ZHbjmDrXth01FQP8VianigA2bhu6YeY7n4MCbtqMVvbkgxHi7FiHh4a8YXdqcgrBK7t79U4waFhMnLxqYJ8YRPLyn2Jdn7qfKmmevRoxvOvwOcn19TFjVXs1KthRMpvSotwhnc6ExaN0oBN7VdjAKIPnmNQCQ77ZT6KayF-Q8NBf_bz-ENP3y-NTtdfOETd-SPaqGtHAtQxdRrqMUNKIfAgUdVHxO4Mwzv_vayR8a6-aeNShHWl-DCGsTu5c5KE1yum4ALHAxS90VIWTKrNM_P_kwmG91tjbtEnlNWzYjM2rO1Gu9MreMyyVGOjIcwRXdjoGJIw7YebZZ1f0E_v9XBlJ2Wme6KmRFyJpK7qZFY7t_Sk3oenuVaR4qJURNmUznktD_9BxGyKRQ669qjYp1PwbOE_5KxiSOhibO7CVUuHPyAz2tYniX-VN07q462FOv4K4e7a2ifQLyMM8f5Fx9lTL4UF-MxpPNTr6xIXuHE62eu6eZPUgEiDAzjS9JJLAEt7KGwnt-8rumnM6nKFsBqRovHt449dQxGJzw-9RiOecKs744cof0RbZfAf_pXrE0oGnDYx-4_mYvMvBpZ8ykAQuSqDKLtKAaro8FMiYbDAsAcOVC9kczgl14",
    "token_type": "Bearer",
    "expires_at": "2021-08-21 23:50:53"
}
```

### HTTP Request
`GET api/login/facebook/callback`


<!-- END_89b9a98360ef8fa1d3520efdf619cec0 -->

#authentication 


<!-- START_00e7e21641f05de650dbe13f242c6f2c -->
## Logout user (Revoke the token)

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/logout" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/logout"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "message": "Successfully logged out"
}
```

### HTTP Request
`GET api/logout`


<!-- END_00e7e21641f05de650dbe13f242c6f2c -->

#edit profile

used to edit profile
<!-- START_09851fbd2b828e86dbb0087ce3bf68a2 -->
## api/uploadprofilepicture
<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X POST \
    "http://localhost/api/uploadprofilepicture" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"photo":"nihil"}'

```

```javascript
const url = new URL(
    "http://localhost/api/uploadprofilepicture"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "photo": "nihil"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": {
        "id": 17,
        "email": "aya_1999_mahmoud@hotmail.com",
        "email_verified_at": null,
        "created_at": "2020-08-23 22:13:11",
        "updated_at": "2020-08-23 22:13:55",
        "full_name": "Aya Mahmoud",
        "type": "s",
        "active": 1,
        "birth": "1999-09-09",
        "gender": "female",
        "status": null,
        "image_url": "C:\\Users\\ENG MAHMOUD\\Desktop\\API\\NN-API\\public\/users\/images\/17_avatar.jpg",
        "username": null
    }
}
```

### HTTP Request
`POST api/uploadprofilepicture`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `photo` | file |  required  | the profile picture
    
<!-- END_09851fbd2b828e86dbb0087ce3bf68a2 -->

<!-- START_88a4c713715d6c9929f02fb704ae1a05 -->
## Edit profile

> Example request:

```bash
curl -X POST \
    "http://localhost/api/editprofile" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"full_name":"aperiam","birth":"non","gender":"female","phone":"distinctio","nationality":"rerum"}'

```

```javascript
const url = new URL(
    "http://localhost/api/editprofile"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "full_name": "aperiam",
    "birth": "non",
    "gender": "female",
    "phone": "distinctio",
    "nationality": "rerum"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "user": [
        "mariam",
        "female",
        "1999-11-11",
        null,
        "egyptian"
    ]
}
```

### HTTP Request
`POST api/editprofile`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `full_name` | required |  optional  | String
        `birth` | required |  optional  | Must be in format "YYYY-MM-DD"
        `gender` | required |  optional  | The gender of the user "male" or "female".
        `phone` | not |  required  | Must be a numeric
        `nationality` | not |  required  | String
    
<!-- END_88a4c713715d6c9929f02fb704ae1a05 -->

<!-- START_72b936ae0ca359c0f20175257123c9c2 -->
## Add other price

> Example request:

```bash
curl -X POST \
    "http://localhost/api/priceinfo" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"price_info":"{\"individual\":\"50\",\"group\":\"150\"}"}'

```

```javascript
const url = new URL(
    "http://localhost/api/priceinfo"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "price_info": "{\"individual\":\"50\",\"group\":\"150\"}"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "msg": "price pending"
}
```

### HTTP Request
`POST api/priceinfo`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `price_info` | required |  optional  | Object of two prices includes individual price and group price written in json.
    
<!-- END_72b936ae0ca359c0f20175257123c9c2 -->

<!-- START_003250fdb51ad58e191a88343ae16988 -->
## update certifications of the user

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X POST \
    "http://localhost/api/updatecertificates" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"certifications":"[{'certificate_id':1,'thumb':image.jpg}]"}'

```

```javascript
const url = new URL(
    "http://localhost/api/updatecertificates"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "certifications": "[{'certificate_id':1,'thumb':image.jpg}]"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": [
        {
            "certificate_id": "photo",
            "thumb_name": "files\/160011349783963.jpg"
        }
    ]
}
```

### HTTP Request
`POST api/updatecertificates`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `certifications` | array |  required  | array of certifications the user adds where each object contains certificate_id(the id that refers to the certificate in certificate table)and thumb(the file of the certificate).
    
<!-- END_003250fdb51ad58e191a88343ae16988 -->

<!-- START_ab3a1673faa84305dd23a5b5e5fff59f -->
## update teacher&#039;s experience

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X PUT \
    "http://localhost/api/updateexperience" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"exp_years":"5","exp_desc":0}'

```

```javascript
const url = new URL(
    "http://localhost/api/updateexperience"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "exp_years": "5",
    "exp_desc": 0
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": {
        "id": 32,
        "user_id": 17,
        "nationality": null,
        "phone": null,
        "postal_code": null,
        "exp_years": "5",
        "exp_desc": "I love me",
        "payment_info": null,
        "avg_rate": 0,
        "month_rate": 0,
        "rank": 0,
        "rates_count": 0,
        "courses": null,
        "certifications": "[{\"certificate_id\":\"photo\",\"thumb_name\":\"files\\\/159957261167645.jpg\"}]",
        "master_degree": null,
        "weekly": [
            {
                "on": "wed",
                "at": [
                    {
                        "time_from": "05:30",
                        "time_to": "07:30",
                        "started_from": "2020-09-30"
                    },
                    {
                        "time_from": "02:30",
                        "time_to": "03:40",
                        "started_from": "2020-07-29"
                    }
                ]
            },
            {
                "on": "mon",
                "at": [
                    {
                        "time_from": "05:30",
                        "time_to": "06:40",
                        "started_from": "2020-11-30"
                    }
                ]
            },
            {
                "on": "thu",
                "at": [
                    {
                        "time_from": "05:30",
                        "time_to": "05:50",
                        "started_from": "2020-1-30"
                    }
                ]
            },
            {
                "on": "sat",
                "at": [
                    {
                        "time_from": "05:30",
                        "time_to": "06:40",
                        "started_from": "2021-1-30"
                    }
                ]
            }
        ],
        "university_degree_id": null,
        "price_info": null,
        "national_id": null,
        "phones": null,
        "suggested_subjects": null,
        "other_subjects": null
    }
}
```

### HTTP Request
`PUT api/updateexperience`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `exp_years` | string |  required  | experience years of a teacher.
        `exp_desc` | integer |  required  | The description of teacher's experience.
    
<!-- END_ab3a1673faa84305dd23a5b5e5fff59f -->

<!-- START_e0e0c684f73c35683de9f5fe1236338e -->
## update teacher&#039;s education

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X PUT \
    "http://localhost/api/updateeducation" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"uni_degree_id":1,"master_degree":"rerum","courses":"illo"}'

```

```javascript
const url = new URL(
    "http://localhost/api/updateeducation"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "uni_degree_id": 1,
    "master_degree": "rerum",
    "courses": "illo"
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": {
        "id": 32,
        "user_id": 17,
        "nationality": null,
        "phone": null,
        "postal_code": null,
        "exp_years": 5,
        "exp_desc": "I love me",
        "payment_info": null,
        "avg_rate": 0,
        "month_rate": 0,
        "rank": 0,
        "rates_count": 0,
        "courses": "ef.re;fr.fr.e'",
        "certifications": "[{\"certificate_id\":\"photo\",\"thumb_name\":\"files\\\/159957261167645.jpg\"}]",
        "master_degree": "fdl[d]",
        "weekly": [
            {
                "on": "wed",
                "at": [
                    {
                        "time_from": "05:30",
                        "time_to": "07:30",
                        "started_from": "2020-09-30"
                    },
                    {
                        "time_from": "02:30",
                        "time_to": "03:40",
                        "started_from": "2020-07-29"
                    }
                ]
            },
            {
                "on": "mon",
                "at": [
                    {
                        "time_from": "05:30",
                        "time_to": "06:40",
                        "started_from": "2020-11-30"
                    }
                ]
            },
            {
                "on": "thu",
                "at": [
                    {
                        "time_from": "05:30",
                        "time_to": "05:50",
                        "started_from": "2020-1-30"
                    }
                ]
            },
            {
                "on": "sat",
                "at": [
                    {
                        "time_from": "05:30",
                        "time_to": "06:40",
                        "started_from": "2021-1-30"
                    }
                ]
            }
        ],
        "university_degree_id": null,
        "price_info": null,
        "national_id": null,
        "phones": null,
        "suggested_subjects": null,
        "other_subjects": null
    }
}
```

### HTTP Request
`PUT api/updateeducation`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `uni_degree_id` | integer |  required  | university degree id of the teacher.
        `master_degree` | string |  required  | master degree of the teacher.
        `courses` | string |  required  | courses of the teacher.
    
<!-- END_e0e0c684f73c35683de9f5fe1236338e -->

<!-- START_da571d78351055720e61a336a3338d51 -->
## Update subjects

> Example request:

```bash
curl -X POST \
    "http://localhost/api/updatesubjects" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"subjects":"[1]","languages":"[1]","grades":"[3]","edu_systems":"[2]"}'

```

```javascript
const url = new URL(
    "http://localhost/api/updatesubjects"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "subjects": "[1]",
    "languages": "[1]",
    "grades": "[3]",
    "edu_systems": "[2]"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "id": 4,
    "email": "ayamaygdy2@gmail.com",
    "email_verified_at": null,
    "created_at": "2020-08-26 15:20:10",
    "updated_at": "2020-08-26 15:20:10",
    "full_name": "aya",
    "type": "t",
    "active": 1,
    "birth": "1999-05-05",
    "gender": "female",
    "status": 1,
    "image_url": null,
    "username": "ayamagdy",
    "profile": {
        "id": 3,
        "user_id": 4,
        "nationality": null,
        "phone": "01059996633",
        "postal_code": null,
        "exp_years": null,
        "exp_desc": null,
        "payment_info": null,
        "avg_rate": 0,
        "month_rate": 0,
        "rank": 0,
        "rates_count": 0,
        "courses": null,
        "certifications": null,
        "master_degree": null,
        "weekly": [
            {
                "at": [
                    {
                        "time_to": "12:10",
                        "time_from": "11:00",
                        "started_from": "2020-09-03"
                    }
                ],
                "on": "thu"
            }
        ],
        "university_degree_id": null,
        "price_info": {
            "pending": null
        },
        "national_id": null,
        "phones": null,
        "suggested_subjects": [],
        "other_subjects": null
    },
    "specialist_in": [
        {
            "id": 1,
            "slug": "hello",
            "title": "hello",
            "created_at": "2020-09-01 12:35:49",
            "updated_at": "2020-09-01 12:35:49",
            "is_active": 1,
            "pivot": {
                "user_id": 4,
                "subject_id": 1
            }
        }
    ],
    "languages": [
        {
            "id": 1,
            "slug": "english",
            "title": "english",
            "pivot": {
                "user_id": 4,
                "language_id": 1
            }
        }
    ],
    "edu_systems": [
        {
            "id": 1,
            "slug": "maam",
            "title": "maamm",
            "created_at": "2020-09-01 12:37:19",
            "updated_at": "2020-09-01 12:37:19",
            "pivot": {
                "user_id": 4,
                "edu_id": 1
            }
        }
    ],
    "latest_reviews": [],
    "grades": [
        {
            "id": 2,
            "slug": "kg2",
            "title": "kg2",
            "created_at": "2020-09-09 00:00:00",
            "updated_at": "2020-09-09 00:00:00",
            "pivot": {
                "user_id": 4,
                "grade_id": 2
            }
        }
    ]
}
```

### HTTP Request
`POST api/updatesubjects`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `subjects` | not |  required  | Array of subject id.
        `languages` | not |  required  | Array of subject id.
        `grades` | not |  required  | Array of subject id.
        `edu_systems` | not |  required  | Array of subject id.
    
<!-- END_da571d78351055720e61a336a3338d51 -->

#general


<!-- START_0c068b4037fb2e47e71bd44bd36e3e2a -->
## Authorize a client to access the user&#039;s account.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/oauth/authorize" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/authorize"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET oauth/authorize`


<!-- END_0c068b4037fb2e47e71bd44bd36e3e2a -->

<!-- START_e48cc6a0b45dd21b7076ab2c03908687 -->
## Approve the authorization request.

> Example request:

```bash
curl -X POST \
    "http://localhost/oauth/authorize" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/authorize"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST oauth/authorize`


<!-- END_e48cc6a0b45dd21b7076ab2c03908687 -->

<!-- START_de5d7581ef1275fce2a229b6b6eaad9c -->
## Deny the authorization request.

> Example request:

```bash
curl -X DELETE \
    "http://localhost/oauth/authorize" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/authorize"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE oauth/authorize`


<!-- END_de5d7581ef1275fce2a229b6b6eaad9c -->

<!-- START_a09d20357336aa979ecd8e3972ac9168 -->
## Authorize a client to access the user&#039;s account.

> Example request:

```bash
curl -X POST \
    "http://localhost/oauth/token" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/token"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST oauth/token`


<!-- END_a09d20357336aa979ecd8e3972ac9168 -->

<!-- START_d6a56149547e03307199e39e03e12d1c -->
## Get all of the authorized tokens for the authenticated user.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/oauth/tokens" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/tokens"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET oauth/tokens`


<!-- END_d6a56149547e03307199e39e03e12d1c -->

<!-- START_a9a802c25737cca5324125e5f60b72a5 -->
## Delete the given token.

> Example request:

```bash
curl -X DELETE \
    "http://localhost/oauth/tokens/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/tokens/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE oauth/tokens/{token_id}`


<!-- END_a9a802c25737cca5324125e5f60b72a5 -->

<!-- START_abe905e69f5d002aa7d26f433676d623 -->
## Get a fresh transient token cookie for the authenticated user.

> Example request:

```bash
curl -X POST \
    "http://localhost/oauth/token/refresh" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/token/refresh"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST oauth/token/refresh`


<!-- END_abe905e69f5d002aa7d26f433676d623 -->

<!-- START_babcfe12d87b8708f5985e9d39ba8f2c -->
## Get all of the clients for the authenticated user.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/oauth/clients" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/clients"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET oauth/clients`


<!-- END_babcfe12d87b8708f5985e9d39ba8f2c -->

<!-- START_9eabf8d6e4ab449c24c503fcb42fba82 -->
## Store a new client.

> Example request:

```bash
curl -X POST \
    "http://localhost/oauth/clients" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/clients"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST oauth/clients`


<!-- END_9eabf8d6e4ab449c24c503fcb42fba82 -->

<!-- START_784aec390a455073fc7464335c1defa1 -->
## Update the given client.

> Example request:

```bash
curl -X PUT \
    "http://localhost/oauth/clients/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/clients/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT oauth/clients/{client_id}`


<!-- END_784aec390a455073fc7464335c1defa1 -->

<!-- START_1f65a511dd86ba0541d7ba13ca57e364 -->
## Delete the given client.

> Example request:

```bash
curl -X DELETE \
    "http://localhost/oauth/clients/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/clients/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE oauth/clients/{client_id}`


<!-- END_1f65a511dd86ba0541d7ba13ca57e364 -->

<!-- START_9e281bd3a1eb1d9eb63190c8effb607c -->
## Get all of the available scopes for the application.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/oauth/scopes" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/scopes"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET oauth/scopes`


<!-- END_9e281bd3a1eb1d9eb63190c8effb607c -->

<!-- START_9b2a7699ce6214a79e0fd8107f8b1c9e -->
## Get all of the personal access tokens for the authenticated user.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/oauth/personal-access-tokens" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/personal-access-tokens"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET oauth/personal-access-tokens`


<!-- END_9b2a7699ce6214a79e0fd8107f8b1c9e -->

<!-- START_a8dd9c0a5583742e671711f9bb3ee406 -->
## Create a new personal access token for the user.

> Example request:

```bash
curl -X POST \
    "http://localhost/oauth/personal-access-tokens" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/personal-access-tokens"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST oauth/personal-access-tokens`


<!-- END_a8dd9c0a5583742e671711f9bb3ee406 -->

<!-- START_bae65df80fd9d72a01439241a9ea20d0 -->
## Delete the given token.

> Example request:

```bash
curl -X DELETE \
    "http://localhost/oauth/personal-access-tokens/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/personal-access-tokens/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE oauth/personal-access-tokens/{token_id}`


<!-- END_bae65df80fd9d72a01439241a9ea20d0 -->

<!-- START_f50fecb2993d22653a99f84a5951e92c -->
## details api *get users*

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/details" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/details"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/details`


<!-- END_f50fecb2993d22653a99f84a5951e92c -->

<!-- START_a3e1881290e56344612a4642673397ed -->
## api/getprofile
> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/getprofile" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/getprofile"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/getprofile`


<!-- END_a3e1881290e56344612a4642673397ed -->

<!-- START_31f430322462abe3fc3e4ba369b8f77d -->
## Resend the email verification notification.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/email/resend" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/email/resend"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/email/resend`


<!-- END_31f430322462abe3fc3e4ba369b8f77d -->

<!-- START_3e4a08674c3c1aaa7a4e8aacbf86420a -->
## Mark the authenticated user&#039;s email address as verified.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/email/verify/1/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/email/verify/1/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (403):

```json
{
    "message": "Invalid signature."
}
```

### HTTP Request
`GET api/email/verify/{id}/{hash}`


<!-- END_3e4a08674c3c1aaa7a4e8aacbf86420a -->

<!-- START_b7802a3a2092f162a21dc668479801f4 -->
## Send a reset link to the given user.

> Example request:

```bash
curl -X POST \
    "http://localhost/api/password/email" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/password/email"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/password/email`


<!-- END_b7802a3a2092f162a21dc668479801f4 -->

<!-- START_8ad860d24dc1cc6dac772d99135ad13e -->
## Reset the given user&#039;s password.

> Example request:

```bash
curl -X POST \
    "http://localhost/api/password/reset" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/password/reset"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/password/reset`


<!-- END_8ad860d24dc1cc6dac772d99135ad13e -->

<!-- START_59bbdcd23e875fa73b638c4c3c6aa5d6 -->
## api/m
> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/m" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/m"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
```

### HTTP Request
`GET api/m`


<!-- END_59bbdcd23e875fa73b638c4c3c6aa5d6 -->

<!-- START_66e08d3cc8222573018fed49e121e96d -->
## Show the application&#039;s login form.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/login" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/login"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (500):

```json
{
    "message": "Server Error"
}
```

### HTTP Request
`GET login`


<!-- END_66e08d3cc8222573018fed49e121e96d -->

<!-- START_ba35aa39474cb98cfb31829e70eb8b74 -->
## Handle a login request to the application.

> Example request:

```bash
curl -X POST \
    "http://localhost/login" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/login"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST login`


<!-- END_ba35aa39474cb98cfb31829e70eb8b74 -->

<!-- START_e65925f23b9bc6b93d9356895f29f80c -->
## Log the user out of the application.

> Example request:

```bash
curl -X POST \
    "http://localhost/logout" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/logout"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST logout`


<!-- END_e65925f23b9bc6b93d9356895f29f80c -->

<!-- START_ff38dfb1bd1bb7e1aa24b4e1792a9768 -->
## Show the application registration form.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/register" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/register"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (500):

```json
{
    "message": "Server Error"
}
```

### HTTP Request
`GET register`


<!-- END_ff38dfb1bd1bb7e1aa24b4e1792a9768 -->

<!-- START_d7aad7b5ac127700500280d511a3db01 -->
## Handle a registration request for the application.

> Example request:

```bash
curl -X POST \
    "http://localhost/register" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/register"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST register`


<!-- END_d7aad7b5ac127700500280d511a3db01 -->

<!-- START_d72797bae6d0b1f3a341ebb1f8900441 -->
## Display the form to request a password reset link.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/password/reset" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/password/reset"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (500):

```json
{
    "message": "Server Error"
}
```

### HTTP Request
`GET password/reset`


<!-- END_d72797bae6d0b1f3a341ebb1f8900441 -->

<!-- START_feb40f06a93c80d742181b6ffb6b734e -->
## Send a reset link to the given user.

> Example request:

```bash
curl -X POST \
    "http://localhost/password/email" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/password/email"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST password/email`


<!-- END_feb40f06a93c80d742181b6ffb6b734e -->

<!-- START_e1605a6e5ceee9d1aeb7729216635fd7 -->
## Display the password reset view for the given token.

If no token is present, display the link request form.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/password/reset/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/password/reset/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (500):

```json
{
    "message": "Server Error"
}
```

### HTTP Request
`GET password/reset/{token}`


<!-- END_e1605a6e5ceee9d1aeb7729216635fd7 -->

<!-- START_cafb407b7a846b31491f97719bb15aef -->
## Reset the given user&#039;s password.

> Example request:

```bash
curl -X POST \
    "http://localhost/password/reset" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/password/reset"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST password/reset`


<!-- END_cafb407b7a846b31491f97719bb15aef -->

<!-- START_b77aedc454e9471a35dcb175278ec997 -->
## Display the password confirmation view.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/password/confirm" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/password/confirm"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET password/confirm`


<!-- END_b77aedc454e9471a35dcb175278ec997 -->

<!-- START_54462d3613f2262e741142161c0e6fea -->
## Confirm the given user&#039;s password.

> Example request:

```bash
curl -X POST \
    "http://localhost/password/confirm" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/password/confirm"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST password/confirm`


<!-- END_54462d3613f2262e741142161c0e6fea -->

<!-- START_c88fc6aa6eb1bee7a494d3c0a02038b1 -->
## Show the email verification notice.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/email/verify" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/email/verify"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET email/verify`


<!-- END_c88fc6aa6eb1bee7a494d3c0a02038b1 -->

<!-- START_6792598c74b34a271a2e3ab9365adf9e -->
## Mark the authenticated user&#039;s email address as verified.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/email/verify/1/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/email/verify/1/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET email/verify/{id}/{hash}`


<!-- END_6792598c74b34a271a2e3ab9365adf9e -->

<!-- START_38334d357e7e155bf70b9ab94619ca3d -->
## Resend the email verification notification.

> Example request:

```bash
curl -X POST \
    "http://localhost/email/resend" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/email/resend"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST email/resend`


<!-- END_38334d357e7e155bf70b9ab94619ca3d -->

#getters

used to get subjects
<!-- START_2fe7213b84d8914a1f7c34334e2b20ca -->
## Get all subject

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/getsubjects" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/getsubjects"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "Subjects": [
        {
            "id": 1,
            "slug": "hello",
            "title": "hello",
            "created_at": "2020-09-01 12:35:49",
            "updated_at": "2020-09-01 12:35:49",
            "is_active": 1
        },
        {
            "id": 2,
            "slug": "maths",
            "title": "maths",
            "created_at": "2020-09-09 00:00:00",
            "updated_at": "2020-09-09 00:00:00",
            "is_active": 1
        }
    ]
}
```

### HTTP Request
`GET api/getsubjects`


<!-- END_2fe7213b84d8914a1f7c34334e2b20ca -->

<!-- START_316f3a551f3cde626ccf0f6f5490cffd -->
## Get all languages

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/getlanguages" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/getlanguages"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "Languages": [
        {
            "id": 1,
            "slug": "english",
            "title": "english"
        }
    ]
}
```

### HTTP Request
`GET api/getlanguages`


<!-- END_316f3a551f3cde626ccf0f6f5490cffd -->

<!-- START_cd2ad2ff332e4f2176ee4f93b81e1d1f -->
## Get all edusystems

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/getedusystems" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/getedusystems"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "EduSystems": [
        {
            "id": 1,
            "slug": "maam",
            "title": "maamm",
            "created_at": "2020-09-01 12:37:19",
            "updated_at": "2020-09-01 12:37:19"
        }
    ]
}
```

### HTTP Request
`GET api/getedusystems`


<!-- END_cd2ad2ff332e4f2176ee4f93b81e1d1f -->

<!-- START_f064dd136cf1beeca8ce9d6324142aa5 -->
## Get all grades

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/getgrades" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/getgrades"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "Grades": [
        {
            "id": 1,
            "slug": "kg1",
            "title": "kg1",
            "created_at": "2020-09-01 12:37:48",
            "updated_at": "2020-09-01 12:37:48"
        }
    ]
}
```

### HTTP Request
`GET api/getgrades`


<!-- END_f064dd136cf1beeca8ce9d6324142aa5 -->

<!-- START_9c02760d4d20e71c342fa52a8838d2fd -->
## Get prices approved by the admin from options table

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/getprice" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/getprice"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "price": "{\"individual\":[\"50\",\"100\"],\r\n\"group\":[\"100\",\"200\"]}"
}
```
> Example response (400):

```json
{
    "error": "Unauthorized"
}
```

### HTTP Request
`GET api/getprice`


<!-- END_9c02760d4d20e71c342fa52a8838d2fd -->

#payment

Lecture to be checked out must be scheduled in schedule table with success=NULL and payed=0. if the lecture is free(percent=100) 200 response is sent, otherwise it returns 302 with the payment link.Links have to be modified by fontened in FawryEloquent.php and PaymentEloquent.php.
<!-- START_d4be91a3a8d5235a2d282e7158c2bdb6 -->
## Checkout

> Example request:

```bash
curl -X POST \
    "http://localhost/api/checkout" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"items":12,"payment_method":"\"fawry\"","percent":0}'

```

```javascript
const url = new URL(
    "http://localhost/api/checkout"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "items": 12,
    "payment_method": "\"fawry\"",
    "percent": 0
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "sucess": "thank you for checking out"
}
```
> Example response (302):

```json
{
    "url": "https:\/\/pay-it.mobi\/globalpayit\/pciglobal\/WebForms\/Payitcheckoutservice%20.aspx?isysid=16001206822235&amount=50.00&description=Payed+Sessions+for+2020-09-05&description2=Payed+Sessions+for+2020-09-05&language=EN&merchant_name=NajahNow&akey=YW52DzYnU8E7PMyE&original=Uq85b%2Fz3Yu4iQ0efflLzXxso1Wsg11FrA6ZZNDCBWsI%3D&hash=0FD16DCFCFC7A92A8593042C1E22D17A5EF0F63F173CD0AA05D4683C66218428&timestamp=1600120683&rnd=&user_id=18"
}
```
> Example response (404):

```json
{
    "error": "no items found for checkout"
}
```

### HTTP Request
`POST api/checkout`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `items` | integer |  required  | at least one element in the array
        `payment_method` | string |  required  | fawry or credit_card.
        `percent` | integer |  optional  | optional discount percent of promocode.
    
<!-- END_d4be91a3a8d5235a2d282e7158c2bdb6 -->


