<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\UserDataSource\UserDataSource;
use App\Domain\User;
use Tests\TestCase;
use Mockery;

class GetUserDataTest extends TestCase
{
    private UserDataSource $userDataSource;
    protected function setUp(): void
    {
        parent::setUp();
        $this->userDataSource = Mockery::mock(UserDataSource::class);
        $this->app->bind(UserDataSource::class, function () {
            return $this->userDataSource;
        });
    }

    /**
     * @test
     */
    public function whenUserIdResquestedIfNotExistUserWithEmailThenError()
    {
        $this->userDataSource->expects('findByEmail')->with('email@email.com')->andReturnNull();

        $response = $this->get('/api/user/email@email.com');

        $response->assertNotFound();
        $response->assertExactJson(['status' => 'error', 'message' => 'usuario no encontrado']);
    }

    /**
     * @test
     */
    public function whenUserIdResquestedIfExistUserWithEmailThenReturnId()
    {
        $this->userDataSource->expects('findByEmail')->with('email@email.com')->andReturn(new User(1, 'email@email.com'));

        $response = $this->get('/api/user/email@email.com');

        $response->assertOk();
        $this->assertEquals(1, $response['id']);
        $this->assertEquals('email@email.com', $response['email']);
    }

    /**
     * @test
     */
    public function whenUsersListRequestedIfEmptyThenReturnEmptyList()
    {
        $this->userDataSource->expects('getAll')->andReturn([]);

        $response = $this->get('/api/users');

        $response->assertOk();
        $response->assertExactJson([]);
    }

    /**
     * @test
     */
    public function whenUsersListRequestedIfNotEmptyThenReturnUsersList()
    {
        $this->userDataSource->expects('getAll')->andReturn([new User(1, 'email@email.com'),new User(2, 'otro_email@email.com')]);

        $response = $this->get('/api/users');

        $response->assertOk();
        $firstUser = (array) json_decode($response[0]);
        $secondUser = (array) json_decode($response[1]);
        $this->assertEquals($firstUser, ['id' => 1, 'email' => 'email@email.com']);
        $this->assertEquals($secondUser, ['id' => 2,'email' => 'otro_email@email.com']);
    }

    /**
     * @test
     */
    public function whenEarlyAdopterIsCheckedUpIfNotFoundThenError()
    {
        $this->userDataSource->expects('findByEmail')->with('email@email.com')->andReturnNull();

        $response = $this->get('/api/early_adopter/email@email.com');

        $response->assertNotFound();
        $response->assertExactJson(['status' => 'error', 'message' => 'usuario no encontrado']);
    }

    /**
     * @test
     */
    public function whenEarlyAdopterIsCheckedUpIfItIsThenMessage()
    {
        $this->userDataSource->expects('findByEmail')->with('email@email.com')->andReturn(new User(1, 'email@email.com'));

        $response = $this->get('/api/early_adopter/email@email.com');

        $response->assertOk();
        $response->assertExactJson(['message' => 'El usuario es early adopter']);
    }

    /**
     * @test
     */
    public function whenEarlyAdopterIsCheckedUpIfItIsntThenMessage()
    {
        $this->userDataSource->expects('findByEmail')->with('email@email.com')->andReturn(new User(101, 'email@email.com'));

        $response = $this->get('/api/early_adopter/email@email.com');

        $response->assertOk();
        $response->assertExactJson(['message' => 'El usuario no es early adopter']);
    }
}
