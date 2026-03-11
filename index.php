<?php
require_once("ConnectionManager.php");

$cm = new ConnectionManager();
$conn = $cm->getConnection();

// TODO: Fetch all menu items from the database into an array called $items
$stmt = $conn->prepare("SELECT * FROM menu_items");
$stmt->execute();
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// TODO: Shuffle the array so that the items appear in random order on each page load
shuffle($items);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Restaurant Menu Manager</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">

        <h1>Menu Item Manager</h1>
        <hr>

        <div class="menu-grid">

            <?php
            // TODO: Loop through $items array to generate each menu item dynamically
            foreach ($items as $item):
            ?>

                <div class="menu-item">
                    <!-- TODO: Display menu name -->
                    <h2><?php echo htmlspecialchars($item['menuName']); ?></h2>

                    <!-- TODO: Display menu photo -->
                    <img src="img/<?php echo htmlspecialchars($item['photo'] ?? 'placeholder.jpg'); ?>" alt="<?php echo htmlspecialchars($item['menuName']); ?>">

                    <!-- TODO: Display description -->
                    <div class="description"><?php echo htmlspecialchars($item['description']); ?></div>

                    <!-- TODO: Display price and vegan status -->
                    <div class="price-vegan">
                        <span>$<?php echo number_format($item['menuPrice'], 2); ?></span>
                        <span><?php echo $item['vegan'] ? 'Vegan' : 'Non-Vegan'; ?></span>
                    </div>

                    <!-- TODO: Action buttons -->
                    <div class="actions">
                        <!-- Update button -->
                        <form action="update.php" method="get">
                            <input type="hidden" name="id" value="<?php echo $item['menuID']; ?>">
                            <button type="submit">Update</button>
                        </form>

                        <!-- Delete button -->
                        <form action="delete.php" method="post">
                            <input type="hidden" name="id" value="<?php echo $item['menuID']; ?>">
                            <button type="submit" class="delete" onclick="return confirm('Delete this item?')">Delete</button>
                        </form>
                    </div>
                </div>

            <?php
            // TODO: Close foreach loop here
            endforeach;
            ?>

            <!-- ADD NEW ITEM CARD -->
            <div class="menu-item add-new">
                <a href="insert.php">➕ Add New Menu Item</a>
            </div>

        </div> <!-- end menu-grid -->
    </div> <!-- end container -->
</body>

</html>