# Security Policy

Security is a top priority for the **Faculty Subject Allocation System**. Because this application handles internal university data for the Computational Sciences Department, we take vulnerabilities seriously and expect all contributors to adhere to secure coding practices.

## Supported Versions

Currently, this project maintains a single main release branch. Security updates are applied directly to the active `main` branch. 

| Version | Supported          | Notes                                      |
| ------- | ------------------ | ------------------------------------------ |
| `main`  | :white_check_mark: | Active development & security patches      |
| `< 1.0` | :x:                | Older forks are not officially maintained  |

## Reporting a Vulnerability

**Please do not report security vulnerabilities through public GitHub issues.**

If you believe you have found a security vulnerability, please report it to us confidentially so we can investigate and patch it before it is disclosed publicly. 

1. **Email the maintainers** directly (or reach out to the Department of Computational Sciences administration).
2. **Provide details:** Include the type of vulnerability, the steps to reproduce it, and any potential impact.
3. **Response Time:** We strive to acknowledge all vulnerability reports within 48 hours and provide a timeline for a fix.

Once the issue is resolved, we will publish a security advisory and credit you for the responsible disclosure.

## Security Practices in Our Codebase

When contributing, ensure your code adheres to our baseline security standards:

1. **SQL Injection Prevention:** 
   - All database queries must be sanitized. While our current architecture utilizes `$conn->real_escape_string()`, any new major database operations should preferably use **Prepared Statements** via the `mysqli` extension.
2. **Cross-Site Scripting (XSS) Prevention:** 
   - Never trust user input. Any user-submitted data rendered in the browser (e.g., faculty names, subject selections) MUST be passed through `htmlspecialchars()` before outputting to the DOM.
3. **Authentication:** 
   - The HOD dashboard and Faculty form are protected by PHP sessions. Do not circumvent session checks.
   - Do not expose administrative logic or endpoints on unprotected routes.
4. **Environment Configuration:** 
   - Never commit sensitive database credentials to the repository. The `db.php` file acts as our configuration template; in production environments, ensure these credentials are kept secure and separate from version control.

By maintaining these standards, we ensure the integrity and privacy of Brainware University's administrative data.
