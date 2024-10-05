<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Confirm Your Registration</h2>
        <form action="confirm_code.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" 
                       value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>" 
                       readonly required>
            </div>
            <div class="mb-3">
                <label for="confirmation_code" class="form-label">Confirmation Code</label>
                <input type="text" class="form-control" id="confirmation_code" name="confirmation_code" required>
            </div>
            <button type="submit" class="btn btn-primary">Confirm</button>
        </form>
    </div>
</body>
</html>
