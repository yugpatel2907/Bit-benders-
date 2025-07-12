<?php
session_start();
$conn = new mysqli("localhost", "root", "", "hackathon_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Simulated user
$user_id = 1;

$fromSwap = isset($_GET['fromSwap']) && $_GET['fromSwap'] == '1';
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $type = $_POST['type'];
    $size = $_POST['size'];
    $condition = $_POST['condition'];
    $price = $_POST['price'];
    $image_url = $_POST['image_url'];

    // ✅ Use backticks for `condition` column
    $stmt = $conn->prepare("INSERT INTO products (title, description, category, type, size, `condition`, uploader_id, image_url, price)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssisd", $title, $description, $category, $type, $size, $condition, $user_id, $image_url, $price);
    
    if ($stmt->execute()) {
        // ✅ If this came from a swap, increment swaps_ongoing
        if ($fromSwap) {
            $conn->query("UPDATE hackathon_users SET swaps_ongoing = swaps_ongoing + 1 WHERE id = $user_id");
        }

        // ✅ Redirect to dashboard
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Failed to add item: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Item – ReWear</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;500&display=swap" rel="stylesheet">
  <style>
    body {
      background: #1e1f1f; color: white; font-family: 'Poppins', sans-serif;
      padding: 2rem;
    }
    .container {
      background: #2a2c2b;
      max-width: 600px;
      margin: auto;
      padding: 2rem;
      border-radius: 12px;
    }
    h2 {
      color: #a8dadc;
      font-family: 'Playfair Display', serif;
    }
    input, select, textarea {
      width: 100%;
      padding: 10px;
      margin-bottom: 1rem;
      border-radius: 8px;
      border: none;
      background: #3e4c3a;
      color: #fff;
    }
    button {
      background: #6b8e6e;
      color: #fff;
      padding: 12px 20px;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
    }
    button:hover {
      background: #5a7b5d;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>Add New Item</h2>
  <form method="POST">
    <input type="text" name="title" placeholder="Item Title" required>
    <textarea name="description" placeholder="Item Description" required></textarea>
    <input type="text" name="category" placeholder="Category (e.g. Tops)" required>
    <input type="text" name="type" placeholder="Type (e.g. Shirt)" required>
    <input type="text" name="size" placeholder="Size (e.g. M, L)" required>
    <input type="text" name="condition" placeholder="Condition (e.g. New, Gently Used)" required>
    <input type="text" name="image_url" placeholder="Image URL" required>
    <input type="number" name="price" placeholder="Estimated Price" step="0.01" required>
    <button type="submit">Submit Item</button>
  </form>
</div>

</body>
</html>
