<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Time Card</title>
  <!-- Latest compiled and minified CSS & JS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
  <link rel="stylesheet" href="./assets/font-awesome-4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container-fluid">
<div class="col-xs-12 col-md-8 offset-md-2 mainTitle">
<span>
<i class="fa fa-circle track-up" aria-hidden="true"></i>
<i class="fa fa-circle track-down" aria-hidden="true"></i>
<i class="fa fa-circle track-up" aria-hidden="true"></i>
<i class="fa fa-circle track-down" aria-hidden="true"></i>
<i class="fa fa-dot-circle-o track-final track-up" aria-hidden="true"></i>
<h1>Task Tracks</h1>
</span>
<h5>A PHP/PostgresQL Time Keeper</h5>
</div>

<?php 
  include './time-tracker-db.php';
  include './components/new-task-form.php';
  include './components/task-display.php';
?>

</div> 
</body>
<script src="./main.js"></script>
</html>