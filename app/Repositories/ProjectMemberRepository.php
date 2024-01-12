<?php

namespace App\Repositories;

use App\Models\{Project, ProjectMember};
use App\Interfaces\ProjectMemberRepositoryInterface;

class ProjectMemberRepository implements ProjectMemberRepositoryInterface
{
    public function addMemberToProject($memberId, $projectId)
    {
        ProjectMember::create(['member_id' => $memberId, 'project_id' => $projectId]);
    }

    public function isMemberInProject($memberId, $projectId) 
    {
        return Project::findOrFail($projectId)->members()->where('member_id', $memberId)->exists();
    }
}