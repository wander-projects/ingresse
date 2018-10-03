<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class UsersTest extends TestCase
{
    use DatabaseTransactions;

    public function testCreateUser()
    {
        User::create([
            'name' => 'Harry Dickinson',
            'cellphone' => '(11)2600-0718',
            'email' => 'wanderlei_santos@testebom.com',
            'password' => base64_encode('secret')
        ]);

        //Verifica se o dado lá existe no banco de dados
        $this->assertDatabaseHas('users', ['name' => 'Harry Dickinson']);

        $response = $this->json('POST', 'api/users', ['name' => 'Sally Field']);

        $response
            ->assertStatus(201)
            ->assertJson([
                "name" => "Sally Field",
                "cellphone" => "(11)2600-071",
                "email" => "sally_field@hotmail.com",
                "updated_at" => "2018-10-01 02:05:24",
                "created_at" => "2018-10-01 02:05:24",
                "id" =>  58
            ]);
    }

    public function testLogin()
    {
        //Passando as  credenciais para verificar se retorna true ou false
        $response = $this->json(
            'POST',
            'api/login',
            ['email' => 'tevin00@example.com', 'password' => 'secret']);

        //Verifica se token foi gerado com sucesso
        $response
            ->assertStatus(200)
            ->assertJson([
                'token' => true,
            ]);

        //Verifica se status code é 200 e resultou em sucesso
        $response->assertOk();
        $response->assertSuccessful();
    }
}
