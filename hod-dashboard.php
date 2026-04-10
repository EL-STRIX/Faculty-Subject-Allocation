<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOD Dashboard | Brainware University</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="hod-body">

<?php
session_start();
require_once 'db.php';

$HOD_PASSWORD = 'brainware';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hod_password'])) {
    if ($_POST['hod_password'] === $HOD_PASSWORD) {
        $_SESSION['hod_authenticated'] = true;
    } else {
        $error_msg = 'Incorrect password. Please try again.';
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: hod-dashboard.php');
    exit;
}

if (isset($_GET['delete']) && $_SESSION['hod_authenticated']) {
    $del_id = intval($_GET['delete']);
    $conn->query("DELETE FROM faculty_submissions WHERE id = $del_id");
    header('Location: hod-dashboard.php');
    exit;
}

if (!isset($_SESSION['hod_authenticated'])) {
?>

    <div class="login-overlay">
        <div class="login-box">
            <div class="login-logo">
                <div class="logo-circle large">BU</div>
            </div>
            <h2>HOD Panel Access</h2>
            <p class="login-sub">Brainware University — Computational Sciences</p>

            <?php if ($error_msg): ?>
            <div class="alert alert-error small">
                <span>❌</span> <?= htmlspecialchars($error_msg) ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="hod-dashboard.php">
                <div class="form-group">
                    <label for="hod_password">
                        <span class="label-icon">🔐</span> HOD Password
                    </label>
                    <input type="password" id="hod_password" name="hod_password"
                        placeholder="Enter HOD password" required autofocus>
                </div>
                <button type="submit" class="btn-submit full-width">
                    <span class="btn-icon">🔓</span> Access Dashboard
                </button>
            </form>
            <a href="landing.php" class="back-link">← Back to Login</a>
        </div>
    </div>

<?php } else {

    $result = $conn->query("SELECT * FROM faculty_submissions ORDER BY submitted_at DESC");
    $rows = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    }
    $total = count($rows);

    $subject_count = [];
    foreach ($rows as $r) {
        for ($i = 1; $i <= 4; $i++) {
            $s = $r["subject_$i"];
            $subject_count[$s] = ($subject_count[$s] ?? 0) + 1;
        }
    }
    arsort($subject_count);
    $top_subject = $total > 0 ? array_key_first($subject_count) : 'N/A';
?>

    <!-- Screen-only Header -->
    <header class="site-header no-print">
        <div class="header-inner">
            <div class="logo-area">
                <div class="logo-circle">BU</div>
                <div class="logo-text">
                    <span class="uni-name">Brainware University</span>
                    <span class="dept-name">HOD Dashboard — Computational Sciences</span>
                </div>
            </div>
            <div class="header-actions">
                <a href="index.php" class="hod-link">← Faculty Form</a>
                <a href="hod-dashboard.php?logout=1" class="btn-logout">Logout</a>
            </div>
        </div>
    </header>

    <div class="hero-banner no-print">
        <div class="hero-content">
            <h1>HOD Dashboard</h1>
            <p>Faculty Subject Allocation — Academic Year 2025–2026</p>
        </div>
    </div>

    <!-- Print-only Header -->
    <div class="print-header print-only">
        <div class="print-header-top">
            <div class="print-logo-circle">BU</div>
            <div class="print-header-text">
                <h1>Brainware University</h1>
                <h2>Department of Computational Sciences</h2>
                <p>Faculty Subject Allocation — Academic Year 2025–2026</p>
            </div>
        </div>
        <div class="print-header-meta">
            <span><strong>Printed:</strong> <?= date('d M Y, h:i A') ?></span>
            <span><strong>Total Faculty:</strong> <?= $total ?></span>
            <span><strong>Subjects Covered:</strong> <?= count($subject_count) ?></span>
            <span><strong>Top Subject:</strong> <?= htmlspecialchars($top_subject) ?></span>
        </div>
        <div class="print-divider"></div>
    </div>

    <main class="main-container hod-main">

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon no-print">👨‍🏫</div>
                <div class="stat-info">
                    <span class="stat-number"><?= $total ?></span>
                    <span class="stat-label">Total Submissions</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon no-print">📚</div>
                <div class="stat-info">
                    <span class="stat-number"><?= count($subject_count) ?></span>
                    <span class="stat-label">Subjects Covered</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon no-print">🏆</div>
                <div class="stat-info">
                    <span class="stat-number top-sub"><?= htmlspecialchars($top_subject) ?></span>
                    <span class="stat-label">Most Selected Subject</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon no-print">🕐</div>
                <div class="stat-info">
                    <span class="stat-number small-stat">
                        <?= $total > 0 ? date('d M Y', strtotime($rows[0]['submitted_at'])) : 'N/A' ?>
                    </span>
                    <span class="stat-label">Last Submission</span>
                </div>
            </div>
        </div>

        <!-- Toolbar (screen only) -->
        <div class="dashboard-toolbar no-print">
            <div class="search-wrap">
                <span class="search-icon">🔍</span>
                <input type="text" id="searchInput"
                    placeholder="Search by faculty name or subject..."
                    onkeyup="filterTable()">
            </div>
            <div class="export-buttons">
                <a href="export-excel.php" class="btn-export csv">📥 Export Excel</a>
                <button onclick="window.print()" class="btn-export print">🖨️ Print</button>
            </div>
        </div>

        <!-- Data Table -->
        <div class="table-card">
            <?php if ($total === 0): ?>
            <div class="empty-state">
                <div class="empty-icon">📭</div>
                <h3>No Submissions Yet</h3>
                <p>Faculty members haven't submitted their subject allocations yet.</p>
                <a href="index.php" class="btn-submit" style="display:inline-block;margin-top:16px;">Go to Faculty Form</a>
            </div>
            <?php else: ?>
            <div class="table-scroll-wrap">
                <table id="submissionsTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Faculty Name</th>
                            <th>Subject 1</th>
                            <th>Subject 2</th>
                            <th>Subject 3</th>
                            <th>Subject 4</th>
                            <th>Submitted At</th>
                            <th class="no-print">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $index => $row): ?>
                        <tr>
                            <td class="row-num"><?= $total - $index ?></td>
                            <td class="faculty-name-cell">
                                <div class="faculty-avatar no-print"><?= strtoupper(substr($row['faculty_name'], 0, 1)) ?></div>
                                <?= htmlspecialchars($row['faculty_name']) ?>
                            </td>
                            <td><span class="subject-tag"><?= htmlspecialchars($row['subject_1']) ?></span></td>
                            <td><span class="subject-tag"><?= htmlspecialchars($row['subject_2']) ?></span></td>
                            <td><span class="subject-tag"><?= htmlspecialchars($row['subject_3']) ?></span></td>
                            <td><span class="subject-tag"><?= htmlspecialchars($row['subject_4']) ?></span></td>
                            <td class="date-cell"><?= date('d M Y, h:i A', strtotime($row['submitted_at'])) ?></td>
                            <td class="no-print">
                                <a href="hod-dashboard.php?delete=<?= $row['id'] ?>"
                                    class="btn-delete"
                                    onclick="return confirm('Delete submission from <?= addslashes(htmlspecialchars($row['faculty_name'])) ?>?')"
                                >🗑️ Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="table-footer no-print">
                Showing <strong id="visibleCount"><?= $total ?></strong> of <strong><?= $total ?></strong> records
            </div>
            <?php endif; ?>
        </div>

        <!-- Subject Summary (print only) -->
        <?php if ($total > 0 && !empty($subject_count)): ?>
        <div class="subject-summary-section print-only">
            <h3 class="summary-heading">Subject Allocation Summary</h3>
            <table class="summary-table">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Subject Name</th>
                        <th>Faculty Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $rank = 1; foreach ($subject_count as $subj => $cnt): ?>
                    <tr>
                        <td><?= $rank++ ?></td>
                        <td><?= htmlspecialchars($subj) ?></td>
                        <td><?= $cnt ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

    </main>

    <footer class="site-footer no-print">
        <p>© 2025 Brainware University &nbsp;|&nbsp; HOD Panel — Computational Sciences &nbsp;|&nbsp; Subject Allocation System</p>
        <p style="margin-top:6px;font-size:12px;opacity:0.7;">Developed by <strong>Sujay Paul</strong></p>
    </footer>

    <div class="print-footer print-only">
        <div class="print-divider"></div>
        <p>© 2025 Brainware University — Department of Computational Sciences — Faculty Subject Allocation System</p>
        <p>This document is system-generated and intended for internal administrative use only.</p>
    </div>

    <script src="assets/dashboard.js"></script>

<?php } ?>

</body>
</html>
