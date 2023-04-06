# REST API Photo Sharing
Created by Fajar Hamdani \
Start Date: 06 April 2023

## How To Run

1. `composer install`
2. `php artisan key:generate`
3. `php artisan migrate`
4. `php artisan db:seed`
5. `php artisan storage:link`
6. `php artisan jwt:secret`
7. `php artisan serve`

## Design Database
users
- id [pk]
- email [unique]
- password
- created_at
- updated_at

photos
- id [pk]
- user_id [fk users, on delete cascade]
- caption [text, nullable]
- photo_path [nullable]
- created_at
- updated_at

photo_tags
- id [pk]
- photo_id [fk photos, on delete cascade]
- tag_name

photo_likes [pivot table of users and photos]
- id [pk]
- user_id [fk users, on delete cascade]
- photo_id [fk photos, on delete cascade]
- created_at
- updated_at

## API Documentation
Base URL: http://128.199.225.24

**API Login** \
Method: POST \
Path: /api/auth/login \
Headers: 
- Content-Type: application/json 

Payload:
```
{
    "email": "fajarhamdani70@gmail.com",
    "password": "fajar123"
}
```
Response:
```
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "email": "fajarhamdani70@gmail.com",
            "created_at": "2023-04-06T07:28:11.000000Z",
            "updated_at": "2023-04-06T07:28:11.000000Z"
        },
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2F1dGgvbG9naW4iLCJpYXQiOjE2ODA3NjYxMTYsImV4cCI6MTY4MDc2OTcxNiwibmJmIjoxNjgwNzY2MTE2LCJqdGkiOiJsYnpXUUpBeFdpcWhHTHFkIiwic3ViIjoiMSIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.RaWJOAVmGoHQRob1p42E3mTNexVCRXVmbjBFJGWufBk"
    },
    "error": null
}
```

**API Get Photos** \
Method: GET \
Path: /api/photos \
Headers:
- Authorization: Bearer token

Response:
```
{
    "success": true,
    "data": [
        {
            "id": 5,
            "user_id": 1,
            "caption": "Updated content",
            "photo_path": "photos/kETAFjn2tdrV6DGGB7gZ7EaoLwjp2J2bmWsgzfOt.png",
            "created_at": "2023-04-06T07:45:01.000000Z",
            "updated_at": "2023-04-06T07:45:33.000000Z",
            "tags": [
                {
                    "tag_name": "#tag_1"
                },
                {
                    "tag_name": "#tag_2"
                }
            ],
            "likes": [
                {
                    "id": 1,
                    "email": "fajarhamdani70@gmail.com"
                }
            ]
        },
        {
            "id": 4,
            "user_id": 1,
            "caption": null,
            "photo_path": "photos/NEOmlR1ojdOPUB1LIiUwnp3wSL1FZPalfY1Mff4y.png",
            "created_at": "2023-04-06T07:41:10.000000Z",
            "updated_at": "2023-04-06T07:44:43.000000Z",
            "tags": [
                {
                    "tag_name": "#tag_1"
                },
                {
                    "tag_name": "#tag_2"
                }
            ],
            "likes": []
        }
    ],
    "error": null
}
```

**API Create Photo** \
Method: POST \
Path: /api/photos \
Headers:
- Authorization: Bearer token
- Content-Type: multipart/form-data

Form Data:
- photo [required, image, max 5mb]
- caption [optional]
- tags [optional, format: #tag_1 #tag_2 #tag_3 #tag_n]

Response:
```
{
    "success": true,
    "data": {
        "user_id": 1,
        "caption": "Example caption",
        "photo_path": "photos/ZL0dRd5Mtt4B85uHSDIZtELCcvS8Wi3dh0ldu9s7.png",
        "updated_at": "2023-04-06T10:08:50.000000Z",
        "created_at": "2023-04-06T10:08:50.000000Z",
        "id": 6,
        "tags": [
            {
                "tag_name": "#tag1"
            },
            {
                "tag_name": "#tag2"
            },
            {
                "tag_name": "#tag3"
            }
        ]
    },
    "error": null
}
```

**API Photo Detail** \
Method: GET \
Path: /api/photos/<photo_id> \
Headers:
- Authorization: Bearer token

Response:
```
{
    "success": true,
    "data": {
        "id": 5,
        "user_id": 1,
        "caption": "Updated content",
        "photo_path": "photos/kETAFjn2tdrV6DGGB7gZ7EaoLwjp2J2bmWsgzfOt.png",
        "created_at": "2023-04-06T07:45:01.000000Z",
        "updated_at": "2023-04-06T07:45:33.000000Z",
        "tags": [
            {
                "tag_name": "#tag_1"
            },
            {
                "tag_name": "#tag_2"
            }
        ],
        "likes": [
            {
                "id": 1,
                "email": "fajarhamdani70@gmail.com"
            }
        ]
    },
    "error": null
}
```

**API Update Photo** \
Method: PUT \
Path: /api/photos/<photo_id> \
Headers:
- Authorization: Bearer token
- Content-Type: application/json

Payload:
```
{
    "caption": "Updated content",
    "tags": "#tag_1 #tag_2"
}
```
Response:
```
{
    "success": true,
    "data": {
        "id": 5,
        "user_id": 1,
        "caption": "Updated content",
        "photo_path": "photos/kETAFjn2tdrV6DGGB7gZ7EaoLwjp2J2bmWsgzfOt.png",
        "created_at": "2023-04-06T07:45:01.000000Z",
        "updated_at": "2023-04-06T07:45:33.000000Z",
        "tags": [
            {
                "tag_name": "#tag_1"
            },
            {
                "tag_name": "#tag_2"
            }
        ]
    },
    "error": null
}
```

**API Delete Photo** \
Method: DELETE \
Path: /api/photos/<photo_id> \
Headers:
- Authorization: Bearer token

Response:
```
{
    "success": true,
    "data": {
        "message": "Photo deleted."
    },
    "error": null
}
```

**API Like Photo** \
Method: POST \
Path: /api/photos/<photo_id>/like \
Headers:
- Authorization: Bearer token

Response:
```
{
    "success": true,
    "data": {
        "message": "Like success."
    },
    "error": null
}
```

**API Unlike Photo** \
Method: POST \
Path: /api/photos/<photo_id>/unlike \
Headers:
- Authorization: Bearer token

Response:
```
{
    "success": true,
    "data": {
        "message": "Unlike success."
    },
    "error": null
}
```
