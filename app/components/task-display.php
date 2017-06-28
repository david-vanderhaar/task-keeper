<div class="row">
  
<div class="col-xs-12 col-md-8 offset-md-2">
<div class="col-xs-12 col-xl-6 offset-xl-3">
  <form action="" method="get">
  <div class="btn-group">
  <input type="text" id="sortedByInput" name="sortedByInput" value="<?=$safeSortTasks;?>" hidden>
  <button id="sortedByDisplay" class="btn btn-secondary btn-lg dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <?=$safeSortTasks?>
  </button>

<?php 
          // $totalHoursSpent = 0;

          foreach (getTasks(getDb(), $safeSortTasks) as $task) {

            // var_dump(intval($task['task_dur']));
            $totalHoursSpent += intval($task['task_dur']);
          
          }

?>

  <div class="input-group">
    <input type="text" class="form-control" placeholder="Hours Spent" aria-describedby="showin hours spent on tag group" value="<?=$totalHoursSpent;?>" disabled>
    <span class="input-group-addon" id="basic-addon2">Hours Spent</span>
    </div>
  <div class="dropdown-menu">

          <button type="submit" class="dropdown-item btn btn-primary" href="#" onclick="changeTaskSortedByDisplay('All Tasks')">
          All Tasks
          </button>

    <?php 
            foreach (getTags(getDb()) as $tag) {
        ?>
          <button type="submit" class="dropdown-item btn btn-primary" href="#" onclick="changeTaskSortedByDisplay('<?=$tag['tag_name'];?>')">
          <?=$tag['tag_name'];?>
          </button>
        <?php 
          }
     ?>
          
  </div>
</div>
</form>
</div>

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
          // $totalHoursSpent = 0;

          foreach (getTasks(getDb(), $safeSortTasks) as $task) {

            $tag_name = getTagsForTasks(getDb(), $task['tag_id']);

            
        ?>
    <tr>
      <th scope="row">
      <form action="" method="get">
        <input name="removeTaskId" value="<?=$task['task_id'];?>" hidden>
      <button type="submit" class="close btn btn-danger" aria-label="Remove"><i class="fa fa-trash" aria-hidden="true"></i>
</button>
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