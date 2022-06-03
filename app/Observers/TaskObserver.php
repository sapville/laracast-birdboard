<?php

namespace App\Observers;

use App\Models\Task;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     *
     * @param \App\Models\Task $task
     * @return void
     */
    public function created(Task $task)
    {
        $task->createActivity('created');
    }

    /**
     * Handle the Task "updated" event.
     *
     * @param \App\Models\Task $task
     * @return void
     */
    public function updated(Task $task)
    {
        if ($task->wasChanged('body'))
            $task->createActivity('updated');

        if ($task->wasChanged('completed'))
            $task->createActivity($task->completed ? 'completed' : 'uncompleted');
    }

    /**
     * Handle the Task "deleted" event.
     *
     * @param \App\Models\Task $task
     * @return void
     */
    public function deleted(Task $task)
    {
        $task->createActivity('deleted');
    }

}
