<?php
$conn = new mysqli("localhost", "root", "", "hackathon_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sql = "SELECT * FROM products WHERE id = $product_id";
$result = $conn->query($sql);
$product = $result->fetch_assoc();

if (!$product) {
    echo "Product not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($product['title']) ?> - ReWear</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;500&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: #1e1f1f;
      color: #fff;
    }
    .container {
      max-width: 800px;
      margin: 2rem auto;
      background: #2a2c2b;
      padding: 2rem;
      border-radius: 12px;
    }
    h1 {
      font-family: 'Playfair Display', serif;
      color: #a8dadc;
    }
    img {
      width: 100%;
      max-height: 400px;
      object-fit: cover;
      border-radius: 12px;
      margin-bottom: 1.5rem;
    }
    .details p {
      line-height: 1.6;
      color: #ddd;
    }
    .buttons {
      margin-top: 2rem;
    }
    .buttons form {
      display: inline;
    }
    .buttons button {
      background: #6b8e6e;
      border: none;
      padding: 12px 20px;
      color: white;
      font-weight: bold;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.3s;
    }
    .buttons button:hover {
      background: #5a7b5d;
    }
  </style>
</head>
<body>

<div class="container">
  <h1><?= htmlspecialchars($product['title']) ?></h1>
 

  <div class="details">
    <p><strong>Category:</strong> <?= htmlspecialchars($product['category']) ?></p>
    <p><strong>Type:</strong> <?= htmlspecialchars($product['type']) ?></p>
    <p><strong>Size:</strong> <?= htmlspecialchars($product['size']) ?></p>
    <p><strong>Condition:</strong> <?= htmlspecialchars($product['condition']) ?></p>
    <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($product['description'])) ?></p>
  </div>

  <div class="buttons">
    <!-- ✅ FORM sends POST to swap_request.php -->
    <form action="swap_request.php" method="POST">
      <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
      <button type="submit">Request Swap</button>
    </form>
  </div>
</div>

</body>
</html>
