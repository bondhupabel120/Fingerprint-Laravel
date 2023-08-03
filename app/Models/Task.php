<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $appends = ["assign_name"];
    public function getAssignNameAttribute()
    {
        return $this->assign_user->name ?? "";
    }

    public function assign_user()
    {
        return $this->belongsTo(User::class, 'assign_to', 'id');
    }

    public static function saveTaskData($request)
    {
        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $request->deadline,
            'status' => $request->status ? $request->status : "Open",
        ]);
    }
    public static function deleteTaskData($id)
    {
        $resource = Task::find($id);
        if ($resource != null || $resource != '' || $resource != "") {
            $resource->delete();
        }
    }
}
