
# REST API Assessment

This is a simple PHP application providing a REST API.

Looking for the Laravel version of this application? Click [here](https://github.com/ammarkero/laravel-rest-api-assessment "here")

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
    - [get all users](#get-list-of-users)
    - [create a new user](#create-a-new-user)
    - [get a specific user](#get-a-specific-user)
    - [update an user](#update-a-specific-user)
    - [store user's role](#create-a-new-users-role) `[many-to-many relationship]`
    - [get user's role(s)](#get-list-of-users-role) `[many-to-many relationship]`
    - [delete an user](#delete-a-specific-user)
- Authentication:
    - [user login](#user-login) (generate JWToken)
    - [user logout](#user-logout)
- External data:
    - [get data from external api call](#get-data-from-external-api-call)
    - [store data from external api call](#store-data-from-external-api-call)
- Post:
    - [store post's image](#store-posts-image) `[polymorhpic relationship]`
    - [get post's image(s)](#get-posts-images) `[polymorhpic relationship]`

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

`POST /api/v1/user/create`

```bash
url \
-i -X POST \
-H 'Accept: application/json' \
-H 'Content-Type:application/json' \
-d '{"name": "Xavier", "email": "hello@xavier.com","password":"12345678"}' \
http://localhost:8888/api/v1/user/create
```

#### Response

```json
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
```

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

```json
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
```
    
## Update a specific User

#### Request

`PUT /api/v1/user/update`

```bash
curl \
-i -X PUT \
-H 'Accept: application/json' \
-H 'Content-Type:application/json' \
-d '{"id": "1","name": "Sara","email": "hello@sara.com","password": "abc1234567"} \
http://localhost:8888/api/v1/user/update
```

#### Response

```json
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
```
    
## Create a new User's role

#### Request

`POST /api/v1/user/role/create`

```bash
curl \
-i -X POST \
-H 'Accept: application/json' \
-H 'Content-Type:application/json' \
-d '{"email": "hello@sara.com","role_id": "1"}'\
http://localhost:8888/api/v1/user/role/create
```

#### Response

```json
{
    "status": 200,
    "message": "OK",
    "data": [
        {
            "title": "Admin"
        }
    ]
}
```

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

```json
{
    "status": 200,
    "message": "OK",
    "data": [
        {
            "title": "Admin"
        }
    ]
}
```
    
## Delete a specific User

#### Request

`DELETE /api/v1/user/delete`
```bash
curl \
-i -X DELETE \
-H 'Accept: application/json' \
-H 'Content-Type:application/json' \
-d '{"id": "13"}' \
http://localhost:8888/api/v1/user/delete
```

#### Response

```json
{
    "status": 200,
    "message": "User successfully deleted",
    "data": []
}
```
    
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

```json
{
    "status": 200,
    "message": "User access token generated.",
    "data": {
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsb2NhbGhvc3Q6ODg4OCIsInN1YiI6MSwiaWF0IjoxNjg3NDk1MDE5LCJleHAiOjE2ODc0OTUzMTl9.PxFcZrSWLtgQZdxtm8C6XpZkHm5URSXisOqFJ52vUz8"
    }
}
```
    
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

```json
{
    "status": 200,
    "message": "User logged out successfully",
    "data": []
}
```

## Get data from external API call

#### Request

`GET /api/v1/external-data/read`

```bash
curl \
-i \
-H 'Accept: application/json' \
http://localhost:8888/api/v1/external-data/read
```

#### Response

```json
{
    "status": 200,
    "message": "External data retrieved successfully",
    "data": [
        {
            "userId": 1,
            "id": 1,
            "title": "delectus aut autem",
            "completed": false
        },
        {
            "userId": 1,
            "id": 2,
            "title": "quis ut nam facilis et officia qui",
            "completed": false
        },
        {
            "userId": 1,
            "id": 2,

            ...

```

## Store data from external API call

#### Request

`POST /api/v1/external-data/read`

```bash
curl \
-i -X POST \
-H 'Accept: application/json' \
-H 'Content-Type: application/json' \
http://localhost:8888/api/v1/external-data/store
```

#### Response

```json
{
    "status": 200,
    "message": "20 external data retrieved and stored successfully",
    "data": []
}
```

## Store Post's image

#### Request

`POST /api/v1/post/image/store`

```bash
curl \
-i -X POST \
-H 'Accept: application/json' \
-H 'Content-Type: application/json' \
-d '{"post_id": "1","image_path": "placeholder-2.jpg"}'
http://localhost:8888/api/v1/post/image/store
```

#### Response

```json
{
    "status": 200,
    "message": "Image stored successfully",
    "data": []
}
```

## Get Post's image(s)

#### Request

`GET /api/v1/post/image/read?:id`

```bash
curl \
-i \
-H 'Accept: application/json' \
http://localhost:8888/api/v1/post/image?id=1
```

#### Response

```json
{
    "status": 200,
    "message": "OK",
    "data": [
        {
            "image_path": "unicorn.jpg"
        },
        {
            "image_path": "cat.jpg"
        }
    ]
}
```
    
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
    'dbname' => 'rest_api_db',
    'charset' => 'utf8mb4'
  ],
  'services' => [
    'jwt' => [
      'secret_key' => 'your-secret-key',
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

## Useful Resources
- Locate (and pick one) and import SQL Dump into your database.

```
root
|
|- rest_api_db.sql
|- rest_api_db.gz
|
```

-  Locate and import Postman Collection to test API calls via Postman.

```
root
|
|- rest_api_postman_collection.json
|
```