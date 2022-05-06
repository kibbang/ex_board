<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class CommentTest extends TestCase
{
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
    public function addComment() {
        $token = $this->userAuth();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('POST', 'api/posts/2/comments', [
            'comment' => 'testComment in 0506'
        ]);

        \Log::info(1, [$response->getContent()]); // 로그 확인용 (사실 불필요함)

        $response->assertStatus(200);
    }

    /** @test */
    public function getComments() {
        $response =  $this->json('GET', 'api/posts/2/comments');

        \Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);
    }

    /** @test */
    public function updateComment() {
        $token = $this->userAuth();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('PUT', 'api/comments/10', [
            'comment' => 'modify comment1 in 0506',
            'updated_at' => Carbon::now()
        ]);

        \Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);
    }

    /** @test */
    public function deleteComment() {
        $token = $this->userAuth();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('DELETE', 'api/comments/14');

        \Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);
    }
}
