<?php
class TaskController
{
    public function index()
    {
        $taskModel = new Task;
        $tasks = $taskModel->getAllTasks();

        require 'views/tasks.php';
    }

    public function show($id)
    {

        $taskModel = new Task;
        $task = $taskModel->getAllTasks();

        if(!is_numeric($id) || $id < 0){
            require 'views/404.php';
            return;
        }

        $tasks = [$task[$id]];
        require 'views/tasks.php';
    }
}
