<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(int $projectId)
    {
        $tasks = Task::where('project_id', $projectId)->orderBy('priority')->get();
        return response()->json($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Project $project)
    {
        $maxPriority = (Task::where('project_id', $project->id)->max('priority') ?? 0) + 1;

        // Handling invalid inputs
        $priority = floor(request()->input('priority'));

        if ($priority < 1) {
            $priority = 1;
        }

        if ($priority > $maxPriority) {
            $priority = $maxPriority;
        }

        Task::where('project_id', $project->id)->where('priority', '>=', $priority)->update([
            'priority' => DB::raw('priority + 1'),
        ]);

        $task = new Task;
        $task->name = request()->input('name');
        $task->project_id = $project->id;
        $task->priority = $priority;
        $task->save();
        return response()->json(null, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $task = Task::findOrFail($id);

        // Update the priority of tasks after the selected task
        Task::where('project_id', $task->project_id)->where('priority', '>=', $request->input('priority'))->update([
            'priority' => DB::raw('priority + 1'),
        ]);

        $task->priority = $request->input('priority');
        $task->save();
        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::findOrFail($id);

        // Update the priority of tasks after the selected task
        Task::where('priority', '>', $task->priority)->update([
            'priority' => DB::raw('priority - 1'),
        ]);

        $task->delete();
        return response()->json(null, 204);
    }
}
