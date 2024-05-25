<?php
if (!isset($_SESSION["username"]) || !$_SESSION["username"]) {
    header("Location: index.php");
    exit;
}

include 'db.php';


$conn = getDatabaseConnection();


$date_from = '2023-05-01';
$date_to = '2024-05-01';
if(isset($_POST['Filter'])) {
    $date_from = isset($_POST['date_from']) ? $_POST['date_from'] : date('Y-m-01');
    $date_to = isset($_POST['date_to']) ? $_POST['date_to'] : date('Y-m-t');
}


$sql = "SELECT country, SUM(amount) AS total_amount 
        FROM Histories 
        WHERE active = 1 AND datetime BETWEEN '".$date_from."' AND '".$date_to."'
        GROUP BY country" ;
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>
<html>
    <head>
        <title>dashboard</title>
        <style>
            form {
                margin-bottom: 50px;
            }
            table {
                margin-bottom: 50px;
            }
        </style>
    </head>
    <body>
        <form action="" method='POST'>
            Date From: <input type='date' name='date_from' value='$date_from'>
            Date To: <input type='date' name='date_to' value='$date_to'> 
            <input type='submit' name='Filter'>
        </form>
        <table border='1' width='20%'>
            <tr>
                <th height='40px' >Country</th>
                <th>Total Amount</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) {  ?>
            <tr>
                <td height='30px' ><?php echo $row['country'];  ?></td>
                <td height='30px'><?php echo $row['total_amount'];  ?></td>
            </tr>
            <?php } ?>
        </table>
    </body>

</html>

<?php
$stmt->close();
$conn->close();
?>
