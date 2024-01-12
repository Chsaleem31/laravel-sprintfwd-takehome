<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Interfaces\TeamRepositoryInterface;
use Mockery;
use Database\Factories\TeamFactory;
use Database\Factories\MemberFactory;

class TeamControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $repositoryMock;
    private $teamRepository;
    private $memberRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->repositoryMock = Mockery::mock(TeamRepositoryInterface::class);

        $this->app->instance(TeamRepositoryInterface::class, $this->repositoryMock);
    }

    /** @test */
    public function it_can_list_items()
    {
        $this->repositoryMock->shouldReceive('all')->andReturn(['item1', 'item2']);

        $response = $this->get('api/teams');

            $response->assertStatus(200)
            ->assertJson(['item1', 'item2']);
    }

    /** @test */
    public function it_can_create_an_item()
    {
        $this->repositoryMock->shouldReceive('store')->andReturn(['id' => 1, 'name' => 'New Item']);

        $response = $this->post('api/teams', ['name' => 'New Item']);

        $response->assertStatus(201)
            ->assertJson(['id' => 1, 'name' => 'New Item']);
    }

    /** @test */
    public function it_can_show_an_item()
    {
        $this->repositoryMock->shouldReceive('find')->with(1)->andReturn(['id' => 1, 'name' => 'Sample Item']);

        $response = $this->get('api/teams/1');

        $response->assertStatus(200)
            ->assertJson(['id' => 1, 'name' => 'Sample Item']);
    }

    /** @test */
    public function it_can_update_an_item()
    {
        $this->repositoryMock->shouldReceive('update')->with(['name' => 'Updated Item'], 1)->andReturn(['id' => 1, 'name' => 'Updated Item']);

        $response = $this->put('api/teams/1', ['name' => 'Updated Item']);

        $response->assertStatus(200)
            ->assertJson(['id' => 1, 'name' => 'Updated Item']);
    }

    /** @test */
    public function it_can_get_members_of_a_team()
    {
        $team = TeamFactory::new()->create();
        $members = MemberFactory::new()->count(3)->create(['team_id' => $team->id]);

        $this->repositoryMock->shouldReceive('find')->with($team->id)->andReturn($team);

        $this->repositoryMock->shouldReceive('getMembers')->with($team)->andReturn($members);

        $response = $this->get("/api/teams/{$team->id}/members");

        $response->assertStatus(200);

        $response->assertJson([
            'members' => $members->toArray(),
        ]);
    }

    /** @test */
    public function it_can_destroy_an_item()
    {
        $this->repositoryMock->shouldReceive('delete')->with(1);

        $response = $this->delete('api/teams/1');

        $response->assertStatus(204);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
}
