<?php
  $conn = new mysqli("localhost", "root", "", "game_db");
  

  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_id'])) {
    $remove_id = $_POST['remove_id'];
    $delete_sql = "DELETE FROM cart WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param('i', $remove_id);
    $stmt->execute();
    $stmt->close();
}

  $sql = "SELECT * FROM cart";
  $result = $conn->query($sql);
  $totalprice = 0;


  $conn->close();
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="../css/styles.css"/>	
<link rel="stylesheet" type="text/css" href="../css/cart.css"/>	
<title>سبدخرید</title>   
 <style>
.buyMain{

    min-height: 61vh;
    justify-content: space-evenly;
}

.cart-title {
    width: max-content !important;
    margin: 1rem !important;
}

.pstyle{
    margin-top: 2rem;
    font-size: 2rem;
}
.imgbuy{
    width: 450px;
    height: 350px;
    margin-top:3%;
}
.cart-title{
    width: max-content !important;
    margin: 1rem auto;
}
.delete{
    background: purple !important;
    color: white !important;
}
.payBtn{
    background: orangeRed !important;
    color: white !important;
    box-shadow: 0 0 10px black !important;
}
    </style>
</head>

<body>
<ul class="nav">
        <ul class="topnav">
            <li><a href="Index.html" class="rightFloat">خانه</a></li>	
            <li class="subli"> انواع محصولات
               <ul class="sub">
                  <li><a href="cd.html"> سی دی اشتراکی</a></li>
                  <li><a href="microsoft.html">مایکروسافت استور</a></li>
               </ul>
            </li>		
            <li><a href="blog.html">مقاله</a></li>	
        <li><a href="about.html">درباره گیم سنتر</a></li>	

        </ul>
     
        <li><a href="cart.php" class="rightFloat">سبد خرید</a></li>	
    
    </ul>


<div class="buyMain">
<p class="pstyle">سبد خرید</p>

     <section>
         <?php
              if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                  $itemPrice = $row ["price"] * $row["qnt"];
                  $totalprice += $itemPrice;
                  echo " 
                      <div class='product-box'>
                      <img src='" . $row["img"] . "' alt='" . $row["name"] . "' class='product-image'>
                      <div class='product-info'>
                          <h2 class='cart-title'>" . $row["name"] . "</h2>
                          <p>قیمت: " . $row["price"] . " تومان</p>
                          <p>تعداد: " . $row['qnt'] . "</p>
                         </div> 
                          <p>قیمت نهایی: " . $itemPrice . " تومان</p>
                         <div class='priceWrapper'>
                              <form method='POST' action=''>
                                  <input type='hidden' name='remove_id' value='" . $row["id"] . "'>
                                  <button type='submit' class='delete'>حذف</button>
                              </form>
                         
                      </div>
                  </div>
                  ";
                }
            } else {
                echo "<p class='title'>سبد خرید خالی  </p>";
            }
         ?>
                   </section> 
            <h3 class="payTitle">جمع سبد خرید  : <?php echo number_format($totalprice); ?></h3>
            <button class="payBtn">پرداخت</button>

        </div>

        </div>
        </div>
    


<footer>
    <div class="rightFoot">
        <span class="footTitle">راه ارتباطی</span>
        تهران – خیابان مطهری – بعد از اتوبان مدرس – ابتدای خیابان میرعماد – پلاک ۲
        <br>
        ایمیل : zeynabjahanara80@gmail.com
        <br>
        لینکدین : linkedin.com/in/zeynab-jahanara
    </div>
    <img class="centerFoot" src="../images/اینماد.jfif" style="width: 200px; height: 130px;border-radius: 20px;">
    <img class="centerFoot" src="../images/e2.png" style="width: 200px; height: 130px;border-radius: 20px;">

 </footer> 
<div class="footBTM">تمام حقوق سایت محفوظ است</div>  
<script>
    document.querySelector('.payBtn').onclick = () =>
{
    location.href = 'payment.php'
}
</script>
</body>
</html>
