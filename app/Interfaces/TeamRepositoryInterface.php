<?php

namespace App\Interfaces;

interface TeamRepositoryInterface extends BaseRepositoryInterface
{
    public function getMembers($teamId);
}