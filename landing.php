<?php
session_start();

// Handle faculty logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: landing.php');
    exit;
}

// Handle faculty login — must be BEFORE any HTML output
$faculty_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['faculty_password'])) {
    if ($_POST['faculty_password'] === 'brainware') {
        $_SESSION['faculty_authenticated'] = true;
        header('Location: index.php');
        exit;
    } else {
        $faculty_error = 'Incorrect password. Please try again.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Brainware University</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background: var(--gray-50);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .landing-main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 24px;
        }

        .landing-wrapper {
            width: 100%;
            max-width: 820px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 32px;
        }

        .section-heading {
            text-align: center;
        }
        .section-heading h2 {
            font-size: 20px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 6px;
        }
        .section-heading p {
            font-size: 14px;
            color: var(--gray-600);
        }

        .role-cards {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            width: 100%;
        }

        @media (max-width: 580px) {
            .role-cards { grid-template-columns: 1fr; }
        }

        .role-card {
            background: #ffffff;
            border: 2px solid var(--gray-200);
            border-radius: 16px;
            padding: 36px 28px 28px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.22s ease;
            box-shadow: var(--shadow-sm);
        }

        .role-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .role-card.faculty:hover { border-color: var(--green); }
        .role-card.hod:hover     { border-color: #2563eb; }

        .role-icon {
            font-size: 46px;
            line-height: 1;
            margin-bottom: 4px;
        }

        .role-title {
            color: var(--navy);
            font-size: 20px;
            font-weight: 700;
            margin: 0;
        }

        .role-desc {
            color: var(--gray-600);
            font-size: 13px;
            text-align: center;
            margin: 0;
            line-height: 1.6;
        }

        .role-badge {
            margin-top: 10px;
            padding: 5px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .role-card.faculty .role-badge {
            background: rgba(16,185,129,0.12);
            color: #059669;
            border: 1px solid rgba(16,185,129,0.35);
        }

        .role-card.hod .role-badge {
            background: rgba(37,99,235,0.10);
            color: #1d4ed8;
            border: 1px solid rgba(37,99,235,0.3);
        }

        .login-panel {
            display: none;
            width: 100%;
            max-width: 440px;
            animation: slideUp 0.3s ease;
        }

        .login-panel.active { display: block; }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .login-panel-box {
            background: #ffffff;
            border: 1.5px solid var(--gray-200);
            border-radius: 16px;
            padding: 36px 32px;
            box-shadow: var(--shadow-md);
        }

        .panel-back {
            background: none;
            border: none;
            color: var(--gray-600);
            font-size: 13px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 24px;
            padding: 0;
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            transition: color 0.2s;
        }
        .panel-back:hover { color: var(--navy); }

        .panel-icon  { font-size: 36px; text-align: center; margin-bottom: 6px; }
        .panel-title {
            color: var(--navy);
            font-size: 22px;
            font-weight: 700;
            margin: 0 0 4px;
            text-align: center;
        }
        .panel-sub {
            color: var(--gray-600);
            font-size: 13px;
            text-align: center;
            margin: 0 0 24px;
            line-height: 1.5;
        }

        .landing-footer-bar {
            background: var(--navy);
            padding: 18px 24px;
            text-align: center;
        }
        .landing-footer-bar p {
            color: #94a3b8;
            font-size: 12px;
            margin: 0;
            line-height: 1.9;
        }
        .landing-footer-bar .dev-credit {
            color: #fcd34d;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <!-- Header — same as faculty & HOD pages -->
    <header class="site-header">
        <div class="header-inner">
            <div class="logo-area">
                <div class="logo-circle">BU</div>
                <div class="logo-text">
                    <span class="uni-name">Brainware University</span>
                    <span class="dept-name">Department of Computational Sciences</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Banner — same as faculty & HOD pages -->
    <div class="hero-banner">
        <div class="hero-content">
            <h1>Subject Allocation System</h1>
            <p>Academic Year 2025 – 2026 &nbsp;|&nbsp; Select your role to continue</p>
        </div>
    </div>

    <main class="landing-main">
        <div class="landing-wrapper">

            <div class="section-heading">
                <h2>Welcome — Please select your role</h2>
                <p>Faculty members can submit subject allocations. HOD can view and manage all submissions.</p>
            </div>

            <!-- Role selector -->
            <div class="role-cards" id="roleSelector">
                <div class="role-card faculty" onclick="showPanel('faculty')">
                    <div class="role-icon">👨‍🏫</div>
                    <h3 class="role-title">Faculty</h3>
                    <p class="role-desc">Submit your subject allocation for the current semester.</p>
                    <span class="role-badge">Faculty Login</span>
                </div>
                <div class="role-card hod" onclick="window.location.href='hod-dashboard.php'">
                    <div class="role-icon">🎓</div>
                    <h3 class="role-title">HOD</h3>
                    <p class="role-desc">Access the dashboard to view and manage all faculty submissions.</p>
                    <span class="role-badge">HOD Panel</span>
                </div>
            </div>

            <!-- Faculty Login Panel -->
            <div class="login-panel <?= $faculty_error ? 'active' : '' ?>" id="facultyPanel">
                <div class="login-panel-box">
                    <button class="panel-back" onclick="showSelector()">← Back to Role Selection</button>
                    <div class="panel-icon">👨‍🏫</div>
                    <h2 class="panel-title">Faculty Login</h2>
                    <p class="panel-sub">Enter your faculty password to access the subject allocation form.</p>

                    <?php if ($faculty_error): ?>
                    <div class="alert alert-error small">
                        <span class="alert-icon">❌</span>
                        <div><?= htmlspecialchars($faculty_error) ?></div>
                    </div>
                    <?php endif; ?>

                    <form method="POST" action="landing.php">
                        <div class="form-group">
                            <label for="faculty_password">
                                <span class="label-icon">🔐</span> Faculty Password
                            </label>
                            <input type="password" id="faculty_password" name="faculty_password"
                                placeholder="Enter faculty password" required autofocus>
                        </div>
                        <button type="submit" class="btn-submit full-width">
                            <span class="btn-icon">🔓</span> Access Faculty Form
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="landing-footer-bar">
        <p>© 2025 Brainware University &nbsp;|&nbsp; Department of Computational Sciences &nbsp;|&nbsp; Faculty Subject Allocation System</p>
        <p>Developed by <span class="dev-credit">Sujay Paul</span></p>
    </footer>

<script>
    function showPanel(role) {
        document.getElementById('roleSelector').style.display = 'none';
        if (role === 'faculty') {
            const p = document.getElementById('facultyPanel');
            p.classList.add('active');
        }
    }

    function showSelector() {
        document.getElementById('roleSelector').style.display = 'grid';
        document.getElementById('facultyPanel').classList.remove('active');
    }

    <?php if ($faculty_error): ?>
    document.getElementById('roleSelector').style.display = 'none';
    <?php endif; ?>
</script>

</body>
</html>
