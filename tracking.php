<?php
// Prepare the SQL query to retrieve symptom options
$symptomOptionsQuery = $conn->prepare("SELECT symptomid, symptom FROM symptoms");
$symptomOptionsQuery->execute();
$symptomOptions = $symptomOptionsQuery->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission for adding a new entry
if (isset($_POST['addSymptomEntry'])) {
  $symptomid = $_POST['symptomid'];
  $severity = $_POST['severity'];
  $date = $_POST['date']; 
  try {
      $insertQuery = $conn->prepare("INSERT INTO symptomtrack (email, symptomid, date, symptomrate) VALUES (:email, :symptomid, :date, :severity)");
      $insertQuery->bindParam(':email', $email);
      $insertQuery->bindParam(':symptomid', $symptomid);
      $insertQuery->bindParam(':date', $date);
      $insertQuery->bindParam(':severity', $severity);
      $insertQuery->execute();

      echo "<script>
          document.addEventListener('DOMContentLoaded', function() {
              alert('Symptom entry added successfully!');
          });
      </script>";
  } catch (PDOException $e) {
      echo "<script>alert('Failed to add symptom entry: " . $e->getMessage() . "');</script>";
  }
}

// Prepare the SQL query to retrieve symptom data for the user
$query = $conn->prepare("
    SELECT st.date, s.symptom, st.symptomrate 
    FROM symptomtrack st
    INNER JOIN symptoms s ON st.symptomid = s.symptomid
    WHERE st.email = :email
    ORDER BY st.date ASC
");
$query->bindParam(':email', $email); 
$query->execute();

// Fetch the results 
$symptomData = $query->fetchAll(PDO::FETCH_ASSOC);

// Initialize an array to store unique dates
$uniqueDates = [];

// Initialize an associative array to store symptom data
$symptomDataBySymptom = [];

// Process each row of the fetched data
foreach ($symptomData as $row) {
    $date = $row['date'];
    $symptom = $row['symptom'];
    $symptomRate = $row['symptomrate'];

    // Track unique dates
    if (!in_array($date, $uniqueDates)) {
        $uniqueDates[] = $date;
    }

    // If the symptom hasn't been encountered yet, initialize an array for it
    if (!isset($symptomDataBySymptom[$symptom])) {
        $symptomDataBySymptom[$symptom] = [];
    }

    // Store the symptom rate indexed by date
    $symptomDataBySymptom[$symptom][$date] = $symptomRate;
}

// Convert data to JSON format for use in JavaScript
$uniqueDates_json = json_encode($uniqueDates);
$symptomDataBySymptom_json = json_encode($symptomDataBySymptom);
?>

 <!-- Symptom Tracking container-->
<div id="progress-container" class="container" >
    <h1 id="symptomprogress">Symptom Tracker</h1>

    <div class="row">
      <!-- Graph Section -->
      <div class="col-lg-9 col-md-12">
        <div class="container-custom">
          <canvas id="symptomChart" width="100%" height="50px"></canvas>
        </div>
      </div>

      <!-- Form Section -->
      <div class="col-lg-3 col-md-12">
        <div class="container-custom">
          <h5 class="card-title">Add Symptoms</h5>
          <form method="POST">
              <div class="form-group">
                  <label for="symptom">Symptom:</label>
                  <select id="symptom" name="symptomid" class="form-control" required>
                      <option value="" disabled selected>Select a symptom</option>
                      <?php foreach ($symptomOptions as $symptom): ?>
                          <option value="<?php echo $symptom['symptomid']; ?>">
                              <?php echo htmlspecialchars($symptom['symptom']); ?>
                          </option>
                      <?php endforeach; ?>
                  </select>
              </div>
              <div class="form-group">
                  <label for="severity">Severity (1-5):</label>
                  <input 
                      type="number" 
                      id="severity" 
                      name="severity" 
                      class="form-control" 
                      min="1" 
                      max="5" 
                      required>
              </div>
              <div class="form-group">
                  <label for="date">Date:</label>
                  <input 
                      type="date" 
                      id="date" 
                      name="date" 
                      class="form-control" 
                      value="<?php echo date('Y-m-d'); ?>" 
                      required>
              </div>
              <button type="submit" name="addSymptomEntry" class="btn btn-outline-primary btn-block">Add Entry</button>
          </form>
      </div>
  </div>

<br><br>

<script>
  // Retrieve the symptom data and unique dates from PHP
  var uniqueDates = <?php echo $uniqueDates_json; ?>;
  var symptomDataBySymptom = <?php echo $symptomDataBySymptom_json; ?>;

  // Define a color palette
  var colorPalette = [
    '#246473', '#FF6F61', '#FFD166', '#6A4C93', '#06D6A0',
    '#118AB2', '#EF476F', '#073B4C'
  ];

  // Helper function to assign colors
  function getColor(index) {
    return colorPalette[index % colorPalette.length];
  }

  // Prepare datasets for Chart.js
  var datasets = [];
  var ctx = document.getElementById('symptomChart').getContext('2d');
  var colorIndex = 0;

  for (var symptom in symptomDataBySymptom) {
    if (symptomDataBySymptom.hasOwnProperty(symptom)) {
      var symptomRates = [];
      uniqueDates.forEach(function (date) {
        symptomRates.push(symptomDataBySymptom[symptom][date] || null);
      });

      var lineColor = getColor(colorIndex++);
      datasets.push({
        label: symptom,
        data: symptomRates,
        borderColor: lineColor,
        backgroundColor: 'rgba(0, 0, 0, 0.1)',
        borderWidth: 2,
        fill: false,
        tension: 0.4
      });
    }
  }

  // Initialize Chart.js
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: uniqueDates,
      datasets: datasets
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: true, position: 'top' },
        title: {
          display: true,
          text: 'Symptom Progress Over Time',
          font: { size: 18, family: 'Arial' }
        }
      },
      scales: {
        x: { title: { display: true, text: 'Date', font: { size: 14 } } },
        y: {
          beginAtZero: true,
          title: { display: true, text: 'Symptom Severity (1-5)', font: { size: 14 } },
          ticks: { stepSize: 1, max: 5 }
        }
      }
    }
  });
</script>