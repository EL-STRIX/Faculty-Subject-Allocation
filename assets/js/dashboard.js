// ============================================
//  BRAINWARE UNIVERSITY — HOD Dashboard JS
// ============================================

function filterTable() {
    const input  = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const rows   = document.querySelectorAll('#submissionsTable tbody tr');
    let visible  = 0;

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(filter)) {
            row.style.display = '';
            visible++;
        } else {
            row.style.display = 'none';
        }
    });

    const countEl = document.getElementById('visibleCount');
    if (countEl) countEl.textContent = visible;
}
