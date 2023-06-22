<?php

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

// require_once __DIR__ . '/../Core/functions.php';

class UserTest extends TestCase
{
    protected $http;

    public function setUp() :void
    {
        global $userId, $userEmail;
        $this->http = new Client(['base_uri' => 'http://localhost:8888']);

    }

    public function tearDown() :void
    {
        $this->http = null;
    }

    public function testGetAllUser()
    {
        $response = $this->http->request('GET', '/api/v1/user/all', [
            'http_errors' => false
        ]);

        $bodyContent = json_decode($response->getBody()->getContents(), true);

        switch ($response->getStatusCode()) {
            case 200:
                $this->assertEquals(200, $response->getStatusCode());
                $this->assertArrayHasKey('message', $bodyContent);
                $this->assertArrayHasKey('data', $bodyContent);
                break;
            case 404:
                $this->assertEquals(404, $response->getStatusCode());
                $this->assertArrayHasKey('message', $bodyContent);
                $this->assertEquals('No users found', $bodyContent['message']);
                break;
        };

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
    }

    public function testGetAllUserWithWrongUrl()
    {
        $response = $this->http->request('GET', '/api/v1/user/alll', [
            'http_errors' => false
        ]);
        $this->assertStringContainsString('<h1>404 - Not Found</h1>', $response->getBody()->getContents());
    }

    public function testCreateUser()
    {
        $faker = Faker\Factory::create();

        $jsonData = [
            'name' => $faker->name(),
            'email' => $faker->email(),
            'password' => '123456789'
        ];

        $response = $this->http->request('POST', '/api/v1/user/create', [
            'json' => $jsonData
        ]);

        $this->assertEquals(201, $response->getStatusCode());

        $bodyContent = json_decode($response->getBody()->getContents(), true);

        $this->assertArrayHasKey('message', $bodyContent);
        $this->assertEquals('User successfully created', $bodyContent['message']);

        $this->assertArrayHasKey('data', $bodyContent);
        $this->assertArrayHasKey('id', $bodyContent['data']);
        $this->assertArrayHasKey('name', $bodyContent['data']);
        $this->assertEquals($jsonData['name'], $bodyContent['data']['name']);
        $this->assertArrayHasKey('email', $bodyContent['data']);
        $this->assertEquals($jsonData['email'], $bodyContent['data']['email']);
        $this->assertArrayHasKey('password', $bodyContent['data']);

        global $userId, $userEmail;
        $userId = $bodyContent['data']['id'];
        $userEmail = $bodyContent['data']['email'];

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
    }

    public function testCreateUserWithMissingData()
    {
        $response = $this->http->request('POST', '/api/v1/user/create', [
            'json' => [
                'name' => 'Luke Skywalker',
                // 'email' => 'luke@tatooine.com',
                'password' => '123456789'
            ],
            'http_errors' => false
        ]);

        $this->assertEquals(400, $response->getStatusCode());

        $bodyContent = json_decode($response->getBody()->getContents(), true);

        $this->assertArrayHasKey('message', $bodyContent);
        $this->assertEquals('Missing required data', $bodyContent['message']);

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
    }

    public function testCreateUserWithWrongUrl()
    {
        $response = $this->http->request('POST', '/api/v1/user/creat', [
            'json' => [
                'name' => 'Luke Skywalker',
                // 'email' => 'luke@tatooine.com',
                'password' => '123456789'
            ],
            'http_errors' => false
        ]);

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertStringContainsString('<h1>404 - Not Found</h1>', $response->getBody()->getContents());
    }

    public function testGetSingleUser()
    {
        global $userId;
        
        $response = $this->http->request('GET', '/api/v1/user/single', [
            'query' => [
                'id' => $userId
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $bodyContent = json_decode($response->getBody()->getContents(), true);

        $this->assertArrayHasKey('message', $bodyContent);
        $this->assertEquals('OK', $bodyContent['message']);
        $this->assertArrayHasKey('id', $bodyContent['data']);
        $this->assertEquals($userId, $bodyContent['data']['id']);
        $this->assertArrayHasKey('name', $bodyContent['data']);
        $this->assertArrayHasKey('email', $bodyContent['data']);
        $this->assertArrayHasKey('password', $bodyContent['data']);

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
    }

    public function testGetSingleUserWithWrongUrl()
    {
        $response = $this->http->request('GET', '/api/v1/user/singl?id=4', [
            'http_errors' => false
        ]);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertStringContainsString('<h1>404 - Not Found</h1>', $response->getBody()->getContents());
    }

    public function testGetSingleUserWithNotExistUserId()
    {
        $response = $this->http->request('GET', '/api/v1/user/single?id=999999', [
            'http_errors' => false
        ]);
        
        $this->assertEquals(404, $response->getStatusCode());

        $bodyContent = json_decode($response->getBody()->getContents(), true);

        $this->assertArrayHasKey('message', $bodyContent);
        $this->assertEquals('User not found', $bodyContent['message']);
        $this->assertEquals([], $bodyContent['data']);

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);

    }

    

    public function testUpdateUser()
    {
        global $userId, $userEmail;

        $faker = Faker\Factory::create();

        $response = $this->http->request('PUT', '/api/v1/user/update', [
            'json' => [
                'id' => $userId,
                'name' => $faker->name(),
                'email' => $faker->email(),
                'password' => '123456789$a'
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $bodyContent = json_decode($response->getBody()->getContents(), true);

        $this->assertArrayHasKey('message', $bodyContent);
        $this->assertEquals('User successfully updated', $bodyContent['message']);

        $userEmail = $bodyContent['data']['email'];

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);

    }

    public function testUpdateUserWithMissingData()
    {
        global $userId;
        $response = $this->http->request('PUT', '/api/v1/user/update', [
            'json' => [
                'id' => $userId,
                // 'name' => 'Luke Skywalker',
                'email' => 'mr.skywalker@tatooine.com',
                'password' => '123456789$a'
            ],
            'http_errors' => false
        ]);

        $this->assertEquals(400, $response->getStatusCode());

        $bodyContent = json_decode($response->getBody()->getContents(), true);

        $this->assertArrayHasKey('message', $bodyContent);
        $this->assertEquals('Missing required data', $bodyContent['message']);

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
    }

    public function testUpdateUserWithWrongUrl()
    {
        global $userId;
        $response = $this->http->request('PUT', '/api/v1/user/updat', [
            'json' => [
                'id' => $userId,
                'name' => 'Luke Skywalker',
                'email' => 'mr.skywalker@tatooine.com'
            ],
            'http_errors' => false
        ]);

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertStringContainsString('<h1>404 - Not Found</h1>', $response->getBody()->getContents());
    }

    public function testUpdateUserWithNotExistUserId()
    {
        $randomUserId = 999999;
        $response = $this->http->request('PUT', '/api/v1/user/update', [
            'json' => [
                'id' => $randomUserId,
                'name' => 'Luke Skywalker',
                'email' => 'mr.skywalker@tatooine.com',
                'password' => '123456789$a'
            ],
            'http_errors' => false
        ]);

        $this->assertEquals(404, $response->getStatusCode());

        $bodyContent = json_decode($response->getBody()->getContents(), true);

        $this->assertArrayHasKey('message', $bodyContent);
        $this->assertEquals('User not found', $bodyContent['message']);

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
    }

    public function testCreateUserRole()
    {
        global $userEmail;
        $response = $this->http->request('POST', '/api/v1/user/role/create', [
            'json' => [
                'email' => $userEmail,
                'role_id' => rand(1, 3)
            ]
        ]);

        $this->assertEquals(201, $response->getStatusCode());

        $bodyContent = json_decode($response->getBody()->getContents(), true);

        $this->assertArrayHasKey('message', $bodyContent);
        $this->assertEquals('User role successfully created', $bodyContent['message']);

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);

    }

    public function testCreateUserRoleWithMissingData()
    {
        global $userEmail;
        $response = $this->http->request('POST', '/api/v1/user/role/create', [
            'json' => [
                // 'email' => $userEmail,
                'role_id' => rand(1, 3)
            ],
            'http_errors' => false
        ]);

        $this->assertEquals(400, $response->getStatusCode());

        $bodyContent = json_decode($response->getBody()->getContents(), true);

        $this->assertArrayHasKey('message', $bodyContent);
        $this->assertEquals('Missing required data', $bodyContent['message']);

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
    }

    public function testGetUserRole()
    {
        global $userId;
        $response = $this->http->request('GET', '/api/v1/user/role', [
            'query' => [
                'id' => $userId
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $bodyContent = json_decode($response->getBody()->getContents(), true);

        $this->assertArrayHasKey('message', $bodyContent);
        $this->assertEquals('OK', $bodyContent['message']);

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
    }

    public function testGetUserRoleWithMissingData()
    {
        global $userId;
        $response = $this->http->request('GET', '/api/v1/user/role', [
            'query' => [
                // 'id' => $userId
            ],
            'http_errors' => false
        ]);

        $this->assertEquals(400, $response->getStatusCode());

        $bodyContent = json_decode($response->getBody()->getContents(), true);

        $this->assertArrayHasKey('message', $bodyContent);
        $this->assertEquals('Missing required id', $bodyContent['message']);

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
    }

    public function testGetUserRoleWithWrongUrl()
    {
        global $userId;
        $response = $this->http->request('GET', '/api/v1/user/rol', [
            'query' => [
                'id' => $userId
            ],
            'http_errors' => false
        ]);

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertStringContainsString('<h1>404 - Not Found</h1>', $response->getBody()->getContents());
    }

    public function testDeleteUser()
    {
        global $userId;
        $response = $this->http->request('DELETE', '/api/v1/user/delete', [
            'json' => [
                'id' => $userId
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $bodyContent = json_decode($response->getBody()->getContents(), true);

        $this->assertArrayHasKey('message', $bodyContent);
        $this->assertEquals('User successfully deleted', $bodyContent['message']);

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);

    }
    
}