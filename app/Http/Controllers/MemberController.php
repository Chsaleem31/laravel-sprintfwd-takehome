<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\MemberRepositoryInterface;
use App\Interfaces\TeamRepositoryInterface;
use Illuminate\Http\Response;


class MemberController extends BaseController
{
    public function __construct(MemberRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Update the team of a member.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function updateTeam(Request $request, $id)
    {
        try {
            $updated = $this->repository->updateTeam(intval($id), $request->input('team_id'));
            if ($updated) {
                return response()->json(['message' => 'Team updated successfully'], 200);
            } else {
                return response()->json(['message' => 'Failed to update team. Member not found.'], Response::HTTP_NOT_FOUND);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update team'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
