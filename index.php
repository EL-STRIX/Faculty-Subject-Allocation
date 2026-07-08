<?php
session_start();
if (!isset($_SESSION['faculty_authenticated'])) {
    header('Location: landing.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Subject Allocation | Brainware University</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- Header -->
    <header class="site-header">
        <div class="header-inner">
            <div class="logo-area">
                <div class="logo-circle">BU</div>
                <div class="logo-text">
                    <span class="uni-name">Brainware University</span>
                    <span class="dept-name">Department of Computational Sciences</span>
                </div>
            </div>
            <a href="hod-dashboard.php" class="hod-link">HOD Panel →</a>
            <a href="landing.php?logout=1" class="hod-link" style="margin-left:8px;opacity:0.7;">Logout</a>
        </div>
    </header>

    <!-- Hero Banner -->
    <div class="hero-banner">
        <div class="hero-content">
            <h1>Faculty Subject Allocation</h1>
            <p>Academic Year 2025 – 2026 &nbsp;|&nbsp; Computational Sciences</p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-container">

        <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <span class="alert-icon">✅</span>
            <div>
                <strong>Submitted Successfully!</strong>
                <p>Your subject allocation has been recorded. Thank you, <?= htmlspecialchars($_GET['name']) ?>.</p>
            </div>
        </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-error">
            <span class="alert-icon">❌</span>
            <div>
                <strong>Submission Failed</strong>
                <p><?= htmlspecialchars($_GET['error']) ?></p>
            </div>
        </div>
        <?php endif; ?>

        <div class="form-card">
            <div class="form-header">
                <h2>Subject Selection Form</h2>
                <p>Please select 4 unique subjects you will be teaching this semester.</p>
            </div>

            <form action="api/submit.php" method="POST" id="allocationForm">

                <!-- Faculty Name -->
                <div class="form-group">
                    <label for="faculty_name">
                        <span class="label-icon">👤</span> Faculty Full Name
                        <span class="required">*</span>
                    </label>
                    <input
                        type="text"
                        id="faculty_name"
                        name="faculty_name"
                        placeholder="e.g. Dr. Rajesh Kumar"
                        required
                        autocomplete="off"
                    >
                </div>

                <!-- Divider -->
                <div class="section-divider">
                    <span>Select 4 Subjects</span>
                </div>

                <!-- Progress Bar -->
                <div class="progress-wrap">
                    <div class="progress-label">
                        <span>Subjects Selected</span>
                        <span id="progressCount">0 / 4</span>
                    </div>
                    <div class="progress-bar-bg">
                        <div class="progress-bar-fill" id="progressBar" style="width:0%"></div>
                    </div>
                </div>

                <!-- Subject Dropdowns -->
                <?php
                $subjects = [
                    "Data Structure and Algorithm",
                    "Python Lab",
                    "Artificial Intelligence",
                    "Internet and Web Technologies",
                    "Database Management System",
                    "E-Commerce Technology",
                    "Data Science",
                    "Natural Language Processing",
                    "Cloud Computing",
                    "Android Application Development",
                    "Advance Web Development",
                    "Blockchain Technology",
                    "IoT",
                    "Software Engineering",
                    "Design and Analysis of Algorithm",
                    "Full-stack Development -II",
                    "Machine Learning",
                    "MongoDB Lab",
                    "Computer Network",
                    "Research Methodologies",
                    "Distributed Database",
                    "Client Server Computing",
                    "Formal Language and Automata Theory",
                    "Image Processing",
                    "Digital Logic",
                    "AI Foundations & Generative AI Literacy",
                    "PC Software",
                    "Problem Solving using Python",
                    "Interactive Web Designing",
                    "Computer Organization and Operating System",
                    "AI and its Applications",
                    "Problem-Solving Methodologies"
                ];
                $subjects = array_unique($subjects);
                sort($subjects);
                ?>

                <div class="subjects-grid">
                    <?php for ($i = 1; $i <= 4; $i++): ?>
                    <div class="form-group subject-group">
                        <label for="subject_<?= $i ?>">
                            <span class="subject-badge"><?= $i ?></span> Subject <?= $i ?>
                            <span class="required">*</span>
                        </label>
                        <select
                            id="subject_<?= $i ?>"
                            name="subject_<?= $i ?>"
                            class="subject-select"
                            required
                            onchange="updateDropdowns()"
                        >
                            <option value="">-- Select a Subject --</option>
                            <?php foreach ($subjects as $subject): ?>
                            <option value="<?= htmlspecialchars($subject) ?>"><?= htmlspecialchars($subject) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endfor; ?>
                </div>

                <!-- Submit -->
                <div class="submit-area">
                    <button type="submit" class="btn-submit" id="submitBtn">
                        <span class="btn-icon">📤</span>
                        Submit Allocation
                    </button>
                    <p class="submit-note">Once submitted, your entry will be saved to the HOD's records.</p>
                </div>

            </form>
        </div>

        <!-- Info Cards -->
        <div class="info-cards">
            <div class="info-card">
                <div class="info-icon">📚</div>
                <h4>32 Subjects Available</h4>
                <p>Choose any 4 from the Computational Sciences subject pool.</p>
            </div>
            <div class="info-card">
                <div class="info-icon">🔒</div>
                <h4>No Duplicates Allowed</h4>
                <p>Each subject can only be selected once per faculty member.</p>
            </div>
            <div class="info-card">
                <div class="info-icon">📊</div>
                <h4>HOD Dashboard</h4>
                <p>All submissions are instantly visible to the Head of Department.</p>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="site-footer">
        <p>© 2025 Brainware University &nbsp;|&nbsp; Department of Computational Sciences &nbsp;|&nbsp; Faculty Subject Allocation System</p>
        <p style="margin-top:6px;font-size:12px;opacity:0.7;">Developed by <strong>Sujay Paul</strong></p>
    </footer>

    <script src="assets/js/form.js"></script>
</body>
</html>
