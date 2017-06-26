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

if (isset($_GET['task']) && isset($_GET['startTime']) && isset($_GET['endTime']) && isset($_GET['tagName'])){
    $safetask = htmlentities($_GET['task']);
    $safestartTime = htmlentities($_GET['startTime']);
    $safeendTime = htmlentities($_GET['endTime']);
    $safetagname = htmlentities($_GET['tagName']);
    $safeduration = htmlentities($_GET['duration']);
    addTask(getDb(), $safetask, $safestartTime, $safeendTime, $safetagname);
    
  } else {
    // print_r('tag field: ' . $_GET['tag'] . ' ');
    print_r('one or more fields are empty<br/>');
    print_r('tag_name field is set: ');
    var_dump(isset($_GET['tagName']));
  }

  if (isset($_GET['newTag'])) {
    $safetag = htmlentities($_GET['newTag']);
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

  function addTask ($db, $task, $startTime, $endTime, $tag_name) {
    print_r('tag name: ' . $tag_name . '<br/>');
    $tag_id = getTagId($db, $tag_name);
    $tag_id = $tag_id[0]["tag_id"];
    print_r('tag id: ' . $tag_id);

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
    print_r($stmt);
    $result = pg_query($stmt);

  }

  function addTag ($db, $tag_name) {
    $stmt = "insert into tag (tag_name) values ("
    . '\'' . $tag_name . '\''
    .');';
    $result = pg_query($stmt);
  }

  function getTagId ($db, $tag_name) {
    $request = pg_query(getDb(), "SELECT tag_id FROM tag WHERE tag_name=".'\''.$tag_name.'\''.";");
    // print_r("SELECT tag_id FROM tag WHERE tag_name=".'\''.$tag_name.'\''.";");
    var_dump(pg_fetch_all($request));
    return pg_fetch_all($request);
  }

  function addTagToTask ($db, $tag, $task) {

  }

  function getTasks($db) {
    $request = pg_query(getDb(), "SELECT * FROM task order by task_start;");
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
    var_dump($stmt);
    $result = pg_query($stmt);
  }

  

?>

<div class="container-fluid">
<div class="row">
<div class="col-xs-12 col-md-6 offset-md-3">
<form class="form center" method="GET">

  <label class="sr-only" for="task">Task</label>
  <input type="text" class="form-control mb-2 mr-sm-2 mb-sm-0" id="task" name="task"  placeholder="Task">

    <label class="sr-only" for="tag">Tag</label>
    <div class="input-group">
      <div class="input-group-btn">
        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Select or Add Tag
        </button>
        <div class="dropdown-menu">
        <?php 
            foreach (getTags(getDb()) as $tag) {
        ?>
          <a class="dropdown-item" href="#" onclick="changeTagDisplay('<?=$tag['tag_name'];?>')">
          <?=$tag['tag_name'];?>
          </a>
        <?php 
          }
        ?>
          <div role="separator" class="dropdown-divider"></div>
          <a class="dropdown-item" data-toggle="modal" data-target="#addTagModal">Add New Tag</a>
        </div>
      </div>
      <label class="sr-only" for="tagName">Selected Tag</label>
      <input type="text" id="tagDisplayHidden" name="tagName" placeholder="Selected Tag" value="" class="form-control" aria-label="Text input with dropdown button" hidden>
      <input type="text" id="tagDisplay" placeholder="Selected Tag" value="" class="form-control" aria-label="Text input with dropdown button" disabled>
      <div class="input-group-btn">
      <!-- <button type="button" class="btn btn-secondary" aria-haspopup="true" aria-expanded="false">+</button> -->
        </div>
    </div>

   <?php
   {
      $currentDate = date("Y-m-d\TH:m");
   
   ?> 

  <div class="input-group mb-2 mr-sm-2 mb-sm-0">
  <p class="input-group-addon" id="startAddon">Start</p>
  <label class="sr-only" for="startTime">Start Time</label>
  <input type="datetime-local" value="<?=$currentDate?>" class="form-control mb-2 mr-sm-2 mb-sm-0" id="startTime" name="startTime">
  </div>

  <div class="input-group mb-2 mr-sm-2 mb-sm-0">
  <p class="input-group-addon" id="endAddon">End</p>
  <label class="sr-only" for="endTime">End Time</label>
  <input type="datetime-local" value="<?=$currentDate?>" class="form-control mb-2 mr-sm-2 mb-sm-0" id="endTime" name="endTime">
  </div>
<?php
  }
?>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
</div>
</div>

<!-- Add Tag Modal -->
<form method="GET">
  <div class="modal fade" id="addTagModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="text" id="newTagInput" name="newTag" placeholder="Add a new tag" value="" class="form-control" aria-label="Text input to add new tag">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save New Tag</button>
      </div>
    </div>
  </div>
</div>
</form>

<div class="row">
  
<div class="col-xs-12 col-md-8 offset-md-2">
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

            $tag_name = getTagsForTasks(getDb(), $task['tag_id']);

            
        ?>
    <tr>
      <th scope="row">
      <form action="" method="get">
        <input name="removeTaskId" value="<?=$task['task_id'];?>" hidden>
      <button type="submit" class="close btn btn-primary" aria-label="Remove">X</button>
      </form>
      </th>
        <td><?=$task['task_start']?></td>
        <td><?=$task['task_name']?></td>
        <!-- <td><?=$task['tag_id']?></td> -->
        <td><?=$tag_name[0]["tag_name"]?></td>
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
<script src="./app/main.js"></script>
</html>