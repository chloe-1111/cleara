<?php
// Prepare the SQL query to retrieve symptom options
$symptomOptionsQuery = $conn->prepare("SELECT symptomid, symptom FROM symptoms");
$symptomOptionsQuery->execute();
$symptomOptions = $symptomOptionsQuery->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission for adding a new entry
if (isset($_POST['addSymptomEntry'])) {
    $symptomid = $_POST['symptomid'];
    $severity = $_POST['severity'];
    $date = date('Y-m-d'); // Automatically set today's date

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
        $('#addSymptomModal').modal('hide');
    });
</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Failed to add symptom entry: " . $e->getMessage() . "');</script>";
    }
}

// Prepare the SQL query to retrieve symptom data for the patient
$query = $conn->prepare("
    SELECT st.date, s.symptom, st.symptomrate 
    FROM symptomtrack st
    INNER JOIN symptoms s ON st.symptomid = s.symptomid
    WHERE st.email = :email
    ORDER BY st.date ASC
");
$query->bindParam(':email', $email); // Assuming $email contains the patient's email
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

<div class="container" id="progress-container">
  <h1 id="symptomprogress">Symptom Tracker</h1>
  <canvas id="symptomChart" width="100%" height="50px"></canvas>
  <button type="button" class="btn btn-primary mt-3" data-toggle="modal" data-target="#addSymptomModal">Add New Entry</button>
</div>

<!-- Add Symptom Entry Modal -->
<div class="modal fade" id="addSymptomModal" tabindex="-1" role="dialog" aria-labelledby="addSymptomModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addSymptomModalLabel">Add New Symptom Entry</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
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
            <input type="number" id="severity" name="severity" class="form-control" min="1" max="5" required>
          </div>
          <button type="submit" name="addSymptomEntry" class="btn btn-outline-secondary">Add Entry</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
// Retrieve the symptom data and unique dates from PHP
var uniqueDates = <?php echo $uniqueDates_json; ?>;
var symptomDataBySymptom = <?php echo $symptomDataBySymptom_json; ?>;

console.log(symptomDataBySymptom); // Debugging: Inspect the data structure

// Prepare datasets for Chart.js
var datasets = [];

// Iterate through each symptom's data
for (var symptom in symptomDataBySymptom) {
    if (symptomDataBySymptom.hasOwnProperty(symptom)) {
        var symptomRates = [];

        // Populate symptomRates for all unique dates
        uniqueDates.forEach(function (date) {
            symptomRates.push(symptomDataBySymptom[symptom][date] || null); // Use null if no data for the date
        });

        // Add dataset for this symptom
        datasets.push({
            label: symptom,
            data: symptomRates,
            borderColor: getRandomColor(),
            backgroundColor: 'rgba(0, 0, 255, 0.1)',
            borderWidth: 1,
            fill: false,
            tension: 0.1
        });
    }
}

// Initialize Chart.js line chart
var ctx = document.getElementById('symptomChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: uniqueDates, // Dates as x-axis labels
        datasets: datasets // Symptom datasets
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true
            },
            title: {
                display: true,
                text: 'Symptom Progress Over Time'
            }
        },
        scales: {
            x: {
                title: {
                    display: true,
                    text: 'Date'
                }
            },
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Symptom Severity'
                }
            }
        }
    }
});

// Function to generate a random color
function getRandomColor() {
    var letters = '0123456789ABCDEF';
    var color = '#';
    for (var i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}
</script>
