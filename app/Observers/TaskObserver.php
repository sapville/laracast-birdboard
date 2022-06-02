<?php

namespace App\Observers;

use App\Models\Task;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     *
     * @param  \App\Models\Task  $task
     * @return void
     */
    public function created(Task $task)
    {
        $task->project->createActivity('task_created');
    }

    /**
     * Handle the Task "updated" event.
     *
     * @param  \App\Models\Task  $task
     * @return void
     */
    public function updated(Task $task)
    {
        if ($task->wasChanged('body'))
            $task->project->createActivity('task_updated');

        if ($task->wasChanged('completed'))
            $task->project->createActivity($task->completed ? 'task_completed' : 'task_uncompleted');
    }

    /**
     * Handle the Task "deleted" event.
     *
     * @param  \App\Models\Task  $task
     * @return void
     */
    public function deleted(Task $task)
    {
        $task->project->createActivity('task_deleted');
    }


}
