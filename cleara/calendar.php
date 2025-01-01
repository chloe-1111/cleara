<!--container 5: cycle-->
<div class="container"  id="calendar-container">
  <h1 id="calendar"> Your Cycle </h1>
  <div class="row">

    <div id="heatmap-navigation" class="col-sm-12 col-md-12 mx-auto">    
      <button id="heatmap-previous" style="margin-bottom: 5px; width: 20px; height: 20px; padding: 0; font-size:0.8em;" class="btn btn-secondary">
        <
      </button>    
      <button id="heatmap-next" style="margin-bottom: 5px; width: 20px; height: 20px; padding: 0; font-size:0.8em;" class="btn btn-secondary">
        >
      </button>
    </div> 

    <div id="cal-heatmap" >  
    </div> 
  </div>    

  <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#editCalendarModal"> Add </button> <br>	
</div>

<!--popup: add visit form-->
<div class="modal fade" id="editCalendarModal" tabindex="-1" role="dialog" aria-labelledby="editCalendarModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editCalendarModalLabel"> Add a Cycle</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
      </div>
      <div class="modal-body">
        <form method="post" action="">
          <div class="form-group">
            <label for="startcycle">Start Date:</label>
              <input type="text" id="startcycle" name="startcycle" class="form-control" placeholder="dd-mm-yyyy"  pattern="[0-9]{2}-[0-9]{2}-[0-9]{4}" required>
            <label for="endcycle">End Date:</label>
              <input type="text" id="endcycle" name="endcycle" class="form-control" placeholder="dd-mm-yyyy" pattern="[0-9]{2}-[0-9]{2}-[0-9]{4}" required><br>
          
          <input type=submit class="btn btn-outline-secondary float-right"> 
          </div>
        </form> 
      </div>
    </div>
  </div>
</div>
 

<!--add new visit php-->
<?php
  if(isset($_POST['startcycle']) && isset($_POST['endcycle'])) {
    $patientid = $_GET['patientid'];
    $startcycle = $_POST['startcycle'];
    $endcycle = $_POST['endcycle'];
  

    // check if record already exists
    $check_query = "SELECT * FROM cycle WHERE patientid = :patientid AND startcycle = :startcycle AND endcycle = :endcycle";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bindParam(':patientid', $patientid);
    $check_stmt->bindParam(':startcycle', $startcycle);
    $check_stmt->bindParam(':endcycle', $endcycle);
    $check_stmt->execute();
    $result = $check_stmt->fetch();
    if ($result) {
      echo "<script>alert('Error: This cycle already exists')</script>";
      echo "<script>window.location.href='home.php?patientid=$patientid'</script>";
    } else {
        // insert new record
        $query = "INSERT INTO cycle (patientid, startcycle, endcycle) VALUES (:patientid, :startcycle, :endcycle)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':patientid', $patientid);
        $stmt->bindParam(':startcycle', $startcycle);
        $stmt->bindParam(':endcycle', $endcycle);
    
        if($stmt->execute()) {
          echo "<script>alert('Cycle added successfully!')</script>";
          echo "<script>window.location.href='home.php?patientid=$patientid'</script>";
        } else {
           //error
            echo "Error: " . implode(',',$stmt->errorInfo());
        }
    }
  }
?>
