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

// دریافت اطلاعات محصول
$product_name = $_POST['product_name'];
$product_price = $_POST['product_price'];
$product_img = $_POST['product_img'];


//ایا محصول داخل بانک قبلا بوده یا نه؟
$sql_check = "SELECT qnt FROM cart WHERE name = '$product_name'";
$result = $conn->query($sql_check);

if ($result->num_rows > 0) {
    // محصول قبلاً وجود دارد، تعداد آن را افزایش می‌دهیم
    $row = $result->fetch_assoc();
    $new_quantity = $row['qnt'] + 1; // افزایش تعداد به یک
    $sql_update = "UPDATE cart SET qnt = $new_quantity WHERE name = '$product_name'";
    
    if ($conn->query($sql_update) === TRUE) {
        echo "تعداد محصول در سبد خرید افزایش یافت.";
    } else {
        echo "خطا در به‌روزرسانی تعداد: " . $conn->error;
    }
} else 
{
    // محصول در سبد خرید وجود ندارد، محصول جدید را اضافه می‌کنیم
    $sql_insert = "INSERT INTO cart (name, price, qnt , img) VALUES ('$product_name', '$product_price', 1 , '$product_img')";



// افزودن محصول به سبد خرید
// $sql = "INSERT INTO cart (name, price , img) VALUES ('$product_name', '$product_price' , '$product_img')";

if ($conn->query($sql_insert) === TRUE) {
    echo "محصول به سبد خرید اضافه شد.";
} else {
    echo "خطا: " . $sql_insert . "<br>" . $conn->error;
}
}
$conn->close();
?>