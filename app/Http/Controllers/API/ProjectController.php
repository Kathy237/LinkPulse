<?php
// app/Http/Controllers/API/ProjectController.php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\Portfolio;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Import important
use Illuminate\Routing\Controller as BaseController;

class ProjectController extends Controller
{
    use AuthorizesRequests;
    /**
     * Ajouter un projet à un portfolio.
     */
    public function store(Request $request, Portfolio $portfolio)
    {
       $this->authorize('update', $portfolio);

        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'github_url' => 'nullable|url|max:500',
            'demo_url' => 'nullable|url|max:500',
            'image' => 'nullable|image|max:2048',
            'sort_order' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('project_images', 'public');
            $validated['image'] = $path;
        }

        $project = $portfolio->projects()->create($validated);

        return new ProjectResource($project);
    }

    /**
     * Mettre à jour un projet.
     */
    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project->portfolio);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:200',
            'description' => 'nullable|string',
            'github_url' => 'nullable|url|max:500',
            'demo_url' => 'nullable|url|max:500',
            'image' => 'nullable|image|max:2048',
            'sort_order' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            if ($project->image) {
                Storage::disk('public')->delete($project->image);
            }
            $path = $request->file('image')->store('project_images', 'public');
            $validated['image'] = $path;
        }

        $project->update($validated);

        return new ProjectResource($project);
    }

    /**
     * Supprimer un projet.
     */
    public function destroy(Project $project)
    {
        $this->authorize('update', $project->portfolio);

        if ($project->image) {
            Storage::disk('public')->delete($project->image);
        }
        $project->delete();

        return response()->json(['message' => 'Projet supprimé avec succès.']);
    }
}