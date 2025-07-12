<?php
session_start();

$loginError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $db = 'hackathonDB';

    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        die("❌ Connection failed: " . $conn->connect_error);
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM rewear_users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $_SESSION['username'] = $username;
        header("Location: screen3.html");
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
  <title>ReWear - Community Clothing Exchange</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    :root {
      --sage-green: #9caf88;
      --soft-beige: #f5f1eb;
      --pale-lavender: #e8e2e9;
      --eco-blue: #7fb3d3;
      --earth-brown: #8b6f47;
      --cream-white: #fdfcf8;
      --text-dark: #3d4a3b;
      --text-light: #6b7c68;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background: linear-gradient(135deg, var(--soft-beige) 0%, var(--pale-lavender) 50%, var(--eco-blue) 100%);
      font-family: 'Poppins', sans-serif;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
      overflow: hidden;
    }

    /* Subtle clothing icons background */
    .bg-pattern {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      opacity: 0.03;
      background-image: 
        url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23000000"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>'),
        url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23000000"><path d="M7 2l-4 4v14h18V6l-4-4H7zm5 18c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4z"/></svg>'),
        url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23000000"><path d="M12 4C9.79 4 8 5.79 8 8v1H7c-1.1 0-2 .9-2 2v8c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2v-8c0-1.1-.9-2-2-2h-1V8c0-2.21-1.79-4-4-4zm0 2c1.1 0 2 .9 2 2v1h-4V8c0-1.1.9-2 2-2z"/></svg>');
      background-repeat: repeat;
      background-size: 80px 80px;
      background-position: 0 0, 40px 40px, 20px 60px;
      animation: float 120s linear infinite;
    }

    @keyframes float {
      0% { background-position: 0 0, 40px 40px, 20px 60px; }
      100% { background-position: 400px 400px, 440px 440px, 420px 460px; }
    }

    /* Card entrance animation */
    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(50px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .login-container {
      background: var(--cream-white);
      padding: 3rem 2.5rem;
      border-radius: 24px;
      box-shadow: 
        0 20px 40px rgba(0, 0, 0, 0.08),
        0 0 0 1px rgba(255, 255, 255, 0.5);
      max-width: 420px;
      width: 100%;
      text-align: center;
      position: relative;
      z-index: 2;
      animation: slideUp 0.8s ease-out;
      backdrop-filter: blur(10px);
    }

    .brand-icon {
      width: 80px;
      height: 80px;
      margin: 0 auto 1.5rem;
      background: linear-gradient(135deg, var(--sage-green), var(--eco-blue));
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      box-shadow: 0 8px 24px rgba(156, 175, 136, 0.3);
    }

    .brand-icon::before {
      content: '👔';
      font-size: 2.5rem;
      filter: grayscale(100%) brightness(2);
    }

    .brand-title {
      font-family: 'Playfair Display', serif;
      font-size: 2.2rem;
      font-weight: 700;
      color: var(--text-dark);
      margin-bottom: 0.5rem;
      letter-spacing: -0.5px;
    }

    .brand-subtitle {
      font-size: 0.9rem;
      color: var(--text-light);
      margin-bottom: 0.3rem;
      font-weight: 400;
    }

    .tagline {
      font-size: 0.85rem;
      color: var(--sage-green);
      font-weight: 500;
      margin-bottom: 2rem;
      font-style: italic;
    }

    .form-group {
      margin-bottom: 1.5rem;
      position: relative;
    }

    .form-input {
      width: 100%;
      padding: 1rem 1.2rem;
      border: 2px solid transparent;
      border-radius: 16px;
      font-size: 1rem;
      font-family: 'Poppins', sans-serif;
      background: var(--soft-beige);
      color: var(--text-dark);
      transition: all 0.3s ease;
      outline: none;
    }

    .form-input::placeholder {
      color: var(--text-light);
      opacity: 0.8;
    }

    .form-input:focus {
      border-color: var(--sage-green);
      background: var(--cream-white);
      box-shadow: 0 0 0 4px rgba(156, 175, 136, 0.1);
      transform: translateY(-2px);
    }

    .form-input:hover {
      background: var(--cream-white);
      transform: translateY(-1px);
    }

    .login-button {
      width: 100%;
      padding: 1.1rem;
      background: linear-gradient(135deg, var(--sage-green), var(--eco-blue));
      color: white;
      border: none;
      border-radius: 16px;
      font-size: 1.1rem;
      font-weight: 600;
      font-family: 'Poppins', sans-serif;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-bottom: 1.5rem;
      box-shadow: 0 4px 16px rgba(156, 175, 136, 0.3);
      position: relative;
      overflow: hidden;
    }

    .login-button::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: left 0.5s ease;
    }

    .login-button:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(156, 175, 136, 0.4);
    }

    .login-button:hover::before {
      left: 100%;
    }

    .login-button:active {
      transform: translateY(0);
    }

    .signup-link {
      font-size: 0.9rem;
      color: var(--text-light);
    }

    .signup-link a {
      color: var(--sage-green);
      text-decoration: none;
      font-weight: 600;
      transition: color 0.3s ease;
    }

    .signup-link a:hover {
      color: var(--eco-blue);
      text-decoration: underline;
    }

    .error-message {
      background: rgba(220, 53, 69, 0.1);
      color: #dc3545;
      padding: 0.8rem 1rem;
      border-radius: 12px;
      font-size: 0.9rem;
      margin-bottom: 1.5rem;
      border-left: 4px solid #dc3545;
    }

    /* Responsive design */
    @media (max-width: 480px) {
      .login-container {
        margin: 1rem;
        padding: 2rem 1.5rem;
      }

      .brand-title {
        font-size: 1.8rem;
      }

      .brand-icon {
        width: 70px;
        height: 70px;
      }

      .brand-icon::before {
        font-size: 2rem;
      }
    }

    /* Floating particles effect */
    .particle {
      position: absolute;
      width: 4px;
      height: 4px;
      background: var(--sage-green);
      border-radius: 50%;
      opacity: 0.3;
      animation: rise 15s linear infinite;
    }

    @keyframes rise {
      0% {
        opacity: 0;
        transform: translateY(100vh) rotate(0deg);
      }
      10% {
        opacity: 0.3;
      }
      90% {
        opacity: 0.3;
      }
      100% {
        opacity: 0;
        transform: translateY(-100px) rotate(360deg);
      }
    }

    .particle:nth-child(1) { left: 10%; animation-delay: 0s; }
    .particle:nth-child(2) { left: 20%; animation-delay: 2s; }
    .particle:nth-child(3) { left: 30%; animation-delay: 4s; }
    .particle:nth-child(4) { left: 40%; animation-delay: 6s; }
    .particle:nth-child(5) { left: 50%; animation-delay: 8s; }
    .particle:nth-child(6) { left: 60%; animation-delay: 10s; }
    .particle:nth-child(7) { left: 70%; animation-delay: 12s; }
    .particle:nth-child(8) { left: 80%; animation-delay: 14s; }
  </style>
</head>
<body>
  <div class="bg-pattern"></div>
  
  <!-- Floating particles -->
  <div class="particle"></div>
  <div class="particle"></div>
  <div class="particle"></div>
  <div class="particle"></div>
  <div class="particle"></div>
  <div class="particle"></div>
  <div class="particle"></div>
  <div class="particle"></div>

  <div class="login-container">
    <div class="brand-icon"></div>
    <h1 class="brand-title">ReWear</h1>
    <p class="brand-subtitle">Community Clothing Exchange</p>
    <p class="tagline">Give your clothes a second life</p>

    <?php if ($loginError): ?>
      <div class="error-message">
        <?= htmlspecialchars($loginError) ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="form-group">
        <input type="text" name="username" class="form-input" placeholder="Username" required />
      </div>
      
      <div class="form-group">
        <input type="password" name="password" class="form-input" placeholder="Password" required />
      </div>
      
      <button type="submit" class="login-button">Sign In</button>
    </form>

    <p class="signup-link">
      New to ReWear? <a href="register.html">Join our community</a>
    </p>
  </div>
</body>
</html>