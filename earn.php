<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #202221;
            color: #ffffff;
            font-family: Arial, sans-serif;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            padding: 20px;
        }
        .button, .form-button {
            background-color: #22c55e;
            border: none;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px;
            text-decoration: none;
            display: inline-block;
        }
        .button:hover, .form-button:hover {
            background-color: #202221;
        }
        .message {
            background-color: #22c55e;
            color: white;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-container {
            background: rgba(255, 255, 255, 0.2);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }
        .form-container input[type="submit"] {
            background-color: #22c55e;
            border: none;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-container input[type="submit"]:hover {
            background-color: #202221;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="update_funds.php?action=engagement" class="button">Engage and Update Funds</a>

        <div class="form-container">
            <form action="update_funds.php" method="POST">
                <input type="hidden" name="update_funds" value="1">
                <input type="submit" value="Update Funds">
            </form>
        </div>
    </div>
</body>
</html>
