<?php
require_once("ConnectionManager.php");

// TODO: Handle POST submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate inputs
    if (empty($_POST['menuName']) || empty($_POST['menuPrice'])) {
        die("Menu name and price are required.");
    }

    $menuName = $_POST['menuName'];
    $menuPrice = $_POST['menuPrice'];
    $description = $_POST['description'] ?? '';
    $vegan = $_POST['vegan'] ?? 0;

    // Handle file upload
    $photo = 'placeholder.jpg'; // default
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
        $fileType = $_FILES['photo']['type'];

        if (!in_array($fileType, $allowedTypes)) {
            die("Only JPG, PNG, and GIF images are allowed.");
        }

        // Generate unique filename
        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $photo = uniqid() . '.' . $ext;
        $uploadPath = 'img/' . $photo;

        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
            die("Error uploading file.");
        }
    }

    // Insert new menu item into the database
    try {
        $cm = new ConnectionManager();
        $conn = $cm->getConnection();

        $sql = "INSERT INTO menu_items (menuName, menuPrice, description, photo, vegan) 
                VALUES (:menuName, :menuPrice, :description, :photo, :vegan)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':menuName' => $menuName,
            ':menuPrice' => $menuPrice,
            ':description' => $description,
            ':photo' => $photo,
            ':vegan' => $vegan
        ]);

        // Redirect back to index
        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Menu Item</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">

        <h1>Add New Menu Item</h1>
        <a href="index.php">⬅ Back to Menu</a>
        <hr>

        <form action="insert.php" method="post" enctype="multipart/form-data">
            <label>Menu Item Name:</label>
            <input type="text" name="menuName" required>

            <label>Price:</label>
            <input type="number" step="0.01" name="menuPrice" required>

            <label>Description:</label>
            <textarea name="description" rows="4"></textarea>

            <label>Vegan?</label>
            <select name="vegan">
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>

            <label>Photo Upload:</label>
            <input type="file" name="photo" accept="image/*">

            <button type="submit" style="margin-top: 15px;">Insert Item</button>
        </form>

    </div>
</body>

</html>