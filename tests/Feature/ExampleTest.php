<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\SecretApi;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testprepareJsonPostResponse_jsonResponse()
    {

        $type = 'json';
        $response = SecretApi::prepareJsonPostResponse(self::createRequest(), $type);
        $this->assertJson($response);
    }

    protected function createRequest()
    {
        $request = new \Illuminate\Http\Request;
        $token = csrf_token();
        return $request->createFromBase(
            \Symfony\Component\HttpFoundation\Request::create(
                $uri = '',
                $method = 'POST',
                $parameters = [],
                $cookies = [],
                $files = [],
                $server =  ['CONTENT_TYPE' => 'application/json'],
                $content = '{"_token":"' . $token . '","id":1,"expireAfter":1,"expireAfterViews":1,"secret":"secret"}'
            )
        );
    }
}
