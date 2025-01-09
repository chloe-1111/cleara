<?php
// Include database connection
include 'templates/conn.php';

// Fetch today's symptoms and rates from the symptomtrack table
$today = date('Y-m-d');
$query = "
    SELECT st.symptomid, st.symptomrate, s.symptom 
    FROM symptomtrack st
    JOIN symptoms s ON st.symptomid = s.symptomid
    WHERE st.date = :today
";
$stmt = $conn->prepare($query);
$stmt->bindParam(':today', $today);
$stmt->execute();
$symptoms = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch tips from the symptom_tips table
function getTips($symptomid, $severity_level, $conn) {
    $query = "SELECT tip FROM symptom_tips WHERE symptomid = :symptomid AND severity_level = :severity_level";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':symptomid', $symptomid);
    $stmt->bindParam(':severity_level', $severity_level);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// Determine severity level based on symptomrate
function getSeverityLevel($rate) {
    if ($rate >= 1 && $rate <= 2) {
        return 'simple';
    } elseif ($rate >= 3 && $rate <= 4) {
        return 'moderate';
    } elseif ($rate >= 5) {
        return 'professional';
    }
    return null;
}

// Generate tips for display
$displayTips = [];
foreach ($symptoms as $entry) {
    $symptomid = $entry['symptomid'];
    $rate = $entry['symptomrate'];
    $symptom = $entry['symptom'];

    $severity_level = getSeverityLevel($rate);
    if ($severity_level) {
        $tips = getTips($symptomid, $severity_level, $conn);
        $displayTips[$symptom] = $tips;
    }
}
?>

<div id="tips-container">
    <h1>Health Tips Based on Today's Symptoms</h1>
    <?php if (!empty($displayTips)) : ?>
        <table>
            <thead>
                <tr>
                    <th>Symptom</th>
                    <th>Tips</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($displayTips as $symptom => $tips) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($symptom); ?></td>
                        <td>
                            <ul>
                                <?php foreach ($tips as $tip) : ?>
                                    <li><?php echo htmlspecialchars($tip); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>No symptoms recorded for today. Add some to receive tips!</p>
    <?php endif; ?>
</div>
