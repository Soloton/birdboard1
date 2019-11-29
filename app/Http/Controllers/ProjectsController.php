<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProjectsController extends Controller
{
    /**
     * View all projects.
     *
     * @return Factory|View
     */
    public function index()
    {
        $projects = auth()->user()->accessibleProjects();

        return view('projects.index', compact('projects'));
    }

    /**
     * Show a single project.
     *
     * @param Project $project
     *
     * @return Factory|View
     * @throws AuthorizationException
     */
    public function show(Project $project)
    {
        $this->authorize('update', $project);

        return view('projects.show', compact('project'));
    }

    /**
     * Create a new project.
     *
     * @return Factory|View
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Persist a new project.
     *
     * @return mixed
     */
    public function store()
    {
        $project = auth()->user()->projects()->create($this->validateRequest());

        if (request()->wantsJson()) {
            return ['message' => $project->path()];
        }

        return redirect($project->path());
    }

    /**
     * Edit the project.
     *
     * @param Project $project
     * @return Factory|View
     */
    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    /**
     * Update the project.
     *
     * @param Project $project
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(Project $project)
    {
        $this->authorize('update', $project);

        $project->update($this->validateRequest());

        return redirect($project->path());
    }

    public function destroy(Project $project)
    {
        $this->authorize('manage', $project);

        $project->delete();

        return redirect('projects');
    }

    /**
     * Validate the request attributes.
     *
     * @return array
     */
    protected function validateRequest()
    {
        return request()->validate([
            'title' => 'sometimes|required',
            'description' => 'sometimes|required',
            'notes' => 'nullable'
        ]);
    }
}
