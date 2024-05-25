<?php


ini_set('max_execution_time', 300);

include 'db.php';


$conn = getDatabaseConnection();

$sql = "CREATE TABLE IF NOT EXISTS Histories (
    username VARCHAR(200) PRIMARY KEY,
    amount DOUBLE,
    country VARCHAR(200),
    active TINYINT(1),
    datetime DATETIME
)";

if ($conn->query($sql) === TRUE) {
    echo "Table Histories created successfully\n";
} else {
    echo "Error creating table: " . $conn->error;
}


$countries = ['Morocco', 'UAE', 'UK', 'Germany', 'France', 'Australia', 'India', 'China', 'Japan', 'Brazil'];
$batchSize = 1000; 
$totalRecords = 500000;
for ($i = 0; $i < $totalRecords; $i += $batchSize) {
    $values = [];

    for ($j = 0; $j < $batchSize; $j++) {
        $username = 'user ' . $i + $j;
        $amount = rand(100, 10000);
        $country = $countries[array_rand($countries)];
        if ( $i + $j < 25000){
            $active = 0;
        }else{
            $active = 1;
        }
        $date = date('Y-m-d H:i:s', rand(strtotime('2023-05-01'), strtotime('2024-05-01')));
        $values[] = "('$username', $amount, '$country', $active, '$date')";
    }

    $sql = "INSERT INTO Histories (username, amount, country, active, datetime) VALUES " . implode(',', $values);
    
    if ($conn->query($sql) !== TRUE) {
        echo "Error inserting data: " . $conn->error . "\n";
    } 
}

echo "Seed data inserted successfully\n";

$conn->close();
?>
