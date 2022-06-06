<?php

namespace App\Observers;

use App\Models\Task;
use App\Traits\RegisterActivity;

class TaskObserver
{
    use RegisterActivity;

    public function updated(Task $task)
    {
        if ($task->wasChanged('body'))
            $task->createUpdatedActivity($task);

        if ($task->wasChanged('completed'))
            $task->createUpdatedActivity($task,$task->completed ? 'completed' : 'uncompleted');
    }

    public function created(Task $task)
    {
        $task->getAttribute('completed') ?: $task->setAttribute('completed', false);
        $task->createActivity('created', null, $task->getAttributes());
    }

    public function deleted(Task $task)
    {
        $task->createActivity('deleted', $task->getAttributes(), null);
    }

}
