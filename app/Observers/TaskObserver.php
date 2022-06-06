<?php

namespace App\Observers;

use App\Models\Task;

class TaskObserver
{
    protected static array $old_values = [];

    public function created(Task $task)
    {
        $task->getAttribute('completed') ?: $task->setAttribute('completed', false);
        $task->createActivity('created', null, $task->getAttributes());
    }

    public function updating(Task $task)
    {
        static::$old_values = $task->getOriginal();
    }

    public function updated(Task $task)
    {
        $after = $task->getChanges();
        $before = array_intersect_key(self::$old_values, $after);

        if ($task->wasChanged('body'))
            $task->createActivity('updated', $before, $after);

        if ($task->wasChanged('completed'))
            $task->createActivity($task->completed ? 'completed' : 'uncompleted', $before, $after);
    }

    public function deleted(Task $task)
    {
        $task->createActivity('deleted', $task->getAttributes(), null);
    }

}
