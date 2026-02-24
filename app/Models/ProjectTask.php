<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectTask extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'task_action_id',
        'job_file_id',
        'task_name',
        'due_date',
        'complete_date',
        'email_alert',
        'comment',
        'assigned_to',
        'assigned_by',
        'assigned_at',
        'status'
    ];

    public function actionType()
    {
        return $this->belongsTo("App\Models\Master\TaskAction", "task_action_id");
    }

    public function assignedToUser()
    {
        return $this->belongsTo(User::class, 'assigned_to')->select('id', 'name', 'email');
    }

    public function assignedByUser()
    {
        return $this->belongsTo(User::class, 'assigned_by')->select('id', 'name', 'email');
    }

    public function project()
    {
        return $this->belongsTo(ProjectDetail::class, 'project_id')->select('id', 'project_name');
    }
}
