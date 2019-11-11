<?php

namespace App\Http\Controllers;

use App\Project;
use App\Task;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;

class ProjectTasksController extends Controller
{
    /**
     * Add a task to the given project.
     *
     * @param Project $project
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(Project $project)
    {
        $this->authorize('update', $project);

        request()->validate(['body' => 'required']);

        $project->addTask(request('body'));

        return redirect($project->path());
    }

    /**
     * Update the project.
     *
     * @param Project $project
     * @param Task $task
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(Project $project, Task $task)
    {
        $this->authorize('update', $task->project);

        $task->update(request()->validate(['body' => 'required']));

        request('completed') ? $task->complete() : $task->incomplete();

        return redirect($project->path());
    }
}
