<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\TeamRepositoryInterface;

class TeamController extends BaseController
{
    public function __construct(TeamRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function getMembers($teamId)
    {
        $team = $this->repository->find($teamId);

        if (!$team) {
            return response()->json(['message' => 'Team not found'], 404);
        }

        $members = $this->repository->getMembers($team);

        return response()->json(['members' => $members], 200);
    }
}

