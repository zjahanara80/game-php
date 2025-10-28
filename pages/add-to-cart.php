<?php

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "game_db"; 

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$product_name = $_POST['product_name'];
$product_price = $_POST['product_price'];
$product_img = $_POST['product_img'];


$sql_check = "SELECT qnt FROM cart WHERE name = '$product_name'";
$result = $conn->query($sql_check);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $new_quantity = $row['qnt'] + 1; 
    $sql_update = "UPDATE cart SET qnt = $new_quantity WHERE name = '$product_name'";
    
    if ($conn->query($sql_update) === TRUE) {
        echo "تعداد محصول در سبد خرید افزایش یافت.";
    } else {
        echo "خطا در به‌روزرسانی تعداد: " . $conn->error;
    }
} else 
{
    $sql_insert = "INSERT INTO cart (name, price, qnt , img) VALUES ('$product_name', '$product_price', 1 , '$product_img')";

if ($conn->query($sql_insert) === TRUE) {
    echo "محصول به سبد خرید اضافه شد.";
} else {
    echo "خطا: " . $sql_insert . "<br>" . $conn->error;
}
}
$conn->close();
?>