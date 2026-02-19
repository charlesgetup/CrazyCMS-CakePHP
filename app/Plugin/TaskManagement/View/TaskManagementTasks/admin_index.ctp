<?php 
    echo $this->element('tasks/task_list', array(
        'tasks'                 => $tasks,
        'taskType'              => $taskType,
        'webDevelopmentStageId' => $webDevelopmentStageId,
        'returnMyTickets'       => $returnMyTickets
    ), array(
        'plugin'                => 'TaskManagement',
    ));
?>