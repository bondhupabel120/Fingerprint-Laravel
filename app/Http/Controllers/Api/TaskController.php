<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $resources = Task::orderBy('id', 'desc')->get();
        return response()->json($resources);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'title' => 'required|max: 255',
            ]);
            Task::saveTaskData($request);
            return response()->json(["status" => true, "message" => "Success"], 200);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => "Unauthorized"], 401);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Task::deleteTaskData($id);
        return response()->json(["status" => true, "message" => "Success"], 200);
    }

    public function sendEmail(Request $request)
    {
        try {
            $task = Task::find($request->task_id);
            $task->assign_to = $request->id;
            $task->save();

            $details['email'] = $request->email;
            dispatch(new SendEmailJob($details));
            return response()->json(["status" => true, "message" => "Success"], 200);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => "Send Mail Failed!"], 401);
        }
    }
}
