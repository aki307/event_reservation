<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function it_tests_user_registration()
    {
        $response = $this->post('/register', [
            // 登録データ
            'login_id' => 'Test User',
            'user_name' => 'Test User',
            'user_type_id' => 2,
            'group_id' => 1,
            'password' => 'testtest',
            "password_confirmation" => 'testtest',
        ]);

        $response->assertStatus(200); // HTTPステータスが200であることを確認
        // その他のアサーションを追加...
    }
}
