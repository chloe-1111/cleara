<!--container 1: patient information -->
    <!-- Template for birthdate input-->
    <script>
        document.getElementById("birthdate").oninvalid = function (e) {
        e.target.setCustomValidity("Birthdate must be in the format 'dd-mm-yyyy'");
    };
    document.getElementById("birthdate").oninput = function (e) {
        e.target.setCustomValidity("");
    };
    </script>  


<!--container 4: medicine--> 
    <!--medicine graph -->
    <script>
    var patientmedicine = <?php echo $patientmedicine_json; ?>;
    var dataByDate = {};
    var medicationLabels = [];
    var medicationColors =  ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 206, 86, 0.2)', 'rgba(76, 175, 80, 0.2)', 'rgba(153, 102, 255, 0.2)', 'rgba(183, 28, 28, 0.2)','rgba(255, 140, 0, 0.2)', ];
    var threeWeeksAgo = new Date();

    //only show medicine from last 3 weeks
    threeWeeksAgo.setDate(threeWeeksAgo.getDate() - 21);
    patientmedicine = patientmedicine.filter(med => {
        var medDate = new Date(med.date);
        return medDate > threeWeeksAgo;
    });

    // Looping through each patient medication and aggregating data by date and medication
    patientmedicine.forEach(patientmedicine => {
        var date = patientmedicine.date;
        var medication = patientmedicine.medication;
        var dosage = patientmedicine.dosage;
        if (!dataByDate[date]) {
            dataByDate[date] = {};
        }
        if (!dataByDate[date][medication]) {
            dataByDate[date][medication] = 0;
        }
        dataByDate[date][medication] += dosage;
        if(medicationLabels.indexOf(medication) == -1) {
                medicationLabels.push(medication);
            }
        });

        // Prepare the labels and datasets for use in a chart.
        var labels = Object.keys(dataByDate);
        var datasets = [];
        medicationLabels.forEach((medication, index) => {
            var data = [];
            labels.forEach(date => {
                data.push(dataByDate[date][medication] || 0);
            });
            datasets.push({
                label: medication,
                data: data,
                backgroundColor: medicationColors[index % medicationColors.length],
                borderColor: medicationColors[index % medicationColors.length],
                borderWidth: 1
            });
        });

        // Create the chart
        const canvas = document.getElementById('myChart');
        const ctx = canvas.getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <!-- template for date input-->
    <script>
        document.getElementById("date").oninvalid = function (e) {
        e.target.setCustomValidity("Date must be in the format 'dd-mm-yyyy'");
    };
    document.getElementById("date").oninput = function (e) {
        e.target.setCustomValidity("");
    };
    </script>  


<!--Container 5: Visit -->
    <!--JS Heatmap -->
    <script type="text/javascript">
    var patientvisit = <?php echo $data_json; ?>;
    var windowWidth = window.innerWidth;
    var cellSize = 25;
    var cal = new CalHeatMap();
    var today = new Date();
    var start = new Date(today.getFullYear(), today.getMonth() - 2, 1);
    var end = new Date(today.getFullYear(), today.getMonth() + 2, 31);

    function setCellSize() {
        windowWidth = window.innerWidth;
        if (windowWidth < 768) {
        cellSize = 20;
        } else if (windowWidth >= 768 && windowWidth < 992) {
        cellSize = 30;
        } else {
        cellSize = 45;
        }

        cal.init({
        domain: "month",
        subDomain: "day",
        cellSize: cellSize,
        data: patientvisit,
        subDomainTextFormat: "%d",
        range: 3,
        displayLegend: false,
        domainMargin: 20,
        animationDuration: 800,
        domainDynamicDimension: false,
        start: start,
        end: end,
        scale: [10, 20, 80],
        previousSelector: "#heatmap-previous",
        nextSelector: "#heatmap-next",
        subDomainTitleFormat: ""
        });
    }

    setCellSize();
    window.onresize = function() {
        setCellSize();
    }

    </script>

    <!-- Template for checkin date input-->
    <script>
        document.getElementById("startcycle").oninvalid = function (e) {
        e.target.setCustomValidity("Date must be in the format 'dd-mm-yyyy'");
    };
    document.getElementById("startcycle").oninput = function (e) {
        e.target.setCustomValidity("");
    };
    </script>  

    <!-- Template for checkout date input-->
    <script>
        document.getElementById("endcycle").oninvalid = function (e) {
        e.target.setCustomValidity("Date must be in the format 'dd-mm-yyyy'");
    };
    document.getElementById("endcycle").oninput = function (e) {
        e.target.setCustomValidity("");
    };
    </script>  


</body>
</html>