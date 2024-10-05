<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Image</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            position: relative;
        }
        video.background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            position: relative;
            z-index: 1;
        }
        .form-container {
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            text-align: center;
        }
        .image-preview {
            margin-bottom: 15px;
        }
        .image-preview img {
            max-width: 100%;
            height: auto;
            border-radius: 100px;
        }
        input[type="file"] {
            margin-bottom: 10px;
        }
        input[type="submit"], .change-image-btn {
            background-color: #007bff;
            border: none;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        .change-image-btn {
            background-color: #6c757d;
        }
    </style>
</head>
<body>
    <!-- Background Video -->
    <video class="background" autoplay muted loop>
        <source src="videos/video.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <div class="container">
        <div class="form-container">
            <form action="upload.php" method="POST" enctype="multipart/form-data">
                <div class="image-preview">
                    <img id="imagePreview" src="" alt="Image Preview" style="display: none;">
                </div>
                <input type="file" id="fileInput" name="image" accept="image/*" required>
                <input type="submit" name="upload" value="Upload Image">
                <button type="button" class="change-image-btn" onclick="document.getElementById('fileInput').click();">Change Image</button>
            </form>
        </div>
    </div>

    <script>
        const fileInput = document.getElementById('fileInput');
        const imagePreview = document.getElementById('imagePreview');

        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    imagePreview.src = event.target.result;
                    imagePreview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>
