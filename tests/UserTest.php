<?php

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    protected $http;
    protected $baseUrl = 'http://localhost:8888';

    public function setUp() :void
    {
        global $userId, $userEmail;
        $this->http = new Client(['base_uri' => $this->baseUrl]);

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

        $this->assertEquals(200, $response->getStatusCode());

        $responseData = json_decode($response->getBody()->getContents(), true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertArrayHasKey('data', $responseData);
        
        if ($response->getStatusCode() === 200) {
            $this->assertNotEmpty($responseData['data']);
        } elseif ($response->getStatusCode() === 404) {
            $this->assertEquals('No users found', $responseData['message']);
        }

        $this->assertSame('application/json', $response->getHeaderLine('Content-Type'));
    }

    public function testGetAllUserWithWrongUrl()
    {
        $response = $this->http->request('GET', '/api/v1/user/alll', [
            'http_errors' => false
        ]);
        $this->assertEquals(404, $response->getStatusCode());
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

        $responseData = json_decode($response->getBody()->getContents(), true);

        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('User successfully created', $responseData['message']);
        $this->assertArrayHasKey('data', $responseData);

        $userData = $responseData['data'];
        $this->assertArrayHasKey('id', $userData);
        $this->assertArrayHasKey('name', $userData);
        $this->assertArrayHasKey('email', $userData);
        $this->assertArrayHasKey('password', $userData);

        

        $this->assertSame($jsonData['name'], $userData['name']);
        $this->assertSame($jsonData['email'], $userData['email']);

        global $userId, $userEmail;
        $userId = $userData['id'];
        $userEmail = $userData['email'];

        $this->assertSame('application/json', $response->getHeaderLine('Content-Type'));
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

        $responseData = json_decode($response->getBody()->getContents(), true);

        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Missing required data', $responseData['message']);

        $this->assertSame('application/json', $response->getHeaderLine('Content-Type'));
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

        $responseData = json_decode($response->getBody()->getContents(), true);

        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('OK', $responseData['message']);

        $userData = $responseData['data'];
        $this->assertArrayHasKey('id', $userData);
        $this->assertEquals($userId, $userData['id']);
        $this->assertArrayHasKey('name', $userData);
        $this->assertArrayHasKey('email', $userData);
        $this->assertArrayHasKey('password', $userData);

        $this->assertSame('application/json', $response->getHeaderLine('Content-Type'));
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

        $responseData = json_decode($response->getBody()->getContents(), true);

        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('User not found', $responseData['message']);
        $this->assertEmpty($responseData['data']);

        $this->assertSame('application/json', $response->getHeaderLine('Content-Type'));

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

        $responseData = json_decode($response->getBody()->getContents(), true);

        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('User successfully updated', $responseData['message']);

        $userEmail = $responseData['data']['email'];

        $this->assertSame('application/json', $response->getHeaderLine('Content-Type'));

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

        $responseData = json_decode($response->getBody()->getContents(), true);

        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Missing required data', $responseData['message']);

        $this->assertSame('application/json', $response->getHeaderLine('Content-Type'));
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

        $responseData = json_decode($response->getBody()->getContents(), true);

        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('User not found', $responseData['message']);

        $this->assertSame('application/json', $response->getHeaderLine('Content-Type'));
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

        $responseData = json_decode($response->getBody()->getContents(), true);

        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('User role successfully created', $responseData['message']);

        $this->assertSame('application/json', $response->getHeaderLine('Content-Type'));

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

        $responseData = json_decode($response->getBody()->getContents(), true);

        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Missing required data', $responseData['message']);

        $this->assertSame('application/json', $response->getHeaderLine('Content-Type'));
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

        $responseData = json_decode($response->getBody()->getContents(), true);

        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('OK', $responseData['message']);

        $this->assertSame('application/json', $response->getHeaderLine('Content-Type'));
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

        $responseData = json_decode($response->getBody()->getContents(), true);

        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Missing required id', $responseData['message']);

        $this->assertSame('application/json', $response->getHeaderLine('Content-Type'));
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

        $responseData = json_decode($response->getBody()->getContents(), true);

        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('User successfully deleted', $responseData['message']);

        $this->assertSame('application/json', $response->getHeaderLine('Content-Type'));

    }
    
}