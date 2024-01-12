<?php

namespace App\Interfaces;

interface ProjectMemberRepositoryInterface
{
    public function addMemberToProject($memberId, $projectId);

    public function isMemberInProject($memberId, $projectId);
}
