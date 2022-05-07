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
            'name' => 'devTestUser5',
            'email' => 'devTest5@test.com',
            'password'=> 'testtest12'
        ]);

        $res->assertSuccessful();
    }

    /**
     * @test
     */
    public function loginTest() {
        $formData = [
            'email' => 'devTest5@test.com',
            'password' => 'testtest12'
        ];

        $res = $this->post('/api/auth/login', $formData);

        $res->assertStatus(Response::HTTP_OK);
    }

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
        $this->assertEquals(1, DB::table('oauth_access_tokens')->orderByDesc('user_id')->first()->revoked);
    }

    /**
     * Auth
     *
     * @return void
     */
    public function userAuth() {
//        테스트 할 때 마다 유저생성 할수없으니 주석
//        $user = User::create([
//            'name'=> 'dev99test',
//            'email'=> 'devTest99@test.com',
//            'password'=> bcrypt('dev5test')
//        ]);
        $user = User::where('email', '007kibbang@gmail.com')->first();

        if (!auth()->attempt(['email'=>$user->email, 'password'=>'dkrak1245%'])) {
            return response(['message' => 'Login credentials are invaild']);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;
        return $accessToken;
    }

    /** @test */
    public function getUserInfo() {
        $token = $this->userAuth();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('GET', 'api/user/info');

        \Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);
    }

    /** @test */
    public function updateUserInfo() {
        $token = $this->userAuth();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('POST', 'api/user/6', [
            'name' => 'fixName test',
            'email' => 'fixEmail@gmail.com'
        ]);

        \Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);
    }

    /** @test */
    public function deleteUserInfo() {
        $token = $this->userAuth();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('POST', 'api/user/6');

        \Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);
    }
}
