<?php
session_start();


require_once 'vendor/autoload.php';

define('GOOGLE_CLIENT_ID', '782187096910-0tgvb0b66mo88kk9nqgeut8q1b3kluo8.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', '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');
define('GOOGLE_REDIRECT_URI', 'http://localhost/hackathon_db/callback.php');

// Initialize Google Client
$client = new Google_Client();
$client->setClientId(GOOGLE_CLIENT_ID);
$client->setClientSecret(GOOGLE_CLIENT_SECRET);
$client->setRedirectUri(GOOGLE_REDIRECT_URI);
$client->addScope("email");
$client->addScope("profile");

$login_url = $client->createAuthUrl();

$loginError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $db = 'hackathon_db';

    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        die("❌ Connection failed: " . $conn->connect_error);
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM hackathon_users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        $loginError = "Invalid username or password";
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ReWear – Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;500&display=swap" rel="stylesheet">
  <style>
    :root {
      --dark-bg: #1e1f1f;
      --dark-card: #2a2c2b;
      --brand-green: #6b8e6e;
      --eco-blue: #66b2b2;
      --accent: #b4a7d6;
      --text-light: #f5f5f5;
      --input-bg: #3a3c3c;
    }

    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: var(--dark-bg);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      color: var(--text-light);
    }

    .card {
      background: var(--dark-card);
      padding: 2.5rem;
      border-radius: 16px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.4);
      max-width: 360px;
      width: 90%;
      text-align: center;
      animation: fadeIn 0.8s ease-in-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    h2 {
      font-family: 'Playfair Display', serif;
      font-size: 2rem;
      color: var(--eco-blue);
      margin-bottom: 0.5em;
    }

    .tagline {
      color: var(--accent);
      font-size: 1rem;
      margin-bottom: 1.5rem;
    }

    input[type="text"], input[type="password"] {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border-radius: 10px;
      border: 1px solid #444;
      background: var(--input-bg);
      color: var(--text-light);
      font-size: 1rem;
    }

    input:focus {
      outline: none;
      border-color: var(--eco-blue);
      box-shadow: 0 0 5px var(--eco-blue);
    }

    button[type="submit"], .google-btn {
      width: 50%;
      padding: 0.8rem;
      margin-top: 0.5rem;
      background: linear-gradient(90deg, var(--brand-green), var(--eco-blue));
      border: none;
      border-radius: 10px;
      color: white;
      font-weight: 600;
      font-size: 1rem;
      cursor: pointer;
      transition: background 0.3s ease, transform 0.2s ease;
      text-decoration: none;
      display: inline-block;
    }

    button[type="submit"]:hover, .google-btn:hover {
      background: linear-gradient(90deg, var(--eco-blue), var(--brand-green));
      transform: translateY(-2px);
    }

    .google-btn {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      width: 100%;
      padding: 0.8rem;
      margin-top: 0.5rem;
      background: transparent;
      border: 2px solid var(--eco-blue);
      border-radius: 10px;
      color: var(--eco-blue);
      font-weight: 600;
      font-size: 1rem;
      cursor: pointer;
      text-decoration: none;
      transition: background 0.3s ease, transform 0.2s ease, color 0.2s ease;
    }

    .google-btn:hover {
      background: var(--eco-blue);
      color: #fff;
      transform: translateY(-2px);
    }

    .google-btn .google-icon {
      height: 20px;
      width: 20px;
    }

    .link {
      margin-top: 1rem;
      font-size: 0.95rem;
    }

    .link a {
      color: var(--accent);
      text-decoration: underline;
    }

    .error {
      background: #441414;
      color: #ffbcbc;
      padding: 0.8em;
      border-radius: 8px;
      margin-bottom: 1em;
      font-size: 0.95rem;
    }
  </style>
  
</head>
<body>
  <div class="card">
    <h2>ReWear</h2>
    <div class="tagline">Login to refresh your wardrobe</div>

    <?php if (!empty($loginError)) echo "<div class='error'>{$loginError}</div>"; ?>

    <form method="POST" action="">
      <input type="text" name="username" placeholder="Username" required />
      <input type="password" name="password" placeholder="Password" required />
      <button type="submit">Login</button>
    </form>

    <a class="google-btn" href="<?= htmlspecialchars($login_url) ?>">
      <img class="google-icon" src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google logo">
      <span>Sign in with Google</span>
    </a>

    <div class="link">Don't have an account? <a href="register.php">Register</a></div>
  </div>
</body>
</html>
