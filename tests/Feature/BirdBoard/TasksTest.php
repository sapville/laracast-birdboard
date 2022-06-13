<?php

namespace Tests\Feature\BirdBoard;

use App\Models\Project;
use App\Models\Task;
use Database\Seeders\ActivityTextSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class TasksTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_guest_cannot_manage_a_task()
    {
        list($project, $old_task , $new_task) = static::updateTask();
        Auth::logout();

        $this->post($project->path() . '/tasks', Task::factory()->raw())->assertRedirect('/login');
        $this->patch($old_task->path(), $new_task->getAttributes())->assertRedirect('/login');
    }

    public function test_a_task_can_be_created()
    {
        $project = static::createProject();
        $task = $project->tasks()->make(Task::factory()->raw());

        $this->post($project->path() . '/tasks', $task->getAttributes());

        $this->assertTrue($project->is($task->project));
        $this->assertCount(1, $project->tasks);
        $this->get($project->path())->assertSee($task->body);
    }

    public function test_a_task_must_have_a_body()
    {
        $project = static::createProject();
        $task = $project->tasks()->make(['body' => '']);

        $this->post($project->path() . '/tasks', $task->getAttributes())->assertSessionHasErrors('body');
    }

    public function test_only_the_owner_can_crate_a_task()
    {
        self::logIn();
        $project = Project::factory()->create();
        $task = Task::factory()->raw();

        self:self::logIn();
        $this->post($project->path() . '/tasks', $task)->assertStatus(403);

        $this->assertDatabaseMissing('tasks', $task);
    }

    public function test_a_user_can_update_a_task()
    {
        $this->withoutExceptionHandling();

        list($project, $old_task, $new_task) = static::updateTask();
        $new_task = $new_task->getAttributes();

        $this->patch($old_task->path(), $new_task)->assertRedirect($project->path());

        $this->assertDatabaseHas('tasks', $new_task);
        $this->get($project->path())->assertSee($new_task['body']);

    }

    public function test_an_updated_task_must_be_validated()
    {
        list(, $old_task, $new_task) = static::updateTask(new_task: Task::factory()->make(['body' => '']));

        $this->patch($old_task->path(), $new_task->getAttributes())->assertSessionHasErrors(['body'], null, $old_task->path());
    }

    public function test_only_an_owner_can_update_a_task()
    {
        list(, $old_task, $new_task) = static::updateTask();
        static::logIn();

        $this->patch($old_task->path(), $new_task->getAttributes())->assertStatus(403);

        $this->assertDatabaseMissing('tasks', $new_task->getAttributes());

    }

    public function test_task_can_be_completed_and_incompleted()
    {
        $this->withoutExceptionHandling();

        $task = static::createProject()->addTask(Task::factory()->make()->body);

        $this->assertFalse($task->fresh()->completed);
        $this->patch($task->path(), [
            'body' => $task->body,
            'completed' => true
        ]);
        $task->refresh();
        $this->assertTrue($task->completed);

        $this->patch($task->path(), [
            'body' => $task->body,
            'completed' => false
        ]);
        $this->assertFalse($task->fresh()->completed);

    }
}
