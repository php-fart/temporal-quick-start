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

        #response {
            margin-top: 20px;
            padding: 15px;
            border-radius: 4px;
            font-family: monospace;
            white-space: pre-wrap;
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Payment System</h1>

        <form id="paymentForm">
            <div class="form-group">
                <label for="amount">Amount:</label>
                <input type="number" id="amount" name="amount" step="0.01" min="0" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" placeholder="Enter payment description..."></textarea>
            </div>

            <button type="submit">Create Payment</button>
        </form>

        <div id="response"></div>
    </div>

    <script>
        document.getElementById('paymentForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = {
                amount: formData.get('amount'),
                description: formData.get('description') || 'No description provided'
            };

            const responseDiv = document.getElementById('response');
            responseDiv.style.display = 'block';
            responseDiv.textContent = 'Sending request...';

            try {
                const response = await fetch('/api/v1/payment/create', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                });

                const responseText = await response.text();
                responseDiv.textContent = `HTTP Status: ${response.status}\n\n${responseText}`;

            } catch (error) {
                responseDiv.textContent = `Error: ${error.message}`;
            }
        });
    </script>
</body>
</html>
