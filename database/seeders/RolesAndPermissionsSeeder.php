<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // User management permissions
        $createUserPermission = Permission::firstOrCreate(['name' => 'create user']);
        $viewUserPermission = Permission::firstOrCreate(['name' => 'view user']);
        $updateUserPermission = Permission::firstOrCreate(['name' => 'update user']);
        $deleteUserPermission = Permission::firstOrCreate(['name' => 'delete user']);
        $restoreUserPermission = Permission::firstOrCreate(['name' => 'restore user']);
        $assignRoleUserPermission = Permission::firstOrCreate(['name' => 'assign role user']);
        $listUsersPermission = Permission::firstOrCreate(['name' => 'list users']);

        // Team management permissions
        $createTeamPermission = Permission::firstOrCreate(['name' => 'create team']);
        $viewTeamPermission = Permission::firstOrCreate(['name' => 'view team']);
        $updateTeamPermission = Permission::firstOrCreate(['name' => 'update team']);
        $deleteTeamPermission = Permission::firstOrCreate(['name' => 'delete team']);
        $listTeamsPermission = Permission::firstOrCreate(['name' => 'list teams']);
        $addMemberTeamPermission = Permission::firstOrCreate(['name' => 'add member team']);
        $removeMemberTeamPermission = Permission::firstOrCreate(['name' => 'remove member team']);
        $assignProjectTeamPermission = Permission::firstOrCreate(['name' => 'assign project team']);
        $removeProjectTeamPermission = Permission::firstOrCreate(['name' => 'remove project team']);
        $assignRolesTeamPermission = Permission::firstOrCreate(['name' => 'assign roles team']);

        // Project management permissions
        $createProjectPermission = Permission::firstOrCreate(['name' => 'create project']);
        $viewProjectPermission = Permission::firstOrCreate(['name' => 'view project']);
        $updateProjectPermission = Permission::firstOrCreate(['name' => 'update project']);
        $deleteProjectPermission = Permission::firstOrCreate(['name' => 'delete project']);
        $listProjectsPermission = Permission::firstOrCreate(['name' => 'list projects']);
        $restoreProjectPermission = Permission::firstOrCreate(['name' => 'restore project']);
        $assignTeamProjectPermission = Permission::firstOrCreate(['name' => 'assign team project']);
        $removeTeamProjectPermission = Permission::firstOrCreate(['name' => 'remove team project']);
        $markProjectCompletePermission = Permission::firstOrCreate(['name' => 'mark project complete']);
        $viewProjectReportPermission = Permission::firstOrCreate(['name' => 'view project report']);

        // Milestone management permissions
        $createMilestonePermission = Permission::firstOrCreate(['name' => 'create milestone']);
        $viewMilestonePermission = Permission::firstOrCreate(['name' => 'view milestone']);
        $updateMilestonePermission = Permission::firstOrCreate(['name' => 'update milestone']);
        $deleteMilestonePermission = Permission::firstOrCreate(['name' => 'delete milestone']);
        $listMilestonesPermission = Permission::firstOrCreate(['name' => 'list milestones']);
        $markMilestoneCompletePermission = Permission::firstOrCreate(['name' => 'mark milestone complete']);

        // Task Permissions
        $createTaskPermission = Permission::firstOrCreate(['name' => 'create task']);
        $viewTaskPermission = Permission::firstOrCreate(['name' => 'view task']);
        $updateTaskPermission = Permission::firstOrCreate(['name' => 'update task']);
        $deleteTaskPermission = Permission::firstOrCreate(['name' => 'delete task']);
        $listTasksPermission = Permission::firstOrCreate(['name' => 'list tasks']);
        $assignTaskPermission = Permission::firstOrCreate(['name' => 'assign task']);
        $markTaskDonePermission = Permission::firstOrCreate(['name' => 'mark task done']);
        $commentTaskPermission = Permission::firstOrCreate(['name' => 'comment task']);
        $reorderTaskPermission = Permission::firstOrCreate(['name' => 'reorder task']);

        // Comment Permissions
        $createCommentPermission = Permission::firstOrCreate(['name' => 'create comment']);
        $viewCommentPermission = Permission::firstOrCreate(['name' => 'view comment']);
        $updateCommentPermission = Permission::firstOrCreate(['name' => 'update comment']);
        $deleteCommentPermission = Permission::firstOrCreate(['name' => 'delete comment']);
        $pinCommentPermission = Permission::firstOrCreate(['name' => 'pin comment']);
        $listCommentsPermission = Permission::firstOrCreate(['name' => 'list comments']);

        // Attachment Permissions
        $uploadAttachmentPermission = Permission::firstOrCreate(['name' => 'upload attachment']);
        $downloadAttachmentPermission = Permission::firstOrCreate(['name' => 'download attachment']);
        $deleteAttachmentPermission = Permission::firstOrCreate(['name' => 'delete attachment']);
        $renameAttachmentPermission = Permission::firstOrCreate(['name' => 'rename attachment']);
        $listAttachmentsPermission = Permission::firstOrCreate(['name' => 'list attachments']);

        // Client Permissions
        $createClientPermission = Permission::firstOrCreate(['name' => 'create client']);
        $viewClientPermission = Permission::firstOrCreate(['name' => 'view client']);
        $updateClientPermission = Permission::firstOrCreate(['name' => 'update client']);
        $deleteClientPermission = Permission::firstOrCreate(['name' => 'delete client']);
        $listClientsPermission = Permission::firstOrCreate(['name' => 'list clients']);
        $restoreClientPermission = Permission::firstOrCreate(['name' => 'restore client']);

        // update cache to know about the newly created permissions (required if using WithoutModelEvents in seeders)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define Roles and assign permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions(Permission::all());

        $projectManagerRole = Role::firstOrCreate(['name' => 'project manager']);
        $projectManagerRole->syncPermissions([
            // Team management permissions
            $assignProjectTeamPermission,
            $removeProjectTeamPermission,
            // Project management permissions
            $createProjectPermission,
            $viewProjectPermission,
            $updateProjectPermission,
            $deleteProjectPermission,
            $listProjectsPermission,
            $restoreProjectPermission,
            $assignTeamProjectPermission,
            $removeTeamProjectPermission,
            $markProjectCompletePermission,
            $viewProjectReportPermission,
            // Milestone management permissions
            $createMilestonePermission,
            $viewMilestonePermission,
            $updateMilestonePermission,
            $deleteMilestonePermission,
            $listMilestonesPermission,
            $markMilestoneCompletePermission,
            // Task permissions
            $createTaskPermission,
            $viewTaskPermission,
            $updateTaskPermission,
            $deleteTaskPermission,
            $listTasksPermission,
            $assignTaskPermission,
            $markTaskDonePermission,
            $commentTaskPermission,
            $reorderTaskPermission,
        ]);

        $teamLeadRole = Role::firstOrCreate(['name' => 'team lead']);
        $teamLeadRole->syncPermissions([
            // Task permissions
            $createTaskPermission,
            $viewTaskPermission,
            $updateTaskPermission,
            $deleteTaskPermission,
            $listTasksPermission,
            $assignTaskPermission,
            $markTaskDonePermission,
            $commentTaskPermission,
            $reorderTaskPermission,
        ]);

        $teamMemberRole = Role::firstOrCreate(['name' => 'team member']);
        $teamMemberRole->syncPermissions([
            // Task permissions
            $viewTaskPermission,
            $listTasksPermission,
            $markTaskDonePermission,
            $commentTaskPermission,
        ]);
    }
}
