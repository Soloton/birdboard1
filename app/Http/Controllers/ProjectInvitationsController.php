<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectInvitationTest;
use App\Project;
use App\User;

class ProjectInvitationsController extends Controller
{
    public function store(Project $project, ProjectInvitationTest $request)
    {
        $user = User::whereEmail(request('email'))->first();

        $project->invite($user);

        return redirect($project->path());
    }
}
