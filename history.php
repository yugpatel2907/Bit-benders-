<?php
$conn = new mysqli("localhost", "root", "", "hackathon_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = 1; // Simulated logged-in user

$sql = "SELECT sr.id, sr.status, sr.created_at, p.title, p.image_url
        FROM swap_requests sr
        JOIN products p ON sr.product_id = p.id
        WHERE sr.requester_id = $user_id
        ORDER BY sr.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Swap History – ReWear</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;500&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
      background: #1e1f1f;
      color: #f5f5f5;
    }

    header {
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
      cursor: pointer;
    }

    .back-btn {
      background: #6b8e6e;
      color: #fff;
      border: none;
      padding: 8px 14px;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 600;
      transition: background 0.3s ease;
    }

    .back-btn:hover {
      background: #5a7d5a;
    }

    .container {
      padding: 2rem;
    }

    h1 {
      font-family: 'Playfair Display', serif;
      color: #b4a7d6;
      margin-bottom: 1.5rem;
      font-size: 2rem;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: #2a2c2b;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 0 10px rgba(0,0,0,0.3);
    }

    th, td {
      padding: 1rem;
      text-align: left;
    }

    th {
      background: #3e4c3a;
      color: #fff;
      font-weight: 600;
    }

    tr:nth-child(even) {
      background: #333533;
    }

    tr:hover {
      background: #444746;
    }

    .status {
      font-weight: bold;
      padding: 4px 8px;
      border-radius: 6px;
    }

    .status.pending { background: #f0ad4e; color: #2a2c2b; }
    .status.completed { background: #6b8e6e; color: #fff; }
    .status.rejected { background: #d9534f; color: #fff; }

    .product-img {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: 6px;
    }

    .no-records {
      text-align: center;
      margin-top: 2rem;
      color: #ccc;
    }
  </style>
</head>
<body>

<header>
  <div class="logo" onclick="location.href='dashboard.php'">ReWear</div>
  <button class="back-btn" onclick="location.href='dashboard.php'">← Back to Dashboard</button>
</header>

<div class="container">
  <h1>Your Swap History</h1>

  <?php if ($result->num_rows > 0): ?>
  <table>
    <tr>
      <th>Item</th>
      <th>Requested On</th>
      <th>Status</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td>
          <div style="display:flex; align-items:center; gap:1rem;">
            <img src="<?= $row['image_url'] ?? 'placeholder.jpg' ?>" alt="Item" class="product-img">
            <span><?= htmlspecialchars($row['title']) ?></span>
          </div>
        </td>
        <td><?= date('M d, Y – H:i', strtotime($row['created_at'])) ?></td>
        <td>
          <span class="status <?= strtolower($row['status']) ?>">
            <?= ucfirst($row['status']) ?>
          </span>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>
  <?php else: ?>
    <div class="no-records">You have not made any swap requests yet.</div>
  <?php endif; ?>
</div>

</body>
</html>
