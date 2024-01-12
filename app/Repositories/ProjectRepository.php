<?php
// app/Repositories/ProjectRepository.php

namespace App\Repositories;

use App\Models\Project;
use App\Repositories\BaseRepository;
use App\Interfaces\ProjectRepositoryInterface;

class ProjectRepository extends BaseRepository implements ProjectRepositoryInterface
{
    public function __construct(Project $project)
    {
        parent::__construct($project);
    }

    public function getMembers($projectId)
    {
        return Project::findOrFail($projectId)->members;
    }
}
