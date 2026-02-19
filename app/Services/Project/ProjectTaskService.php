<?php

namespace App\Services\Project;

use App\Models\Master\TaskAction;
use App\Models\ProjectTask;

class ProjectTaskService
{
    public function saveProjectTask($request, $project)
    {
        $tasks = $request->tasks ? json_decode($request->tasks, true) : [];
            $removedTasks = $request->removedTasks ? json_decode($request->removedTasks, true) : [];

            if (!empty($tasks)) {
                foreach ($tasks as $key => $task) {
                    if ($task['isNew']) {
                        if ($task['actionId'] > 0) {
                            $task_action = TaskAction::find($task['actionId']);
                        } else {
                            $task_action = TaskAction::create(['name' => $task['otherName']]);
                        }

                        $ins = [
                            'project_id' => $project->id,
                            'task_action_id' => $task_action->id,
                            'task_name' => $task_action->name,
                            'due_date' => $task['dueDate'],
                            'email_alert' => $task['emailAlert'] ? '1' : '0',
                            'comment' => $task['comment'] ?? '',
                            'assigned_to' => $task['assignedId'],
                            'assigned_by' => auth()->id(),
                            'assigned_at' => now(),
                        ];

                        ProjectTask::create($ins);
                    }
                }
            }

            if (!empty($removedTasks)) { //['123', 2]
                ProjectTask::whereIn('id', $removedTasks)->delete();
            }
    }
}