// ============================================
//  BRAINWARE UNIVERSITY — Faculty Form JS
// ============================================

const allSubjects = [
    "AI and its Applications",
    "AI Foundations & Generative AI Literacy",
    "Advance Web Development",
    "Android Application Development",
    "Artificial Intelligence",
    "Blockchain Technology",
    "Client Server Computing",
    "Cloud Computing",
    "Computer Network",
    "Computer Organization and Operating System",
    "Data Science",
    "Data Structure and Algorithm",
    "Database Management System",
    "Design and Analysis of Algorithm",
    "Digital Logic",
    "Distributed Database",
    "E-Commerce Technology",
    "Formal Language and Automata Theory",
    "Full-stack Development -II",
    "Image Processing",
    "Interactive Web Designing",
    "Internet and Web Technologies",
    "IoT",
    "Machine Learning",
    "MongoDB Lab",
    "Natural Language Processing",
    "PC Software",
    "Problem Solving using Python",
    "Problem-Solving Methodologies",
    "Python Lab",
    "Research Methodologies",
    "Software Engineering"
];

function getSelectedValues() {
    const values = [];
    for (let i = 1; i <= 4; i++) {
        const val = document.getElementById('subject_' + i)?.value;
        if (val) values.push(val);
    }
    return values;
}

function updateDropdowns() {
    const selected = getSelectedValues();

    for (let i = 1; i <= 4; i++) {
        const sel = document.getElementById('subject_' + i);
        if (!sel) continue;

        const currentVal = sel.value;

        // Rebuild options
        sel.innerHTML = '<option value="">-- Select a Subject --</option>';

        allSubjects.forEach(subject => {
            // Show if: not selected by another dropdown, or it's this dropdown's own current value
            const selectedByOther = selected.filter((v, idx) => {
                const dropIndex = idx + 1;
                return dropIndex !== i && v === subject;
            });

            if (selectedByOther.length === 0 || subject === currentVal) {
                const opt = document.createElement('option');
                opt.value = subject;
                opt.textContent = subject;
                if (subject === currentVal) opt.selected = true;
                sel.appendChild(opt);
            }
        });
    }

    updateProgress();
}

function updateProgress() {
    const selected = getSelectedValues().filter(v => v !== '');
    const count    = selected.length;
    const bar      = document.getElementById('progressBar');
    const label    = document.getElementById('progressCount');

    if (bar)   bar.style.width = (count / 4 * 100) + '%';
    if (label) label.textContent = count + ' / 4';
}

// Auto-dismiss alerts after 6 seconds
document.addEventListener('DOMContentLoaded', () => {
    updateDropdowns();

    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(a => {
            a.style.transition = 'opacity .5s, max-height .5s';
            a.style.opacity = '0';
            a.style.maxHeight = '0';
            a.style.overflow = 'hidden';
            setTimeout(() => a.remove(), 500);
        });
    }, 6000);

    // Form validation before submit
    const form = document.getElementById('allocationForm');
    if (form) {
        form.addEventListener('submit', function (e) {
            const name = document.getElementById('faculty_name').value.trim();
            if (!name) {
                e.preventDefault();
                alert('Please enter your full name.');
                return;
            }
            for (let i = 1; i <= 4; i++) {
                const val = document.getElementById('subject_' + i)?.value;
                if (!val) {
                    e.preventDefault();
                    alert('Please select Subject ' + i + '.');
                    return;
                }
            }

            // Disable submit button to prevent double-submit
            const btn = document.getElementById('submitBtn');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span class="btn-icon">⏳</span> Submitting...';
                btn.style.opacity = '0.7';
            }
        });
    }
});
