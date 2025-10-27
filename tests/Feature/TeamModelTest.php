<?php

namespace Tests\Feature;

use App\Enums\UserRoles;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use App\Services\TeamService;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TeamModelTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected TeamService $teamService;

    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->teamService = new TeamService;
    }

    public function test_team_can_be_created(): void
    {
        $teamName = 'Development Team';
        $teamDescription = 'Handles all development tasks';
        Team::factory()->create([
            'name' => $teamName,
            'description' => $teamDescription,
        ]);

        $this->assertDatabaseHas('teams', [
            'name' => $teamName,
            'description' => $teamDescription,
        ]);
    }

    public function test_team_lead_can_be_leader(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $team = Team::factory()->create();
        $this->assertFalse($team->hasLeader());

        $lead = User::factory()->create();
        $lead->assignRole(UserRoles::TEAM_LEAD->value);
        $this->teamService->setLeader($team, $lead->id, $lead);

        $this->assertTrue($team->hasLeader());
        $this->assertEquals($lead->id, $team->lead()->id);
    }

    public function test_non_team_lead_users_cannot_be_leader(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $team = Team::factory()->create();

        $member = User::factory()->create();
        $member->assignRole(UserRoles::TEAM_MEMBER->value);
        $this->expectException(\InvalidArgumentException::class);
        $this->teamService->setLeader($team, $member->id, $member);
    }

    public function test_team_members_can_be_added(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $team = Team::factory()->create();

        $member1 = User::factory()->create();
        $member1->assignRole(UserRoles::TEAM_MEMBER->value);
        $this->teamService->addMember($team, $member1, UserRoles::TEAM_MEMBER->value, $member1);

        $member2 = User::factory()->create();
        $member2->assignRole(UserRoles::TEAM_MEMBER->value);
        $this->teamService->addMember($team, $member2, UserRoles::TEAM_MEMBER->value, $member2);

        $members = $team->members()->get();
        $this->assertCount(2, $members);
        $this->assertTrue($members->contains($member1));
        $this->assertTrue($members->contains($member2));
    }

    public function test_members_can_be_added_in_bulk(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $team = Team::factory()->create();

        $member1 = User::factory()->create();
        $member1->assignRole(UserRoles::TEAM_MEMBER->value);

        $member2 = User::factory()->create();
        $member2->assignRole(UserRoles::TEAM_MEMBER->value);

        $this->teamService->addMembers($team, [$member1->id => UserRoles::TEAM_MEMBER->value, $member2->id => UserRoles::TEAM_MEMBER->value], $member1);

        $members = $team->members()->get();
        $this->assertCount(2, $members);
        $this->assertTrue($members->contains($member1));
        $this->assertTrue($members->contains($member2));
    }

    public function test_only_valid_members_will_be_added_during_bulk_addition(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $team = Team::factory()->create();

        $member1 = User::factory()->create();
        $member1->assignRole(UserRoles::TEAM_MEMBER->value);
        $this->teamService->addMember($team, $member1, UserRoles::TEAM_MEMBER->value, $member1);

        $member2 = User::factory()->create();
        $member2->assignRole(UserRoles::TEAM_MEMBER->value);

        ['valid_users' => $validMembers, 'invalid_users' => $invalidMembers] = $this->teamService->addMembers($team, [$member1->id => UserRoles::TEAM_MEMBER->value, $member2->id => UserRoles::TEAM_MEMBER->value], $member1);

        $this->assertEquals(2, $team->memberCount());
        $this->assertCount(1, $validMembers);
        $this->assertCount(1, $invalidMembers);
    }

    public function test_adding_existing_member_throws_exception(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $team = Team::factory()->create();

        $member = User::factory()->create();
        $member->assignRole(UserRoles::TEAM_MEMBER->value);
        $this->teamService->addMember($team, $member, UserRoles::TEAM_MEMBER->value, $member);

        $this->expectException(\InvalidArgumentException::class);
        $this->teamService->addMember($team, $member, UserRoles::TEAM_MEMBER->value, $member);
    }

    public function test_is_member_and_is_lead_checks(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $team = Team::factory()->create();

        $lead = User::factory()->create();
        $lead->assignRole(UserRoles::TEAM_LEAD->value);
        $this->teamService->setLeader($team, $lead->id, $lead);

        $member = User::factory()->create();
        $member->assignRole(UserRoles::TEAM_MEMBER->value);
        $this->teamService->addMember($team, $member, UserRoles::TEAM_MEMBER->value, $member);

        $this->assertTrue($team->isLead($lead));
        $this->assertFalse($team->isLead($member));

        $this->assertTrue($team->isMember($member));
        $this->assertFalse($team->isMember($lead));
    }

    public function test_replaced_team_leader_will_be_demoted(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $team = Team::factory()->create();

        $firstLead = User::factory()->create();
        $firstLead->assignRole(UserRoles::TEAM_LEAD->value);
        $this->teamService->setLeader($team, $firstLead->id, $firstLead);

        $secondLead = User::factory()->create();
        $secondLead->assignRole(UserRoles::TEAM_LEAD->value);
        $this->teamService->setLeader($team, $secondLead->id, $secondLead);

        $this->assertTrue($team->isLead($secondLead));
        $this->assertFalse($team->isLead($firstLead));

        $this->assertTrue($team->isMember($firstLead));
    }

    public function test_can_be_updated(): void
    {
        $team = Team::factory()->create([
            'name' => 'Old Name',
            'description' => 'Old Description',
        ]);

        $team->update([
            'name' => 'New Name',
            'description' => 'New Description',
        ]);

        $this->assertDatabaseHas('teams', [
            'id' => $team->id,
            'name' => 'New Name',
            'description' => 'New Description',
        ]);
    }

    public function test_can_be_deleted(): void
    {
        $team = Team::factory()->create();

        $teamId = $team->id;
        $team->forceDelete();

        $this->assertDatabaseMissing('teams', [
            'id' => $teamId,
        ]);
    }

    public function test_members_can_be_removed(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $team = Team::factory()->create();

        $member = User::factory()->create();
        $member->assignRole(UserRoles::TEAM_MEMBER->value);
        $this->teamService->addMember($team, $member, UserRoles::TEAM_MEMBER->value, $member);

        $this->assertTrue($team->hasMember($member));

        $this->teamService->removeMember($team, $member, $member);

        $this->assertFalse($team->hasMember($member));
    }

    public function test_removing_non_member_throws_exception(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $team = Team::factory()->create();

        $member = User::factory()->create();
        $member->assignRole(UserRoles::TEAM_MEMBER->value);

        $this->expectException(\InvalidArgumentException::class);
        $this->teamService->removeMember($team, $member, $member);
    }

    public function test_project_can_be_assigned_to_team(): void
    {
        $team = Team::factory()->create();
        $project = Project::factory()->create();
        $user = User::factory()->create();

        $this->teamService->assignProject($team, $project, null, $user);

        $this->assertTrue($team->worksOnProject($project));
    }

    public function test_project_can_be_removed_from_team(): void
    {
        $team = Team::factory()->create();
        $project = Project::factory()->create();
        $user = User::factory()->create();

        $this->teamService->assignProject($team, $project, null, $user);
        $this->assertTrue($team->worksOnProject($project));

        $this->teamService->removeProject($team, $project, $user);
        $this->assertFalse($team->worksOnProject($project));
    }
}
