<?php
session_start();
// include("../includes/db_connect.php"); // Commented out for dummy mode
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit; }
// Dummy user data
$user = [
    'name' => $_SESSION['user_name'],
    'email' => 'test@test.com', // Dummy email
    'phonenum' => '1234567890',
    'address' => 'Dummy Address'
];
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <link rel="stylesheet" href="../css/customer.css">
</head>
<body>
<div class="container">
    <h2>My Profile</h2>
    <form method="post" action="../controllers/profile_update.php">
        <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        <input type="text" name="phonenum" value="<?php echo htmlspecialchars($user['phonenum']); ?>">
        <textarea name="address"><?php echo htmlspecialchars($user['address']); ?></textarea>
        <button type="submit" name="update">Update</button>
    </form>
    <br>
    <a href="customer_dashboard.php">Back to Dashboard</a>
</div>
</body>
</html>
