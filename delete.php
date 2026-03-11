<?php
require_once("ConnectionManager.php");

// TODO: Handle POST submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // - Get menuID from $_POST
    $menuID = $_POST['id'] ?? 0;

    if (!$menuID) {
        die("Invalid menu ID");
    }

    try {
        $cm = new ConnectionManager();
        $conn = $cm->getConnection();

        // Get photo filename before deleting
        $stmt = $conn->prepare("SELECT photo FROM menu_items WHERE menuID = :id");
        $stmt->execute([':id' => $menuID]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // - Delete the associated image file from /img/
        if ($row && $row['photo'] && $row['photo'] !== 'placeholder.jpg') {
            $photoPath = 'img/' . $row['photo'];
            if (file_exists($photoPath)) {
                unlink($photoPath);
            }
        }

        // - Delete menu item from database
        $stmt = $conn->prepare("DELETE FROM menu_items WHERE menuID = :id");
        $stmt->execute([':id' => $menuID]);

        // - Redirect back to index.php
        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
} else {
    // If not POST, redirect to index
    header("Location: index.php");
    exit;
}
