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
<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پرداخت آنلاین</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing:border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color:rgb(19, 19, 19);
            display: flex;
            justify-content: center;
            align-items: center;
            height: max-content;
            direction: rtl;
        }
        .payment-container {
            background-color:rgb(255, 255, 255);
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 500px;
        }

        .payment-form h2 {
            text-align: center;
            margin: 0 auto;
            margin-bottom: 20px;
            font-size: 24px;
            color:rgb(0, 0, 0);
        }

        .form-group {
            margin-bottom:10px;
            text-align: center; /* وسط‌چین کردن متنی که در هر فیلد است */
        }

        label {
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
            margin-top: 1rem;
            color:rgb(0, 0, 0);
            font-weight: bold;
            text-align: center; /* وسط‌چین کردن متن */
        }

        p#amount {
            font-size: 18px;
            color:rgb(0, 0, 0);
            font-weight: bold;
            text-align: center; /* وسط‌چین کردن مبلغ */
        }

        .card-input-container {
            display: flex;
            justify-content: space-between;
            gap: 5px; /* فاصله بین فیلدهای شماره کارت */
        }

        .card-input-container input {
            width: 22%;
            padding: 10px;
            font-size: 16px;
            text-align: center;
            border-radius: 8px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            transition: all 0.3s ease;
        }

        .card-input-container input:focus {
            border-color: #000000;
            background-color: #fff;
        }

        input[type="text"], input[type="time"] {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-top: 5px;
            background-color: #f9f9f9;
            transition: all 0.3s ease;
            text-align: center; /* وسط‌چین کردن متن داخل فیلد */
        }

        input[type="text"]:focus, input[type="time"]:focus {
            border-color: #000000;
            background-color: #fff;
        }

        .expiry-date {
            display: flex;
            justify-content: space-between;
            gap: 5px; /* فاصله بین فیلدهای تاریخ انقضا */
        }

        .expiry-date input {
            width: 45%;
            padding: 10px;
            font-size: 16px;
            text-align: center;
            border-radius: 8px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            transition: all 0.3s ease;
        }

        .expiry-date input:focus {
            border-color: #11110e;
            background-color: #fff;
        }

        button#send-otp-btn {
            padding: 12px;
            background-color:rgba(53, 39, 56, 0.95);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
            text-align: center;
        }

        button#send-otp-btn:hover {
            background-color: #9e9e9e;
        }

        button#send-otp-btn:active {
            background-color: #b5b2b2;
        }

        button.submit-btn {
            width: 100%;
            padding: 14px;
            font-size: 16px;
            background-color: #c9c9c9;
            color: black;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-align: center;
            display: inline-block;
        }

        button.submit-btn:hover {
            background-color: #bababa;
        }

        button.submit-btn:active {
            background-color: #7e7e7e;
        }

        .timer {
            text-align: center;
            margin: 20px 0;
        }

        .timer p {
            font-size: 18px;
            color:rgb(165, 11, 11);
        }

        .error-message {
            color: red;
            font-size: 14px;
            text-align: center;
            margin-top: 15px;
        }

        .payment-success {
            display: none;
            text-align: center;
            background-color: #ffffff2c;
            color: white;
            padding: 20px;
            border-radius: 8px;
        }

        .payment-success h2 {
            color: #2c2b28;
        }

        .payment-success button {
            padding: 12px;
            background-color: #a3a2a1;
            color: #333;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }

        .payment-success button:hover {
            background-color: #6c6c6c;
        }

        .payment-success button:active {
            background-color: #6c6c6c;
        }

        .payment-form input[type="text"], 
        .payment-form input[type="time"], 
        .payment-form input[type="number"], 
        .payment-form button {
            direction: rtl;
            text-align:center; /* تنظیمات برای راست‌چین کردن متن داخل فیلد */
        }

        .card-input-container input,
        #otp {
            text-align: center; /* این برای دکمه‌ها و فیلدهای شماره کارت و رمز پویا است */
        }

    </style>
</head>
<body>
    <div class="payment-container">
        <div class="payment-form" id="payment-form">
            <h2>پرداخت آنلاین</h2>
            <form id="payment-form" action="#" method="POST">
            <?php
              if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                  $itemPrice = $row ["price"] * $row["qnt"];
                  $totalprice += $itemPrice;
                }}
                else {
                    echo "0";
                }
                  ?>
                <div class="form-group">
                    <label for="amount">مبلغ پرداختی</label>
                    <p id="amount"><?php echo number_format($totalprice); ?></p>
                </div>

                <div class="form-group">
                    <label for="card-number">شماره کارت</label>
                    <div class="card-input-container">
                        <input type="text" id="card-1" maxlength="4" placeholder="0000" required>
                        <input type="text" id="card-2" maxlength="4" placeholder="0000" required>
                        <input type="text" id="card-3" maxlength="4" placeholder="0000" required>
                        <input type="text" id="card-4" maxlength="4" placeholder="0000" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="expiry-date">تاریخ انقضا</label>
                    <div class="expiry-date">
                        <input type="text" id="expiry-month" maxlength="2" placeholder="ماه" required>
                        <input type="text" id="expiry-year" maxlength="2" placeholder="سال" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="cvv">کد امنیتی (CVV)</label>
                    <input type="text" id="cvv" maxlength="3" placeholder="CVV را وارد کنید" required>
                </div>

                <div class="form-group">
                    <label for="otp">رمز پویا</label>
                    <input type="text" id="otp" maxlength="6" placeholder="رمز پویا را وارد کنید" required>
                    <button type="button" id="send-otp-btn">ارسال رمز پویا</button>
                </div>

                <div class="timer">
                    <p>زمان باقی‌مانده: <span id="countdown">05:00</span></p>
                </div>

                <div class="form-group">
                    <button type="submit" class="submit-btn">پرداخت</button>
                </div>
                <div class="error-message" id="error-message"></div>
            </form>
        </div>

        <!-- بخش پیغام موفقیت -->
        <div class="payment-success" id="payment-success" style="display:none;">
            <h2>پرداخت انجام شد</h2>
            <a href="index.html">
            <button onclick="goBack()">بازگشت به سایت</button>
        </a>
        </div>
    </div>

    <script>
        let countdownElement = document.getElementById('countdown');
        let timeRemaining = 5 * 60;

        function updateCountdown() {
            let minutes = Math.floor(timeRemaining / 60);
            let seconds = timeRemaining % 60;
            minutes = minutes < 10 ? '0' + minutes : minutes;
            seconds = seconds < 10 ? '0' + seconds : seconds;

            countdownElement.textContent = `${minutes}:${seconds}`;
            timeRemaining--;

            if (timeRemaining < 0) {
                clearInterval(timerInterval);
                alert('زمان تمام شده است');
            }
        }

        let timerInterval = setInterval(updateCountdown, 1000);

        document.getElementById('payment-form').addEventListener('submit', function(event) {
            event.preventDefault();  

            setTimeout(function() {
                document.getElementById('payment-form').style.display = 'none'; // مخفی کردن فرم
                document.getElementById('payment-success').style.display = 'block'; // نمایش پیغام موفقیت
            }, 2000);  
        });

        function goBack() {
            window.location.href = 'https://your-website.com'; // لینک بازگشت به سایت خود را وارد کنید
        }
    </script>
</body>
</html>
