<?php

namespace App\Interfaces;

interface MemberRepositoryInterface extends BaseRepositoryInterface
{
    public function updateTeam($id, $teamId);
}