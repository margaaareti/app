<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TasksResource;
use App\Models\Task;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TasksController extends Controller
{

    use HttpResponse;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $user = Auth::user();

        $tasks = Task::where('user_id', $user->id)->get();

        return TasksResource::collection($tasks);
    }


    public function store(StoreTaskRequest $request)
    {
        $request->validated($request->all());

        $task = Task::create([
            'user_id' => Auth::user()->id,
            'name' => $request->name,
            'description' => $request->description,
            'priority' => $request->priority,
        ]);

        return new TasksResource($task);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {

        return $this->isNotAuthorized($task) ? $this->isNotAuthorized($task) : new TasksResource($task);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {

        if (Auth::user()->id !== $task->user_id) {
            return $this->onError('', 'You are not authorized to make this request', 403);
        }

        $task->update($request->all());

        return new TasksResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {


        return $this->isNotAuthorized($task) ? $this->isNotAuthorized($task) : $task->delete();
    }



    private function isNotAuthorized($task) {

        if (Auth::user()->id !== $task->user_id) {

            return $this->onError('', 'You are not authorized to make this request', 403);
        }

    }
}
