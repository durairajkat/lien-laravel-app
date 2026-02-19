<?php

namespace Database\Seeders;

use App\Models\Master\TaskAction;
use App\Models\ProjectTask;
use Illuminate\Database\Seeder;

class TaskActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $actions = ProjectTask::select('task_name')->groupBy('task_name')->pluck('task_name');
        if (!empty($actions)) {
            foreach ($actions as $name) {
                TaskAction::firstOrCreate(
                    ['name' => $name] // condition
                );
            }
        }
    }
}
