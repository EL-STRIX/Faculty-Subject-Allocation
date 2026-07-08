# Contributing to the Faculty Subject Allocation System

First off, thank you for considering contributing to the **Faculty Subject Allocation System**! It's people like you who help make this tool robust, secure, and user-friendly for the Computational Sciences Department at Brainware University.

Whether you're fixing a bug, proposing a feature, improving documentation, or optimizing our codebase, your help is genuinely appreciated. This document outlines the process and standards we use to keep this project maintainable and production-ready.

---

## 🧠 Understanding the Architecture

Before diving into code, please familiarize yourself with our stack:
- **Frontend:** Vanilla HTML5, CSS3, and ES6+ JavaScript. We intentionally avoid heavy frameworks (like React or Tailwind) to keep the project lightweight and dependency-free.
- **Backend:** PHP 7.4+. We use native PHP sessions for authentication.
- **Database:** MySQL / MariaDB using the `mysqli` extension.

---

## 🛠️ Local Development Setup

To contribute effectively, set up your local environment:

1. **Prerequisites:** Ensure you have an AMP stack installed (XAMPP, MAMP, or LAMP). You will need PHP 7.4+ and a MySQL server running.
2. **Fork & Clone:** Fork the repository to your GitHub account, then clone it to your local web server's document root (e.g., `C:\xampp\htdocs\brainware-faculty`).
3. **Database Configuration:** The application auto-provisions its database. Simply ensure `config/db.php` has the correct local credentials (default is `root` with no password).
4. **Access the App:** Open `http://localhost/brainware-faculty/` in your browser. The database (`brainware_faculty`) will be created automatically.

---

## 📝 Coding Standards

To maintain a clean and readable codebase, we ask that all contributors adhere to the following standards:

### PHP (Backend)
- Follow **PSR-12** coding standards.
- **Security First:** Always use `real_escape_string()` or prepared statements when interacting with the database to prevent SQL Injection.
- **Output Encoding:** Always use `htmlspecialchars()` when outputting user-submitted data to prevent Cross-Site Scripting (XSS).
- Keep logic modular. Form processing (`api/submit.php`), database connections (`config/db.php`), and UI presentation should remain logically separated.

### CSS & JavaScript (Frontend)
- Use standard semantic HTML.
- Write clean, vanilla CSS. Keep styling confined to `assets/css/style.css` using consistent class naming conventions (BEM-inspired).
- Write modular ES6+ JavaScript. Ensure client-side logic (e.g., `form.js`) does not throw console errors.

---

## 🌿 Branching & Committing

We follow a structured Git workflow to ensure a clean commit history.

### Branch Naming
Create a dedicated branch for your work. Use descriptive prefixes:
- `feat/add-new-export-format`
- `fix/dropdown-validation-bug`
- `docs/update-readme-instructions`

### Commit Messages
We strictly follow [Conventional Commits](https://www.conventionalcommits.org/). This makes our history readable and allows for automated changelog generation.
- **`feat:`** A new feature
- **`fix:`** A bug fix
- **`docs:`** Documentation only changes
- **`style:`** Changes that do not affect the meaning of the code (whitespace, formatting)
- **`refactor:`** A code change that neither fixes a bug nor adds a feature
- **`chore:`** Updating build tasks, package manager configs, etc.

*Example:* `feat: add support for exporting allocations to PDF`

---

## 🔄 Pull Request Workflow

1. **Sync with Main:** Before creating a PR, ensure your branch is up to date with the upstream `main` branch.
2. **Test Your Changes:** Verify that your changes do not break existing functionality. Test the faculty submission flow, the HOD dashboard, and the Excel export.
3. **Open the PR:** Submit your Pull Request against the `main` branch. Provide a clear description of the problem you are solving and the approach you took.
4. **Review Process:** A maintainer will review your code. We may request changes to ensure architectural consistency. Please be open to feedback—we're all working together to build great software!

---

## 🐞 Reporting Bugs & Requesting Features

If you aren't writing code but have found an issue or have an idea:
- Check existing issues to avoid duplicates.
- Open a new issue and use a descriptive title.
- For bugs: Include steps to reproduce, expected behavior, actual behavior, and your environment details (PHP version, browser).
- For features: Clearly explain the use case and how it benefits the department.

Thank you for contributing! Your efforts help power the academic administration of our university.
