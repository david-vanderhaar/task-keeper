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


  <div class="card">
  <button type="submit" class="btn btn-primary">Submit</button>
  </div>
  <div class="alert <?=$statusAlertType;?>" id="statusAlert" name="statusDisplay" role="alert">
  <!-- <strong>Finished!</strong> You successfully created a new <strong>tag group</strong>. -->
  <p id="statusMessage"><?=$statusDisplayMessage;?></p>
  </div>
  
</form>

</div>
</div>

<!-- Add Tag Modal -->
<form method="GET">
  <div class="modal fade" id="addTagModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add New Tag Group</h5>
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