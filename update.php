<?php
require_once("ConnectionManager.php");

// TODO: Get the item id from $_GET
$menuID = $_GET['id'] ?? 0;

// TODO: Fetch item data from database for pre-populating form
$currentItem = null;
if ($menuID) {
    try {
        $cm = new ConnectionManager();
        $conn = $cm->getConnection();

        $stmt = $conn->prepare("SELECT * FROM menu_items WHERE menuID = :id");
        $stmt->execute([':id' => $menuID]);
        $currentItem = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}

// TODO: Handle POST submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Re-fetch item because GET context is gone
    $cm = new ConnectionManager();
    $conn = $cm->getConnection();

    $stmt = $conn->prepare("SELECT * FROM menu_items WHERE menuID = :id");
    $stmt->execute([':id' => $_POST['menuID']]);
    $currentItem = $stmt->fetch(PDO::FETCH_ASSOC);

    // Validate inputs
    if (empty($_POST['menuName']) || empty($_POST['menuPrice'])) {
        die("Menu name and price are required.");
    }

    $menuID = $_POST['menuID'];
    $menuName = $_POST['menuName'];
    $menuPrice = $_POST['menuPrice'];
    $description = $_POST['description'] ?? '';
    $vegan = $_POST['vegan'] ?? 0;

    // Handle optional file upload
    $photo = ''; // Initialize variable

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        // User uploaded a new photo
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
        $fileType = $_FILES['photo']['type'];

        if (!in_array($fileType, $allowedTypes)) {
            die("Only JPG, PNG, and GIF images are allowed.");
        }

        // Get current photo to delete it later if needed
        if ($currentItem && $currentItem['photo'] && $currentItem['photo'] !== 'placeholder.jpg') {
            $oldPhotoPath = 'img/' . $currentItem['photo'];
            if (file_exists($oldPhotoPath)) {
                unlink($oldPhotoPath);
            }
        }

        // Generate unique filename
        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $photo = uniqid() . '.' . $ext;
        $uploadPath = 'img/' . $photo;

        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
            die("Error uploading file.");
        }
    } else {
        // No new photo uploaded, keep the current one
        if ($currentItem) {
            $photo = $currentItem['photo'];
        } else {
            // If no current item and no upload, use placeholder
            $photo = 'placeholder.jpg';
        }
    }

    // Update menu item in database
    try {
        $cm = new ConnectionManager();
        $conn = $cm->getConnection();

        $sql = "UPDATE menu_items 
                SET menuName = :menuName, menuPrice = :menuPrice, description = :description, 
                    photo = :photo, vegan = :vegan 
                WHERE menuID = :menuID";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':menuName' => $menuName,
            ':menuPrice' => $menuPrice,
            ':description' => $description,
            ':photo' => $photo,
            ':vegan' => $vegan,
            ':menuID' => $menuID
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
    <title>Update Menu Item</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">

        <h1>Update Menu Item</h1>
        <a href="index.php">⬅ Back to Menu</a>
        <hr>

        <form action="update.php" method="post" enctype="multipart/form-data">
            <!-- TODO: Hidden input for menuID -->
            <input type="hidden" name="menuID" value="<?php echo htmlspecialchars($currentItem['menuID'] ?? ''); ?>">

            <!-- TODO: Menu Name input -->
            <label>Menu Item Name:</label>
            <input type="text" name="menuName" value="<?php echo htmlspecialchars($currentItem['menuName'] ?? ''); ?>" required>

            <!-- TODO: Price input -->
            <label>Price:</label>
            <input type="number" step="0.01" name="menuPrice" value="<?php echo htmlspecialchars($currentItem['menuPrice'] ?? ''); ?>" required>

            <!-- TODO: Description input -->
            <label>Description:</label>
            <textarea name="description" rows="4"><?php echo htmlspecialchars($currentItem['description'] ?? ''); ?></textarea>

            <!-- TODO: Vegan select -->
            <label>Vegan?</label>
            <select name="vegan">
                <option value="0" <?php echo ($currentItem['vegan'] ?? 0) == 0 ? 'selected' : ''; ?>>No</option>
                <option value="1" <?php echo ($currentItem['vegan'] ?? 0) == 1 ? 'selected' : ''; ?>>Yes</option>
            </select>

            <!-- TODO: Show current photo -->
            <label>Current Photo:</label>
            <p><img src="img/<?php echo htmlspecialchars($currentItem['photo'] ?? 'placeholder.jpg'); ?>" width="120"></p>

            <!-- TODO: Upload new photo input -->
            <label>Upload New Photo (optional):</label>
            <input type="file" name="photo" accept="image/*">

            <button type="submit" style="margin-top: 15px;">Update Item</button>
        </form>

    </div>
</body>

</html>