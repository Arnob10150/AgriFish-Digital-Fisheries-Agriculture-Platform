<?php
    session_start();
    if(!isset($_SESSION["user_id"]) || $_SESSION["role"] != "customer")
    {
        header("Location:../login.php");
        exit;
    }

    $bill = $_SESSION['bill'] ?? null;
    if (!$bill) {
        header("Location: customer.php");
        exit;
    }

    $products = [
        'Ilish (Hilsa)' => 2400,
        'Rui (River)' => 750,
        'Katla (River)' => 750,
        'Ayre (Giant Catfish)' => 1500,
        'Chitol (Featherback)' => 1250,
        'Boal (Wallago)' => 800,
        'Shing (Stinging Catfish)' => 570,
        'Pabda (Pabo Catfish)' => 450,
        'Rupchanda (Pomfret)' => 1200,
        'Koral (Seabass)' => 800,
        'Tuna' => 500,
        'Loitta (Bombay Duck)' => 350,
        'Surma (King Fish)' => 600,
        'Poa (Yellow Croaker)' => 550,
        'Golda Chingri (Prawn)' => 1350,
        'Bagda/Tiger Shrimp' => 1000,
        'Lobster' => 2000,
        'Crab (Mud/Blue)' => 700,
        'Churi Shutki (Dried)' => 1200,
        'Basa/Dory Fillet' => 580
    ];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill - DFAP</title>
    <link rel="stylesheet" href="Css/style.css">
    <link rel="stylesheet" href="Css/dashboard.css">
    <link rel="stylesheet" href="Css/bill.css">
</head>
<body>
    <div class="bill-container">
        <div class="bill-header">
            <h1>🧾 Purchase Bill</h1>
            <p>DFAP - Digital Fisheries & Agriculture Platform</p>
        </div>

        <div class="bill-content">
            <div class="bill-info">
                <div class="bill-section">
                    <h3>Customer Information</h3>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
                    <p><strong>Customer ID:</strong> <?php echo htmlspecialchars($_SESSION['user_id']); ?></p>
                </div>
                <div class="bill-section">
                    <h3>Bill Details</h3>
                    <p><strong>Bill Date:</strong> <?php echo date('F j, Y', strtotime($bill['date'])); ?></p>
                    <p><strong>Bill Time:</strong> <?php echo date('g:i A', strtotime($bill['date'])); ?></p>
                    <p><strong>Bill Number:</strong> #<?php echo rand(10000, 99999); ?></p>
                </div>
            </div>

            <div class="bill-items">
                <h3>Items Purchased</h3>
                <?php foreach ($bill['items'] as $item): ?>
                    <div class="bill-item">
                        <span class="item-name"><?php echo htmlspecialchars($item); ?> (1 kg)</span>
                        <span class="item-price">৳<?php echo $products[$item] ?? 0; ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="bill-total">
                <div class="total-row">
                    <span>Total Amount:</span>
                    <span>৳<?php echo $bill['total']; ?></span>
                </div>
            </div>

            <div class="bill-footer">
                <p>Thank you for shopping with DFAP! Your fresh seafood will be delivered soon.</p>
                <button onclick="window.print()" class="print-btn">Print Bill</button>
                <a href="customer.php" class="continue-btn">Continue Shopping</a>
            </div>
        </div>
    </div>

    <script>
        // Auto-print on mobile or redirect
        if (window.innerWidth < 768) {
            setTimeout(() => {
                window.print();
            }, 1000);
        }
    </script>
</body>
</html>