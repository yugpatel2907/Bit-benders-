<?php
$conn = new mysqli("localhost", "root", "", "hackathon_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assume logged-in user has id = 1
$user_id = 1;

// Get user info
$user_sql = "SELECT username, COALESCE(points, 0) AS points, COALESCE(swaps_ongoing, 0) AS swaps_ongoing, COALESCE(swaps_completed, 0) AS swaps_completed FROM hackathon_users WHERE id = $user_id";
$user_result = $conn->query($user_sql);
if ($user_result->num_rows === 0) {
    die("User not found.");
}
$user = $user_result->fetch_assoc();

$swaps_ongoing = $user['swaps_ongoing'];
$swaps_completed = $user['swaps_completed'];

// Count uploaded items
$uploads_sql = "SELECT COUNT(*) AS uploaded_count FROM products WHERE uploader_id = $user_id";
$uploads_result = $conn->query($uploads_sql);
$uploaded_count = $uploads_result->fetch_assoc()['uploaded_count'] ?? 0;

// Get all products
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ReWear – Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;500&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0; padding: 0; font-family: 'Poppins', sans-serif;
      background: #1e1f1f; color: #f5f5f5;
    }
    header, footer {
      background: #2a2c2b;
      padding: 1rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .logo {
      font-family: 'Playfair Display', serif;
      font-size: 1.8rem;
      color: #a8dadc;
    }
    .history-button {
      font-size: 1rem;
      padding: 0.7rem 1.2rem;
      background: #6b8e6e;
      color: #fff;
      border: none;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.3s ease;
    }
    .history-button:hover {
      background: #66b2b2;
    }
    .banner {
      width: 100%;
      height: 280px;
      background: url('b.png') no-repeat center center/cover;
    }
    .intro {
      text-align: center;
      padding: 2rem;
    }
    .intro h1 {
      font-size: 2.4rem;
      color: #a8dadc;
      font-family: 'Playfair Display', serif;
    }
    .intro p {
      font-size: 1.1rem;
      color: #ccc;
      margin: 1rem 0;
    }
    .section {
      padding: 2rem;
    }
    .dashboard-info {
      display: flex;
      justify-content: space-around;
      text-align: center;
      margin-bottom: 2rem;
      flex-wrap: wrap;
      gap: 1rem;
    }
    .info-box {
      background: #3e4c3a;
      padding: 1rem 2rem;
      border-radius: 12px;
      min-width: 200px;
    }
    .info-box h3 {
      margin: 0;
      font-size: 1.2rem;
      color: #fff;
    }
    .info-box p {
      margin: 0.5rem 0 0;
      font-size: 1.5rem;
      font-weight: bold;
      color: #b4a7d6;
    }
    .categories {
      display: flex;
      gap: 1rem;
      flex-wrap: wrap;
      justify-content: center;
    }
    .category {
      background: #3e4c3a;
      color: #fff;
      padding: 0.8rem 1.2rem;
      border-radius: 8px;
      font-weight: 500;
      cursor: pointer;
    }
    .products {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
      gap: 1.5rem;
      margin-top: 1rem;
    }
    .product-card {
      background: #2a2c2b;
      border-radius: 12px;
      overflow: hidden;
      transition: transform 0.2s;
      text-decoration: none;
      color: inherit;
      position: relative;
    }
    .product-card:hover {
      transform: scale(1.02);
    }
    .product-card img {
      width: 100%;
      height: 220px;
      object-fit: cover;
    }
    .product-info {
      padding: 1rem;
    }
    .product-title {
      font-size: 1rem;
      font-weight: 500;
      margin-bottom: 0.5rem;
    }
    .product-price {
      font-weight: 600;
      color: #b4a7d6;
    }
    .add-cart {
      position: absolute;
      top: 10px;
      right: 10px;
      background: #b4a7d6;
      padding: 6px 10px;
      border-radius: 6px;
      font-size: 0.8rem;
      font-weight: bold;
      color: #2a2c2b;
      cursor: pointer;
    }
    footer {
      text-align: center;
      font-size: 0.9rem;
      color: #aaa;
      margin-top: 2rem;
    }
  </style>
</head>
<body>
<header>
  <div class="logo">ReWear</div>
  <div>
    <button class="history-button" onclick="location.href='history.php'">View History</button>
  </div>
</header>

<div class="banner"></div>

<div class="intro">
  <h1>Welcome, <?= htmlspecialchars($user['username']) ?>!</h1>
  <p>Swap your style, not your values. Recycle fashion, reduce waste, and reinvent your wardrobe today!</p>
</div>

<!-- Dashboard Info -->
<div class="section dashboard-info">
  <div class="info-box">
    <h3>Points Balance</h3>
    <p><?= $user['points'] ?> pts</p>
  </div>
  <div class="info-box">
    <h3>Uploaded Items</h3>
    <p><?= $uploaded_count ?></p>
  </div>
  <div class="info-box">
    <h3>Ongoing Swaps</h3>
    <p><?= $swaps_ongoing ?></p>
  </div>
  <div class="info-box">
    <h3>Completed Swaps</h3>
    <p><?= $swaps_completed ?></p>
  </div>
</div>

<!-- Shop by Category -->
<div class="section">
  <h2>Shop by Category</h2>
  <div class="categories">
    <div class="category" onclick="filterCategory('All')">All</div>
    <div class="category" onclick="filterCategory('Tops')">Tops</div>
    <div class="category" onclick="filterCategory('Bottoms')">Bottoms</div>
    <div class="category" onclick="filterCategory('Outerwear')">Outerwear</div>
    <div class="category" onclick="filterCategory('Accessories')">Accessories</div>
    <div class="category" onclick="filterCategory('Footwear')">Footwear</div>
    <div class="category" onclick="filterCategory('Vintage')">Vintage</div>
  </div>
</div>

<!-- All Products -->
<div class="section">
  <h2>All Products</h2>
  <div class="products" id="product-list">
    <?php
    // Manual image overrides (all .png now)
    $imageMap = array(
      "Denim Jacket" => "uploads/denim_jacket.png",
      "Canvas Bag" => "uploads/canvas_bag.png",
      "Recycled Sneakers" => "uploads/recycled_sneakers.png",
      "Vintage Denim Jacket" => "uploads/vintage_denim_jacket.png",
      "Boho Maxi Dress" => "uploads/boho_maxi_dress.png",
      "Canvas Backpack" => "uploads/canvas_backpack.png",
      "Eco Hoodie" => "uploads/eco_hoodie.png",
      "High-Waisted Jeans" => "uploads/high_waisted_jeans.png",
      "Wool Peacoat" => "uploads/wool_peacoat.png",
      "Chunky Knit Sweater" => "uploads/chunky_knit_sweater.png",
      "Retro Sneakers" => "uploads/retro_sneakers.png",
      "Corduroy Pants" => "uploads/corduroy_pants.png",
      "Leather Belt" => "uploads/leather_belt.png",
      "Vintage Blazer" => "uploads/vintage_blazer.png",
      "Blue Jacket" => "uploads/blue_jacket.png"
    );
    ?>

    <?php foreach ($products as $row): ?>
      <a href="product.php?id=<?= $row['id'] ?>" class="product-card" data-category="<?= htmlspecialchars($row['category']) ?>">
        <?php
          $title = $row['title'];
          $imgPath = $imageMap[$title] ?? htmlspecialchars($row['image_url']);
          if (!empty($imgPath) && file_exists($imgPath)) {
              echo "<img src=\"$imgPath\" alt=\"" . htmlspecialchars($title) . "\">";
          } else {
              echo "<img src='uploads/default.jpg' alt='No Image Available'>";
          }
        ?>
        <div class="add-cart">Add to Cart</div>
        <div class="product-info">
          <div class="product-title"><?= htmlspecialchars($title) ?></div>
          <div class="product-price">$<?= number_format($row['price'], 2) ?></div>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
</div>

<footer>
  &copy; 2025 ReWear. Sustainable fashion for everyone.
</footer>

<script>
function filterCategory(category) {
  const cards = document.querySelectorAll('.product-card');
  cards.forEach(card => {
    const cat = card.getAttribute('data-category');
    card.style.display = (category === 'All' || cat === category) ? 'block' : 'none';
  });
}
</script>

</body>
</html>
