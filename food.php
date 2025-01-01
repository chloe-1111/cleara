<?php
if(isset($_GET['patientid'])) {
    $patientid = $_GET['patientid'];
} else {

    header("Location: login.php");
    exit(); 
}
?>

<!--container: food-->
<div class="container"  id="food-container">
  <h1 id="food"> Food Recommendations for You </h1>
  <table class="table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Quantity (grams)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $pcos_foods = ['almonds', 'spinach', 'chickpeas', 'avocado', 'berries', 'salmon', 'broccoli', 'flaxseeds', 'pumpkin seeds', 'kale'];

            function getRandomQuantity() {
                return rand(50, 200) . 'g'; 
            }

            $food_recommendations = [];

            $csvFile = 'C:\xampp\htdocs\PCOScompanion\PCOS_AI\data.csv';
            $file = fopen($csvFile, 'r');

            $headers = fgetcsv($file, 0, ';');

            while (($row = fgetcsv($file, 0, ';')) !== FALSE) {
                $foods = array_slice($row, 9);

                foreach ($foods as $food) {
                    if (!empty($food)) {
                        $food_parts = explode('_', $food);
                        $item = $food_parts[0];

                        if (in_array($item, $pcos_foods) && !array_key_exists($item, $food_recommendations)) {
                            $food_recommendations[$item] = [
                                'item' => $item,
                                'quantity' => getRandomQuantity()
                            ];
                        }
                    }
                }
            }

            fclose($file);

            $food_recommendations = array_values($food_recommendations);
            shuffle($food_recommendations);
            
            $recommendations = array_slice($food_recommendations, 0, 3);
       
            foreach ($recommendations as $recommendation) {
                $item = $recommendation['item'];
                $quantity = $recommendation['quantity'];

                echo '<tr>';
                echo '<td>' . $item . '</td>';
                echo '<td>' . $quantity . '</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
  <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#editDailyModal"> Add Today's Symptoms </button> <br>	<br>
  <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#editFoodModal">Add Today's Food Consumption</button><br>

</div>


<!--popup: edit list-->
<div class="modal fade" id="editDailyModal" tabindex="-1" role="dialog" aria-labelledby="editDailyModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editDailyModalLabel">Edit Todays Symptoms</h5> <br>
        
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
      </div>
      <div class="modal-body">
      <form action="home.php?patientid=<?php echo $patientid; ?>" method="POST">
<!-- Form Field for Patient ID -->
<input type="hidden" name="patientid" value="<?php echo isset($patientid) ? $patientid : ''; ?>">

<!-- Debugging statement to check the value of $symptoms -->
<?php
// Fetch symptoms data from the database
$sql = "SELECT * FROM symptoms";
$stmt = $conn->prepare($sql);
$stmt->execute();
$symptoms = $stmt->fetchAll();

// Check if $symptoms is not empty before proceeding
if (!empty($symptoms)) {
    ?>
    <!-- Form Field for Patient ID -->
    <input type="hidden" name="patientid" value="<?php echo isset($patientid) ? $patientid : ''; ?>">

    <!-- Form Fields for Symptom IDs and Rates -->
    <?php foreach ($symptoms as $symptom): ?>
        <?php $symptomid = isset($symptom['symptomid']) ? $symptom['symptomid'] : ''; ?>
        <div class="symptom-row">
            <div class="symptom-name">
                <strong><?php echo $symptom['symptom']; ?></strong>
            </div>
            <div class="symptom-rating">
                <label for="symptomrates[<?php echo $symptomid; ?>]"></label>
                <input type="range" id="symptomrates[<?php echo $symptomid; ?>]" name="symptomrates[<?php echo $symptomid; ?>]" min="1" max="5" value="3">
                <!-- Hidden input field for symptom ID -->
                <input type="hidden" name="symptomids[]" value="<?php echo $symptomid; ?>">
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Submit Button -->
    <input type="submit" name="updatesymptom" value="Update Symptom" class="btn btn-outline-secondary float-right">
<?php
} else {
    echo "No symptoms found.";
}
?>

</form>

      </div>
    </div>
  </div>
</div>


<!--edit list-->
<?php
if (isset($_POST['updatesymptom'])) {
    // Debugging: Inspect the $_POST array
    var_dump($_POST); // Output the entire $_POST array to see its contents

    try {
        $patientid = isset($_POST['patientid']) ? $_POST['patientid'] : null;
        $symptomids = isset($_POST['symptomids']) ? $_POST['symptomids'] : [];
        $symptomrates = isset($_POST['symptomrates']) ? $_POST['symptomrates'] : [];

        if (!empty($patientid) && !empty($symptomids)) {
            $currentDate = date('d-m-Y');
            $sql = "INSERT INTO patientsymptom (patientid, symptomid, date, symptomrate) VALUES (:patientid, :symptomid, :date, :symptomrate)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':patientid', $patientid);
            $stmt->bindParam(':date', $currentDate);

            foreach ($symptomids as $symptomid) {
                if (!empty($symptomid)) {
                    // Ensure corresponding symptom rate exists
                    $symptomrate = isset($symptomrates[$symptomid]) ? $symptomrates[$symptomid] : null;
                    if ($symptomrate !== null) {
                        $stmt->bindParam(':symptomid', $symptomid);
                        $stmt->bindParam(':symptomrate', $symptomrate);
                        $stmt->execute();
                    } else {
                        // Handle missing symptom rate
                        // This could involve skipping insertion or displaying an error message
                        echo "Symptom rate not provided for symptomid: $symptomid";
                    }
                }
            }
            
            // Redirect to home page after successful insertion
            echo "<script>alert('Patient symptom list updated successfully!')</script>";
            echo "<script>window.location.href='home.php?patientid=$patientid'</script>";
        } else {
            echo "Patient ID or symptom IDs not provided";
        }
    } catch (PDOException $e) {
        echo "Database Error: " . $e->getMessage();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>




<!--popup: edit food consumption-->
<div class="modal fade" id="editFoodModal" tabindex="-1" role="dialog" aria-labelledby="editFoodModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editFoodModalLabel">Edit Today's Food Consumption</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="home.php?patientid=<?php echo $patientid; ?>" method="POST">
          <input type="hidden" name="patientid" value="<?php echo $patientid; ?>">
          <?php
            // Get all food items
            $sql = "SELECT * FROM food";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $foods = $stmt->fetchAll();
            // Get patient's consumed food items
            $sql = "SELECT * FROM patientfood WHERE patientid = :patientid";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':patientid', $patientid);
            $stmt->execute();
            $patient_foods = $stmt->fetchAll();
            // Create an array to store patient's consumed food item IDs
            $patient_food_ids = array();
            foreach($patient_foods as $patient_food) {
              $patient_food_ids[] = $patient_food['foodid'];
            }
            // Create a checklist with all food items and check the ones that the patient has consumed
            foreach($foods as $food) {
              $checked = in_array($food['foodid'], $patient_food_ids) ? "checked" : "";
              echo "<input type='checkbox' name='foodids[]' value='" . $food['foodid'] . "' " . $checked . ">" . $food['food'];
              echo "<br>";
            }
          ?>
          <input type="submit" name="updatefood" value="Update Food Consumption" class="btn btn-outline-secondary float-right">
        </form>
      </div>
    </div>
  </div>
</div>

<!--edit food consumption-->
<?php
  if (isset($_POST['updatefood'])) {
    try {
      $patientid = $_POST['patientid'];
      $foodids = isset($_POST['foodids']) ? $_POST['foodids'] : [];

      if (!empty($foodids)) {
        $currentDate = date('d-m-Y'); 
        $sql = "INSERT INTO patientfood (patientid, foodid, date) VALUES (:patientid, :foodid, :date)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':patientid', $patientid);
        $stmt->bindValue(':date', $currentDate);

        foreach ($foodids as $foodid) {
          if (!empty($foodid)) {

            $stmt->bindValue(':foodid', $foodid);
            $stmt->execute();
          }
        }
      }

      // Redirect to home page after successful insertion
      echo "<script>alert('Patient food consumption list updated successfully!')</script>";
      echo "<script>window.location.href='home.php?patientid=$patientid'</script>";
    } catch (PDOException $e) {
      // Handle database errors
      echo "Database Error: " . $e->getMessage();
    } catch (Exception $e) {
      // Handle other errors
      echo "Error: " . $e->getMessage();
    }
  }
?>

