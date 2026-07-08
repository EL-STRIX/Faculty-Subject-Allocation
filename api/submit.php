<?php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

// Sanitize inputs
$faculty_name = trim($conn->real_escape_string($_POST['faculty_name'] ?? ''));
$subject_1    = trim($conn->real_escape_string($_POST['subject_1'] ?? ''));
$subject_2    = trim($conn->real_escape_string($_POST['subject_2'] ?? ''));
$subject_3    = trim($conn->real_escape_string($_POST['subject_3'] ?? ''));
$subject_4    = trim($conn->real_escape_string($_POST['subject_4'] ?? ''));

// Validation: empty fields
if (!$faculty_name || !$subject_1 || !$subject_2 || !$subject_3 || !$subject_4) {
    header('Location: ../index.php?error=All+fields+are+required.');
    exit;
}

// Validation: faculty name (letters and spaces only)
if (!preg_match("/^[a-zA-Z\s\.]+$/", $faculty_name)) {
    header('Location: ../index.php?error=Invalid+faculty+name.+Only+letters+and+spaces+allowed.');
    exit;
}

// Validation: no duplicate subjects
$subjects = [$subject_1, $subject_2, $subject_3, $subject_4];
if (count($subjects) !== count(array_unique($subjects))) {
    header('Location: ../index.php?error=Duplicate+subjects+selected.+Each+subject+must+be+unique.');
    exit;
}

// Validation: valid subject values
$allowed_subjects = [
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
foreach ($subjects as $s) {
    if (!in_array($s, $allowed_subjects)) {
        header('Location: ../index.php?error=Invalid+subject+selected.');
        exit;
    }
}

// Check if faculty already submitted
$check = $conn->query("SELECT id FROM faculty_submissions WHERE LOWER(faculty_name) = LOWER('$faculty_name') LIMIT 1");
if ($check && $check->num_rows > 0) {
    header('Location: ../index.php?error=A+submission+from+this+faculty+name+already+exists.+Please+contact+HOD+to+update.');
    exit;
}

// Insert into database
$sql = "INSERT INTO faculty_submissions (faculty_name, subject_1, subject_2, subject_3, subject_4)
        VALUES ('$faculty_name', '$subject_1', '$subject_2', '$subject_3', '$subject_4')";

if ($conn->query($sql)) {
    $name_encoded = urlencode($faculty_name);
    header("Location: ../index.php?success=1&name=$name_encoded");
} else {
    header('Location: ../index.php?error=Database+error.+Please+try+again.');
}

$conn->close();
exit;
?>
