<?php

// app/Repositories/ProjectRepository.php

namespace App\Repositories;

use App\Models\Team;
use App\Repositories\BaseRepository;
use App\Interfaces\TeamRepositoryInterface;

class TeamRepository extends BaseRepository implements TeamRepositoryInterface
{
    public function __construct(Team $team)
    {
        parent::__construct($team);
    }

    public function getMembers($team)
    {
        return $team->members;
    }
}
