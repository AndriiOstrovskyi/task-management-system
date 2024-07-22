<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\TeamResource;
use App\Http\Requests\StoreTeamRequest;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use App\Models\Team;

class TeamController extends Controller
{
    use HttpResponses;

    public function index()
    {
        $teams = Team::with('users')->get();

        return TeamResource::collection($teams);
    }

    public function store(StoreTeamRequest $request)
    {
        $team = Team::create([
            'name' => $request->name,
        ]);

        $team->users()->attach(Auth::id());

        return new TeamResource($team);
    }

    public function addUserToTeam(Request $request, $teamId)
    {
        $team = Team::findOrFail($teamId);

        if ($team->users->contains(Auth::id())) {
            return $this->error('You are already a member of this team', 403);
        }

        $team->users()->attach(Auth::id());

        $team->load('users');

        return new TeamResource($team);
    }
    public function removeUserFromTeam($teamId, $userId)
    {
        $team = Team::findOrFail($teamId);

        if (!$team->users->contains($userId)) {
            return $this->error('User is not a member of this team', 403);
        }

        $team->users()->detach($userId);

        $team->load('users');

        return new TeamResource($team);
    }
}
