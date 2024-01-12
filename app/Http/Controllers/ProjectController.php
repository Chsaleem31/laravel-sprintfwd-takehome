<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\ProjectRepositoryInterface;
use App\Interfaces\ProjectMemberRepositoryInterface;


class ProjectController extends BaseController
{
    protected ProjectMemberRepositoryInterface $projectMemberRepository;

    public function __construct(ProjectRepositoryInterface $repository, ProjectMemberRepositoryInterface $projectMemberRepository)
    {
        parent::__construct($repository);
        $this->projectMemberRepository = $projectMemberRepository;
    }


    public function addMember($projectId, $memberId)
    {
        if ($this->projectMemberRepository->isMemberInProject($memberId, $projectId)) {
            return false; 
        }

        $this->projectMemberRepository->addMemberToProject($memberId, $projectId);

        return true;
    }

    public function getMembers($projectId)
    {
        $members = $this->repository->getMembers($projectId);

        return response()->json(['members' => $members]);
    }

}
