<div class="row">
  
<div class="col-xs-12 col-md-8 offset-md-2">
  <!-- <h4>Tasks</h4> -->
  <form action="" method="get">
  <div class="btn-group">
  <input type="text" id="sortedByInput" name="sortedByInput" value="<?=$safeSortTasks;?>" hidden>
  <button id="sortedByDisplay" class="btn btn-secondary btn-lg dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <?=$safeSortTasks?>
  </button>
  <div class="dropdown-menu">
    <?php 
            foreach (getTags(getDb()) as $tag) {
        ?>
          <button type="submit" class="dropdown-item btn btn-primary" href="#" onclick="changeTaskSortedByDisplay('<?=$tag['tag_name'];?>')">
          <?=$tag['tag_name'];?>
          </button>
        <?php 
          }
     ?>
          <button type="submit" class="dropdown-item btn btn-primary" href="#" onclick="changeTaskSortedByDisplay('All Tasks')">
          All Tasks
          </button>
  </div>
</div>
</form>


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

          foreach (getTasks(getDb(), $safeSortTasks) as $task) {

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