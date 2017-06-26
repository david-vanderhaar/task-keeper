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