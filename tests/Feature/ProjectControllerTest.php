<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Interfaces\ProjectRepositoryInterface;
use App\Interfaces\ProjectMemberRepositoryInterface;
use Database\Factories\{TeamFactory, MemberFactory, ProjectFactory};
use Mockery;

class ProjectControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $projectRepositoryMock;
    protected $projectMemberRepositoryMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->projectRepositoryMock = Mockery::mock(ProjectRepositoryInterface::class);
        $this->projectMemberRepositoryMock = Mockery::mock(ProjectMemberRepositoryInterface::class);

        $this->app->instance(ProjectRepositoryInterface::class, $this->projectRepositoryMock);
        $this->app->instance(ProjectMemberRepositoryInterface::class, $this->projectMemberRepositoryMock);
    }

    /** @test */
    public function it_can_list_items()
    {
        $this->projectRepositoryMock->shouldReceive('all')->andReturn(['item1', 'item2']);

        $response = $this->get('api/projects');

            $response->assertStatus(200)
            ->assertJson(['item1', 'item2']);
    }

    /** @test */
    public function it_can_create_an_item()
    {
        $this->projectRepositoryMock->shouldReceive('store')->andReturn(['id' => 1, 'name' => 'New Item']);

        $response = $this->post('api/projects', ['name' => 'New Item']);

        $response->assertStatus(201)
            ->assertJson(['id' => 1, 'name' => 'New Item']);
    }

    /** @test */
    public function it_can_show_an_item()
    {
        $this->projectRepositoryMock->shouldReceive('find')->with(1)->andReturn(['id' => 1, 'name' => 'Sample Item']);

        $response = $this->get('api/projects/1');

        $response->assertStatus(200)
            ->assertJson(['id' => 1, 'name' => 'Sample Item']);
    }

    /** @test */
    public function it_can_update_an_item()
    {
        $this->projectRepositoryMock->shouldReceive('update')->with(['name' => 'Updated Item'], 1)->andReturn(['id' => 1, 'name' => 'Updated Item']);

        $response = $this->put('api/projects/1', ['name' => 'Updated Item']);

        $response->assertStatus(200)
            ->assertJson(['id' => 1, 'name' => 'Updated Item']);
    }


    /** @test */
    public function it_can_add_member_to_project()
    {
        $team = TeamFactory::new()->create();
        $member = MemberFactory::new()->create(['team_id' => $team->id]);
        $project = ProjectFactory::new()->create();

        $this->projectMemberRepositoryMock->shouldReceive('isMemberInProject')->with($member->id, $project->id)->andReturn(false);
        $this->projectMemberRepositoryMock->shouldReceive('addMemberToProject')->with($member->id, $project->id);

        $response = $this->post("/api/projects/{$project->id}/add-member/{$member->id}");

        $response->assertStatus(200);

        $this->assertTrue($response->original);
    }

    /** @test */
    public function it_returns_false_if_member_already_in_project()
    {
        $team = TeamFactory::new()->create();
        $member = MemberFactory::new()->create(['team_id' => $team->id]);
        $project = ProjectFactory::new()->create();

        $this->projectMemberRepositoryMock->shouldReceive('isMemberInProject')->with($member->id, $project->id)->andReturn(true);

        $response = $this->post("/api/projects/{$project->id}/add-member/{$member->id}");

        $response->assertStatus(200);

        $this->assertFalse($response->original);
    }

    /** @test */
    public function it_can_get_members_of_project()
    {
        $team = TeamFactory::new()->create();
        $project = ProjectFactory::new()->create();
        $members = MemberFactory::new()->count(3)->create(['team_id' => $team->id]);

        $this->projectRepositoryMock->shouldReceive('getMembers')->with($project->id)->andReturn($members);

        $response = $this->get("/api/projects/{$project->id}/members");

        $response->assertStatus(200);

        $response->assertJson(['members' => $members->toArray()]);
    }

    /** @test */
    public function it_can_destroy_an_item()
    {
        $this->projectRepositoryMock->shouldReceive('delete')->with(1);

        $response = $this->delete('api/projects/1');

        $response->assertStatus(204);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
}
