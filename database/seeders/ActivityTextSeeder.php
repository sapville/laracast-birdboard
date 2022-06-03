<?php

namespace Database\Seeders;

use App\Models\ActivityText;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivityTextSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ActivityText::create([
            'description' => 'project_created',
            'text' => 'You created a project'
        ]);
        ActivityText::create([
            'description' => 'project_updated',
            'text' => 'You updated the project'
        ]);
        ActivityText::create([
            'description' => 'task_created',
            'text' => 'You updated a task'
        ]);
        ActivityText::create([
            'description' => 'task_updated',
            'text' => 'You updated the task'
        ]);
        ActivityText::create([
            'description' => 'task_deleted',
            'text' => 'You deleted the task'
        ]);
        ActivityText::create([
            'description' => 'task_completed',
            'text' => 'You completed the task'
        ]);
        ActivityText::create([
            'description' => 'task_uncompleted',
            'text' => 'You uncompleted the task'
        ]);
    }
}
