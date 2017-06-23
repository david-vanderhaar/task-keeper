<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Time Card</title>
  <!-- Latest compiled and minified CSS & JS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
  <!-- <link rel="stylesheet" href="styles.css"> -->
</head>
<body>

<?php

if (isset($_GET['task']) && isset($_GET['startTime']) && isset($_GET['endTime'])){
    $safetask = htmlentities($_GET['task']);
    $safestartTime = htmlentities($_GET['startTime']);
    $safeendTime = htmlentities($_GET['endTime']);
    $safeduration = htmlentities($_GET['duration']);
    addTask(getDb(), $safetask, $safestartTime, $safeendTime);
    
  } else {
    var_dump('one or more fields are empty');
  }

  if (isset($_GET['tag'])) {
    $safetag = htmlentities($_GET['tag']);
    addTag(getDb(), $safetag);
  }

  if (isset($_GET['removeTaskId'])) {
    $safeId = htmlentities($_GET['removeTaskId']);
    removeTask(getDb(), $safeId);
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

  function addTask ($db, $task, $startTime, $endTime) {
   
    $taskDur = getTaskDur($db, $startTime, $endTime);
   // var_dump('dur: '. $taskDur[0] . ' ');
    var_dump($taskDur);
    print_r($taskDur[0]['?column?']);

    $stmt = "insert into task (task_name, task_start, task_end, task_dur) values ("
    . '\'' . $task . '\''
    . ', ' . '\'' . $startTime . '\''
    . ', ' . '\'' . $endTime . '\''
    . ', ' . '\'' . $taskDur[0]['?column?'] . '\''
    . ');';
    //var_dump($stmt);
    $result = pg_query($stmt);
  }

  function getTasks($db) {
    $request = pg_query(getDb(), "SELECT * FROM task order by task_start;");
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

  function addTag ($db, $tag) {

  }

  function addTagToTask ($db, $tag, $task) {

  }

  function removeTask ($db, $id) {
    $stmt = "delete from task where id=". $id;
    var_dump($stmt);
    $result = pg_query($stmt);
  }
  

?>

<div class="container-fluid">
<div class="row">
<div class="col-xs-0 col-md-1">
  
</div>
<div class="col-xs-12 col-md-10">
<form class="form center" method="GET">

  <label class="sr-only" for="task">Task</label>
  <input type="text" class="form-control mb-2 mr-sm-2 mb-sm-0" id="task" name="task"  placeholder="Task">

    <label class="sr-only" for="tag">Tag</label>
    <input type="text" class="form-control" id="tag" name="tag" placeholder="Tag">

  <div class="input-group mb-2 mr-sm-2 mb-sm-0">
  <p class="input-group-addon" id="startAddon">Start</p>
  <label class="sr-only" for="startTime">Start Time</label>
  <input type="datetime-local" class="form-control mb-2 mr-sm-2 mb-sm-0" id="startTime" name="startTime">
  </div>

  <div class="input-group mb-2 mr-sm-2 mb-sm-0">
  <p class="input-group-addon" id="endAddon">End</p>
  <label class="sr-only" for="endTime">End Time</label>
  <input type="datetime-local" class="form-control mb-2 mr-sm-2 mb-sm-0" id="endTime" name="endTime">
  </div>

  <label class="sr-only" for="duration">Duration</label>
  <input type="text" class="form-control mb-2 mr-sm-2 mb-sm-0" id="duration" name="duration" placeholder="Time Spent" disabled>

  <button type="submit" class="btn btn-primary">Submit</button>
</form>
</div>
<div class="col-xs-0 col-md-1">
  
</div> 
</div>

<div class="row">
  
<div class="col">
  <h4>Tasks</h4>


<table class="table table-hover">
  <thead>
    <tr>
      <th>#</th>
      <th>Date</th>
      <th>Task</th>
      <th>Tag</th>
      <th>Time Spent</th>
    </tr>
  </thead>
  <tbody>
  <?php 

          foreach (getTasks(getDb()) as $task) {
            
        ?>
    <tr>
      <th scope="row">
      <form action="" method="get">
        <input name="removeTaskId" value="<?=$task['id'];?>">
      <button type="submit" class="close btn btn-primary" aria-label="Remove">X</button>
      </form>
      </th>
        <td><?=$task['task_start']?></td>
        <td><?=$task['task_name']?></td>
        <td>Blank</td>
        <td><?=$task['task_dur']?> hrs</td>
    </tr>
    <?php 
          }
    ?>
  </tbody>
</table>


</div>

</div>

</div> 
</body>
</html>