
# REST API Assessment

This is a simple PHP application providing a REST API.

## Run Locally

Clone the project

```bash
  git clone https://github.com/ammarkero/rest-api-assessment.git
```

Go to the project directory

```bash
  cd rest-api-assessment
```

Install dependencies

```bash
  composer install
```

Start the server

```bash
  php -S localhost:8888 -t public
```

# List of APIs

- User:
    - get all users (#get-list-of-users)
    - get a specific user
    - create an user
    - update an user
    - delete an user
    - store user's role `[many-to-many relationship]`
    - get all user's role(s) `[many-to-many relationship]`
- Authentication:
    - user login (generate JWToken)
    - user logout
- External data:
    - get data from external api call
    - store data from external api call
- Post:
    - get post's image `[polymorhpic relationship]`
    - store post's image `[polymorhpic relationship]`

# Usage/Example

The REST API to the app is described below.

## Get list of Users

#### Request

`GET /api/v1/user/all`

```bash
curl \
-i \
-H 'Accept: application/json' \
http://localhost:8888/api/v1/user/all
```

#### Response

```json
{
    "status":200,
    "message":"OK",
    "data":[
        {
        "id":1,
        "name":"Jake Smith",
        "email":"jakesmith@email.com",
        "password":"$2y$10$e\/pbdN7ji1UWp\/GheWPKJOaKF7zG14RNj2doLqP3CX7GHhkFFJGs2"
        },
        {
        "id":2,
        "name":"Donato Padberg",
        "email":"donato.padberg@email.com",
        "password":"$2y$10$Ob7Rz3kUa0\/mqsQNHJKxdeFTwhDptoClk6hASn8aunp8sRyfqlkXi"
        }
    ]
}
```

## Create a new User

#### Request

`POST /api/v1/user/cerate`

```bash
url \
-i \
-X POST \
-H 'Accept: application/json' \
-H 'Content-Type:application/json' \
-d '{"name": "Xavier", "email": "hello@xavier.com","password":"12345678"}' \
http://localhost:8888/api/v1/user/create
```

#### Response

    {
        "status": 201,
        "message": "User successfully created",
        "data": {
            "id": 3,
            "name": "Xavier",
            "email": "hello@xavier.com",
            "password": "$2y$10$eAvte8C7yxnJk/nLqzzHN.xGIIDY81rn3CoDJCZNDAWuEDjZ1DWhu"
        }
    }

## Get a specific User

#### Request

`GET /api/v1/user/single?:id`

```bash
curl \
-i \
-H 'Accept: application/json' \
http://localhost:8888/api/v1/user/single?id=4
```

#### Response

    {
        "status": 200,
        "message": "OK",
        "data": {
            "id": 4,
            "name": "Rebeca Terry",
            "email": "hello@rebeca.com",
            "password": "$2y$10$.b1vaE0DKJ0k/SMTfR2IIe/HZ/urLaumrXJzZk3Fev2pzZjkRUHWe"
        }
    }
    
## Update a specific User

#### Request

`PUT /api/v1/user/update`

```bash
curl \
-i \
-H 'Accept: application/json' \
-H 'Content-Type:application/json' \
-X PUT \
-d '{"id": "1","name": "Sara","email": "hello@sara.com","password": "abc1234567"} \
http://localhost:8888/api/v1/user/update
```

#### Response

    {
        "status": 200,
        "message": "OK",
        "data": {
            "id": 1,
            "name": "Sara",
            "email": "hello@sara.com",
            "password": "$2y$10$4ajTn60a7slI6OgXQiI8OulFgmp6LOkOp8iQFVen\/y62YMSrzLDyi"
        }
    }
    
## Create a new User's role

#### Request

`POST /api/v1/user/role/create`

```bash
curl \
-i \
-X POST \
-H 'Accept: application/json' \
-H 'Content-Type:application/json' \
-d '{"email": "hello@sara.com","role_id": "1"}'\
http://localhost:8888/api/v1/user/role/create
```

#### Response

    {
        "status": 200,
        "message": "OK",
        "data": [
            {
                "title": "Admin"
            }
        ]
    }
    
## Get list of User's role

#### Request

`GET /api/v1/user/role?:id`

```bash
curl \
-i \
-H 'Accept: application/json' \
http://localhost:8888/api/v1/user/role?id=1
```

#### Response

    {
        "status": 200,
        "message": "OK",
        "data": [
            {
                "title": "Admin"
            }
        ]
    }
    
## Delete a specific User

#### Request

`DELETE /api/v1/user/delete`
```bash
curl \
-i \
-X DELETE \
-H 'Accept: application/json' \
-H 'Content-Type:application/json' \
-d '{"id": "13"}' \
http://localhost:8888/api/v1/user/delete
```

#### Response

    {
        "status": 200,
        "message": "User successfully deleted",
        "data": []
    }
    
## User Login 
Request JWToken and  store `login_timestamp` value in `user_logs` table

#### Request

`POST /api/v1/auth/login`

```bash
curl \
-i -X POST \
-H 'Accept: application/json' \
-H 'Content-Type:application/json' \
-d '{"email": "hello@sara.com","password":"abc1234567"}' \
http://localhost:8888/api/v1/auth/login
```

#### Response

    {
        "status": 200,
        "message": "User access token generated.",
        "data": {
            "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsb2NhbGhvc3Q6ODg4OCIsInN1YiI6MSwiaWF0IjoxNjg3NDk1MDE5LCJleHAiOjE2ODc0OTUzMTl9.PxFcZrSWLtgQZdxtm8C6XpZkHm5URSXisOqFJ52vUz8"
        }
    }
    
## User Logout
store `logout_timestamp` value in `user_logs` table

#### Request

`POST /api/v1/auth/logout`

```bash
curl \
-i -X POST \
-H 'Accept: application/json' \
-H 'Content-Type:application/json' \
-H "Authorization: Bearer {token}" \
http://localhost:8888/api/v1/auth/logout
```

#### Response

    {
        "status": 200,
        "message": "User logged out successfully",
        "data": []
    }
    
## Status Codes

Response returns the following status codes in its API:

| Status Code | Description |
| :--- | :--- |
| 200 | `OK` |
| 201 | `CREATED` |
| 400 | `BAD REQUEST` |
| 404 | `NOT FOUND` |
| 429 | `TOO MANY REQUESTS` |
| 500 | `INTERNAL SERVER ERROR` |

## App Configuration

```php
// config.php

return [
  'database' => [
    'host' => 'localhost',
    'port' => 3306,
    'dbname' => 'digitas_db',
    'charset' => 'utf8mb4'
  ],
  'services' => [
    'jwt' => [
      'secret_key' => '07jCIM2rwtfCW277',
      'expiry_time' => 5 * 60, // Expiry time in seconds (5 minutes)
    ]
  ]
];
```

## Running Tests

To run tests, run the following command

```bash
composer test
```

or

```bash
vendor/bin/phpunit
```


