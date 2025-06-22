<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        
        input[type="number"],
        input[type="text"],
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }
        
        textarea {
            height: 100px;
            resize: vertical;
        }
        
        button {
            background-color: #007bff;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
        }
        
        button:hover {
            background-color: #0056b3;
        }
        
        .result {
            margin-top: 20px;
            padding: 15px;
            border-radius: 4px;
        }
        
        .success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        
        .error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        
        .result-content {
            font-family: monospace;
            white-space: pre-wrap;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin-top: 10px;
            border: 1px solid #e9ecef;
        }
        
        .transaction-detail {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin-top: 10px;
            border: 1px solid #e9ecef;
        }
        
        .transaction-detail h3 {
            margin-top: 0;
            margin-bottom: 15px;
            color: #495057;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 5px 0;
            border-bottom: 1px solid #dee2e6;
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: bold;
            color: #6c757d;
        }
        
        .detail-value {
            font-family: monospace;
            color: #495057;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Payment System</h1>
        
        <form action="/create-payment" method="POST">
            <div class="form-group">
                <label for="amount">Amount:</label>
                <input type="number" id="amount" name="amount" step="0.01" min="0" 
                       value="<?= htmlspecialchars($amount ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" 
                         placeholder="Enter payment description..."><?= htmlspecialchars($description ?? '') ?></textarea>
            </div>
            
            <button type="submit">Create Payment</button>
        </form>
        
        <?php if (isset($success) && $success): ?>
            <div class="result success">
                <strong>Payment Created Successfully!</strong>
                <div class="transaction-detail">
                    <h3>Transaction Details</h3>
                    <div class="detail-row">
                        <span class="detail-label">Transaction UUID:</span>
                        <span class="detail-value"><?= htmlspecialchars($result['transaction_uuid']) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Fiscal Code UUID:</span>
                        <span class="detail-value"><?= htmlspecialchars($result['fiscal_code_uuid']) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Amount:</span>
                        <span class="detail-value"><?= htmlspecialchars($result['payment_info']['amount']) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Description:</span>
                        <span class="detail-value"><?= htmlspecialchars($result['payment_info']['description']) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Status:</span>
                        <span class="detail-value"><?= htmlspecialchars($result['status']) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Created At:</span>
                        <span class="detail-value"><?= htmlspecialchars($result['created_at']) ?></span>
                    </div>
                </div>
            </div>
        <?php elseif (isset($success) && !$success): ?>
            <div class="result error">
                <strong>Error:</strong> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
