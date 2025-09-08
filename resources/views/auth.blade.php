<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Project</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 0;
      background: linear-gradient(135deg, #1b4459, #224abe);
      color: #fff;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .container-box {
      display: flex;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 15px;
      box-shadow: 0 4px 25px rgba(0,0,0,0.3);
      overflow: hidden;
      max-width: 1000px;
      width: 90%;
    }
    .left-side {
      flex: 1;
      padding: 3rem 2rem;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }
    .left-side h1 {
      font-size: 2.2rem;
      font-weight: bold;
      margin-bottom: 1rem;
    }
    .objectives {
      margin-top: 1.5rem;
      background: rgba(255,255,255,0.1);
      padding: 1rem 1.5rem;
      border-radius: 10px;
    }
    .objectives h5 {
      margin-bottom: 0.8rem;
      font-weight: 600;
    }
    .objectives ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    .objectives ul li {
      margin-bottom: 0.5rem;
      display: flex;
      align-items: center;
    }
    .objectives ul li span {
      margin-right: 0.5rem;
      font-size: 1.2rem;
    }
    .right-side {
      flex: 1;
      background: #fff;
      color: #000;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 2rem;
    }
    .login-box {
      width: 100%;
      max-width: 380px;
    }
    .login-box h3 {
      font-weight: 600;
      margin-bottom: 1.5rem;
    }
    .btn-custom {
      background: #224abe;
      border: none;
    }
    .btn-custom:hover {
      background: #1b3b91;
    }
  </style>
</head>
<body>
  <div class="container-box">
    <!-- Left side -->
    <div class="left-side">
      <h1>📊 Ticket Intelligence</h1>
      <p class="lead">
        Our project leverages AI to automatically classify, analyze, and improve
        ticket management efficiency.
      </p>
      <div class="objectives">
        <h5>🎯 Objectives</h5>
        <ul>
          <li><span>✅</span> Reduce manual categorization errors</li>
          <li><span>⚡</span> Provide real-time insights</li>
          <li><span>🚀</span> Enhance Service Desk productivity</li>
        </ul>
      </div>
    </div>

    <!-- Right side -->
    <div class="right-side">
      <div class="login-box">
        <h3 class="text-center">Sign In</h3>
        <form method="GET" action="{{route('index')}}">
            @csrf
          <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" placeholder="Enter email">
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" placeholder="Enter password">
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-custom text-white" >Sign in</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>
</html>




{{-- 

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Project Login</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      height: 100vh;
      display: flex;
      align-items: center;
      background: #f8f9fa;
    }
    .login-container {
      max-width: 1000px;
      width: 100%;
      height: 600px;
      box-shadow: 0px 8px 25px rgba(0, 0, 0, 0.1);
      border-radius: 15px;
      overflow: hidden;
      display: flex;
    }
    .left-panel {
      background: linear-gradient(135deg, #1b4459, #4e54c8);
      color: #fff;
      padding: 50px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      width: 50%;
    }
    .left-panel h1 {
      font-size: 2.5rem;
      font-weight: bold;
    }
    .left-panel p {
      margin-top: 20px;
      font-size: 1rem;
      line-height: 1.6;
      opacity: 0.9;
    }
    .right-panel {
      background: #fff;
      padding: 50px;
      width: 50%;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }
    .right-panel h2 {
      font-weight: bold;
      margin-bottom: 30px;
    }
    .form-control {
      margin-bottom: 20px;
      height: 50px;
      border-radius: 10px;
    }
    .btn-signin {
      height: 50px;
      border-radius: 10px;
      font-weight: 600;
    }
  </style>
</head>
<body>
  <div class="container d-flex justify-content-center">
    <div class="login-container">
      <!-- Left Side -->
      <div class="left-panel">
        <h1>ProjectX Dashboard</h1>
        <p>
          Welcome to <strong>ProjectX</strong>, an intelligent platform designed to 
          improve incident and request management. <br><br>
          🎯 <strong>Objective:</strong> Provide accurate ticket classification, 
          enhance resolution efficiency, and deliver insights for better decision-making. <br><br>
          🚀 Let's transform ITSM with automation and intelligence.
        </p>
      </div>

      <!-- Right Side -->
      <div class="right-panel">
        <h2>Sign In</h2>
        <form>
          <input type="email" class="form-control" placeholder="Email Address" required>
          <input type="password" class="form-control" placeholder="Password" required>
          <button type="submit" class="btn btn-primary w-100 btn-signin" >Sign In</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html> --}}
