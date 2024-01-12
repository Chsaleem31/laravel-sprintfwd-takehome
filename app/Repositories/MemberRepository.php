<?php
// app/Repositories/MemberRepository.php

namespace App\Repositories;

use App\Models\Member;
use App\Repositories\BaseRepository;
use App\Interfaces\MemberRepositoryInterface;

class MemberRepository extends BaseRepository implements MemberRepositoryInterface
{
    public function __construct(Member $member)
    {
        parent::__construct($member);
    }

    public function updateTeam($id, $teamId)
    {
        $member = $this->repository->find($id);

        if (!$member) {
            return false;
        }

        return $member->update(['team_id' => $teamId]);
    }
}
