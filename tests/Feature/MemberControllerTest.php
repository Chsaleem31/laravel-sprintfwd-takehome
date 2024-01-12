<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Interfaces\MemberRepositoryInterface;
use App\Models\Team;
use App\Models\Member;
use Mockery;
use Database\Factories\TeamFactory;
use Database\Factories\MemberFactory;

class MemberControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $memberRepositoryMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->memberRepositoryMock = Mockery::mock(MemberRepositoryInterface::class);
        $this->app->instance(MemberRepositoryInterface::class, $this->memberRepositoryMock);
    }

    /** @test */
    public function it_can_list_items()
    {
        $this->memberRepositoryMock->shouldReceive('all')->andReturn(['item1', 'item2']);

        $response = $this->get('api/members');

            $response->assertStatus(200)
            ->assertJson(['item1', 'item2']);
    }

    /** @test */
    public function it_can_create_an_item()
    {
        $this->memberRepositoryMock->shouldReceive('store')->andReturn(['id' => 1, 'name' => 'New Item']);

        $response = $this->post('api/members', ['name' => 'New Item']);

        $response->assertStatus(201)
            ->assertJson(['id' => 1, 'name' => 'New Item']);
    }

    /** @test */
    public function it_can_show_an_item()
    {
        $this->memberRepositoryMock->shouldReceive('find')->with(1)->andReturn(['id' => 1, 'name' => 'Sample Item']);

        $response = $this->get('api/members/1');

        $response->assertStatus(200)
            ->assertJson(['id' => 1, 'name' => 'Sample Item']);
    }

    /** @test */
    public function it_can_update_an_item()
    {
        $this->memberRepositoryMock->shouldReceive('update')->with(['name' => 'Updated Item'], 1)->andReturn(['id' => 1, 'name' => 'Updated Item']);

        $response = $this->put('api/members/1', ['name' => 'Updated Item']);

        $response->assertStatus(200)
            ->assertJson(['id' => 1, 'name' => 'Updated Item']);
    }


    /** @test */
    public function it_can_update_the_team_of_a_member()
    {
        $team = Team::factory()->create();
        $newTeam = Team::factory()->create();
        $member = MemberFactory::new()->create(['team_id' => $team->id]);

        $this->memberRepositoryMock->shouldReceive('updateTeam')->with($member->id, $newTeam->id)->andReturn(true); 

        $response = $this->patch("/api/members/{$member->id}/update-team", ['team_id' => $newTeam->id]);

        $response->assertStatus(200);

        $response->assertJson(['message' => 'Team updated successfully']);
    }

    /** @test */
    public function it_returns_not_found_if_member_not_found()
    {
        $nonExistingMemberId = 999;

        $this->memberRepositoryMock->shouldReceive('updateTeam')->with($nonExistingMemberId, Mockery::any())->andReturn(false);

        $response = $this->patch("/api/members/{$nonExistingMemberId}/update-team", ['team_id' => 1]);

        $response->assertStatus(404);

        $response->assertJson(['message' => 'Failed to update team. Member not found.']);
    }

    /** @test */
    public function it_handles_exception_and_returns_internal_server_error()
    {
        $team = Team::factory()->create();
        $member = MemberFactory::new()->create(['team_id' => $team->id]);

        $this->memberRepositoryMock->shouldReceive('updateTeam')->with($member->id, $team->id)->andThrow(new \Exception('Some error'));

        $response = $this->patch("/api/members/{$member->id}/update-team", ['team_id' => $team->id]);

        $response->assertStatus(500);

        $response->assertJson(['message' => 'Failed to update team']);
    }

    /** @test */
    public function it_can_destroy_an_item()
    {
        $this->memberRepositoryMock->shouldReceive('delete')->with(1);

        $response = $this->delete('api/members/1');

        $response->assertStatus(204);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
}
