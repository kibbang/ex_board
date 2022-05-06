<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AuthTest extends TestCase
{
    use WithFaker;
//    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /** @test */
    public function registerTest() {
        $res = $this->post('/api/auth/register', [
            'name' => 'devTestUser2',
            'email' => 'devTest2@test.com',
            'password'=> 'testtest12'
        ]);

        $res->assertSuccessful();
    }

    /**
     * @test
     */
    public function loginTest() {
        $formData = [
            'email' => 'devTest2@test.com',
            'password' => 'testtest12'
        ];

        $res = $this->post('/api/auth/login', $formData);

        $res->assertStatus(Response::HTTP_OK);
    }

//    /** @test */
//    public function currentUserInfo() {
//        $formData = [
//            'email' => 'devTest2@test.com',
//            'password' => 'testtest12'
//        ];
//
//        $res = $this->post('/api/auth/login', $formData);
//
//        \Log::debug(var_export($res, true));
//    }

    /**
     * @test
     */
    public function logoutTest() {
        $user = User::factory()->create();
        $token = $user->createToken('Personal Access Token')->accessToken; // AuthController의 createAcessToken()과 토큰 주는 방식이 다른데 어떻게 테스트 해야하는지 모르겠다.
        $this->assertEquals(0, DB::table('oauth_access_tokens')->first()->revoked);

        $response = $this->post('/api/auth/logout', [], ['Authorization' => 'Bearer ' . $token]);
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['message']);
        $this->assertEquals(1, DB::table('oauth_access_tokens')->orderByDesc('created_at')->first()->revoked);
    }

//    /**
//     * @test
//     */
//    public function getUserTest() {
//        $res = $this->get('/api/user/info');
//        \Log::debug("===================================getUserTest================================");
//        \Log::debug(var_export($res, true));
////        $res = $this->get('/api/user/info');
////
//       $res->assertSuccessful();
//    }
}
