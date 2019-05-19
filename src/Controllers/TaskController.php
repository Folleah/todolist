<?php declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\View;
use App\Models\Task;

class TaskController
{
    /**
     * Get all tasks
     *
     * @param Request $request
     */
    public function tasks (Request $request) {
        $filter = isset($request->query()['filter']) ? $request->query()['filter'] : '';

        switch ($filter) {
            case 'username':
                $currentPageTasks = Task::sort('asc', 'username', (new Task)->get());
                break;
            case 'email':
                $currentPageTasks = Task::sort('asc', 'email', (new Task)->get());
                break;
            case 'status':
                $currentPageTasks = Task::sort('asc', 'status', (new Task)->get());
                break;
            default:
                $currentPageTasks = Task::sort('asc', 'id', (new Task)->get());
        }

        (new View('tasks'))->render([
            'currentPageTasks' => $currentPageTasks
        ]);
    }

    /**
     * Create task
     *
     * @param Request $request
     */
    public function createTask(Request $request)
    {
        $query = $request->query();
        $isQueryValid = isset(
            $query['username'],
            $query['email'],
            $query['text']
        );

        if (!$isQueryValid) {
            throw new \Exception("Invalid request.");
        }

        $task = new Task;
        $task->username = $query['username'];
        $task->email    = $query['email'];
        $task->text     = $query['text'];
        $task->save();

        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}