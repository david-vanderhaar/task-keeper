
<?php

$statusDisplayMessage = null;
$statusAlertType = 'alert-success';

$totalHoursSpent = 0;

$safeSortTasksPrevious = null;

if (isset($_GET['task']) && ($_GET['task'] !== "") && isset($_GET['startTime']) && isset($_GET['endTime']) && isset($_GET['tagName'])){
    $safetask = htmlentities($_GET['task']);
    $safestartTime = htmlentities($_GET['startTime']);
    $safeendTime = htmlentities($_GET['endTime']);
    $safetagname = htmlentities($_GET['tagName']);
    $safeduration = htmlentities($_GET['duration']);
    // var_dump(isset($_GET['task']));
    addTask(getDb(), $safetask, $safestartTime, $safeendTime, $safetagname);
    
    $statusDisplayMessage = 'Task Added';
    $statusAlertType = 'alert-success';
  } else {
      $statusDisplayMessage = 'one or more fields are empty';
      $statusAlertType = 'alert-danger';
    }

  if (isset($_GET['newTag'])) {
    $safeNewTag = htmlentities($_GET['newTag']);
    addTag(getDb(), $safeNewTag);
  }

  if (isset($_GET['removeTaskId'])) {
    $safeId = htmlentities($_GET['removeTaskId']);
    removeTask(getDb(), $safeId);
  }

  if (isset($_GET['sortedByInput'])) {
      $safeSortTasks = htmlentities($_GET['sortedByInput']);
      $safeSortTasksPrevious = $safeSortTasks;
      // var_dump($safeSortTasks);
      // var_dump($safeSortTasksPrevious);
      $statusDisplayMessage = 'Tasks Sorted';
      $statusAlertType = 'alert-success';
  } else {
    $safeSortTasks = 'All Tasks';
  }

  function getDb () {
    $db = pg_connect(
      "host=localhost
       port=5432 
       dbname=timetracker_dev 
       user=timetrackeruser 
       password=password");
    return $db;
  }

  function addTask ($db, $task, $startTime, $endTime, $tag_name) {
    // print_r('task name: ' . $task . '<br/>');
    // print_r('tag name: ' . $tag_name . '<br/>');
    $tag_id = getTagId($db, $tag_name);
    $tag_id = $tag_id[0]["tag_id"];
    // print_r('tag id: ' . $tag_id);


    $taskDur = getTaskDur($db, $startTime, $endTime);

    if ($tag_name != null) { 
      $stmt = "insert into task (task_name, task_start, task_end, task_dur, tag_id) values ("
      . '\'' . $task . '\''
      . ', ' . '\'' . $startTime . '\''
      . ', ' . '\'' . $endTime . '\''
      . ', ' . '\'' . $taskDur[0]['?column?'] . '\''
      . ', ' . '\'' . $tag_id . '\''
      . ');';
  } else if ($tag_name == null) {
      $stmt = "insert into task (task_name, task_start, task_end, task_dur) values ("
      . '\'' . $task . '\''
      . ', ' . '\'' . $startTime . '\''
      . ', ' . '\'' . $endTime . '\''
      . ', ' . '\'' . $taskDur[0]['?column?'] . '\''
      . ');';
  }
    // print_r($stmt);
    $result = pg_query($stmt);

  }

  function addTag ($db, $tag_name) {
    global $statusDisplayMessage;
    global $statusAlertType;

    $stmt = "insert into tag (tag_name) values ("
    . '\'' . $tag_name . '\''
    .');';
    $result = pg_query($stmt);
    // var_dump(pg_query($stmt));
    if ($result === false) {
      $statusAlertType = 'alert-warning';
      $statusDisplayMessage = 'This Tag Already Exists';
    } else {
       $statusDisplayMessage = 'Tag Group Added';
    }
  }

  function getTagId ($db, $tag_name) {
    $request = pg_query(getDb(), "SELECT tag_id FROM tag WHERE tag_name=".'\''.$tag_name.'\''.";");
    // print_r("SELECT tag_id FROM tag WHERE tag_name=".'\''.$tag_name.'\''.";");
    // var_dump(pg_fetch_all($request));
    return pg_fetch_all($request);
  }


  function getTasksSortedByTag ($sortByTagId) {
    $request = pg_query(getDb(), "SELECT * FROM task WHERE tag_id=" . '\'' . $sortByTagId . '\'' . "order by task_start;");
    return pg_fetch_all($request);
  }

  function getTasks($db, $sortTasks) {
    global $safeSortTasks;

    $sortByTagId = pg_query(getDb(),"SELECT tag_id FROM tag WHERE tag_name=" . '\'' . $sortTasks . '\'' . ";");
    $sortByTagId = pg_fetch_all($sortByTagId)[0]["tag_id"];

    if ($sortTasks == "All Tasks") {
      $stmt = "SELECT * FROM task order by task_start;";
    } else {
      $stmt = "SELECT * FROM task WHERE tag_id=" . '\'' . $sortByTagId . '\'' . "order by task_start;";
    }

    $request = pg_query(getDb(), $stmt);
    return pg_fetch_all($request);
  }

  function getTagsForTasks($db, $task_tag_id) {
    $request = pg_query(getDb(),
      "SELECT tag_name FROM tag WHERE tag_id=" . '\'' . $task_tag_id . '\'' . ';');
    //print_r($task_tag_id);
    //print_r('tag name: ' . $tag_name);
    // var_dump(pg_fetch_all($request)[0]["tag_name"]);
    return pg_fetch_all($request);
  }

  function getTags($db) {
    $request = pg_query(getDb(), "SELECT * FROM tag order by tag_name;");
    return pg_fetch_all($request);
  }

  function getTaskDur($db, $startTime, $endTime){
    $request = pg_query(getDb(), 
      'SELECT (DATE_PART(' . '\'' . day . '\'' . ', '
       . '\'' . $endTime . '\'' . '::timestamp - ' 
       . '\'' . $startTime . '\'' . '::timestamp) * 24) + '
       . '(DATE_PART(' . '\'' . hour . '\'' . ', '
       . '\'' . $endTime . '\'' . '::timestamp - ' 
       . '\'' . $startTime . '\'' . '::timestamp) * 1) + '
       . '(DATE_PART(' . '\'' . minute . '\'' . ', '
       . '\'' . $endTime . '\'' . '::timestamp - ' 
       . '\'' . $startTime . '\'' . '::timestamp) / 60)'
       . ';'
     );

    return pg_fetch_all($request);
  }

  function removeTask ($db, $id) {
    $stmt = "delete from task where task_id=". $id;
    // var_dump($stmt);
    $result = pg_query($stmt);
  }

  

?>