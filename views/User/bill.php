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
    <style>
        .bill-container {
            max-width: 800px;
            margin: 2rem auto;
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .bill-header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .bill-header h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .bill-header p {
            opacity: 0.9;
        }

        .bill-content {
            padding: 2rem;
        }

        .bill-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .bill-section h3 {
            font-size: 1.125rem;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 1rem;
        }

        .bill-items {
            margin-bottom: 2rem;
        }

        .bill-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .bill-item:last-child {
            border-bottom: none;
        }

        .item-name {
            font-weight: 500;
            color: #1e293b;
        }

        .item-price {
            color: #3b82f6;
            font-weight: bold;
        }

        .bill-total {
            background: #f8fafc;
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 2rem;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 1.125rem;
            font-weight: bold;
            color: #1e293b;
        }

        .bill-footer {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid #e2e8f0;
        }

        .bill-footer p {
            color: #64748b;
            margin-bottom: 1rem;
        }

        .print-btn {
            background: #10b981;
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            font-weight: 500;
            cursor: pointer;
            margin-right: 1rem;
        }

        .print-btn:hover {
            background: #059669;
        }

        .continue-btn {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .continue-btn:hover {
            background: #2563eb;
        }

        @media print {
            .bill-footer {
                display: none;
            }
        }
    </style>
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