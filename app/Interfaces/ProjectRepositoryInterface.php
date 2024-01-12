<?php

namespace App\Interfaces;

interface ProjectRepositoryInterface extends BaseRepositoryInterface
{
    public function getMembers($projectId);
}