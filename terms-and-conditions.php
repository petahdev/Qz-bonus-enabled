<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quizzy - Terms and Conditions</title>
    <style>
        body {
            background-color: #202221;
            color: #ffffff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h1, h2, h3 {
            color: #22c55e;
        }
        .terms-content {
            background-color: #2c2f34;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .terms-content h3 {
            border-bottom: 2px solid #22c55e;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .terms-content p {
            margin-bottom: 15px;
            line-height: 1.6;
        }
        .form-group {
            margin-top: 20px;
        }
        .form-check {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .form-check input {
            margin-right: 10px;
            accent-color: #22c55e;
        }
        .btn-primary {
            background-color: #22c55e;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #1f9c50;
        }
        .btn-primary:disabled {
            background-color: grey;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Quizzy - Terms and Conditions</h1>
        <div class="terms-content">
            <h3>Terms and Conditions</h3>
            <p>Welcome to Quizzy. By using our platform, you agree to the following terms and conditions:</p>

            <p><strong>1. Payment Processing:</strong> Withdrawals are processed within 12 hours. You will receive your payment directly to your provided account within this time frame after making a withdrawal request.</p>

            <p><strong>2. Reset Upon Withdrawal:</strong> Once a withdrawal request has been processed, your account balance and earnings will be reset to zero.</p>

            <p><strong>3. Eligibility for Withdrawal:</strong> To be eligible for withdrawal, you must meet the minimum earnings threshold set by Quizzy. Currently, the minimum withdrawal is Ksh 20.</p>

            <p><strong>4. Watch Ads for Earnings:</strong> Users earn money by Completing Quizzes and passing by 90% through our platform. By using this service, you agree to comply with all guidelines related Quiz task handling.</p>

            <p><strong>5. Account Usage:</strong> Each user is allowed only one account. If multiple accounts are detected, they may be permanently suspended, and possible loss of funds</p>

            <p><strong>6. Fraudulent Activities:</strong> Any attempt to manipulate, hack, or misuse the platform for illegitimate gains may result in immediate account termination and forfeiture of earnings.</p>

            <p><strong>7. Modifications to Terms:</strong> Gainly reserves the right to modify these terms at any time. Users will be notified via email or platform notification of any changes.</p>

            <p><strong>8. Support:</strong> Should you encounter any issues or have questions, please contact our support team via the provided channels.</p>
        </div>

        <form action="withdrawal_form.php" method="POST" class="form-group">
            <div class="form-check">
                <input type="radio" id="accept-terms" name="accept-terms" required>
                <label for="accept-terms">I agree to the terms and conditions.</label>
            </div>
            <button type="submit" class="btn-primary">Proceed to Withdrawal</button>
        </form>
    </div>

    <script>
        // Disables button unless terms are accepted
        const checkbox = document.getElementById('accept-terms');
        const submitButton = document.querySelector('button[type="submit"]');

        checkbox.addEventListener('change', function() {
            if (this.checked) {
                submitButton.disabled = false;
            } else {
                submitButton.disabled = true;
            }
        });
    </script>
</body>
</html>
