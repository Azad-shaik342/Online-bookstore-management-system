# Build verification and remaining actions

## Verified in the build environment
- PHP syntax lint: all included PHP files pass `php -l`.
- Project archive and database SQL file are present.
- The ZIP is prepared for local XAMPP setup.

## Not verified here
- This environment has no MySQL client/server and its PHP CLI does not include the mysqli extension.
- Database import, live HTTP execution, customer/admin browser workflows, SMTP, and public hosting were not testable here.
- Therefore this package is not described as a fully runtime-tested or deployed website.

## Before submission
1. Install XAMPP with PHP 8.1+ and MySQL/MariaDB.
2. Confirm PHP extensions `mysqli`, `fileinfo`, and `mbstring` are enabled.
3. Import `database.sql` in phpMyAdmin.
4. Copy `config/database.local.example.php` to `config/database.local.php` and set credentials.
5. Set `BASE_URL` in `config/app.php` to the exact folder URL.
6. Run `php setup_admin.php your-email@example.com "A-Strong-Unique-Password" "Your Admin Name"` from the project folder.
7. Complete every item in README.md's QA checklist and take genuine browser screenshots.
8. Replace deployment placeholders in the presentation script only after GitHub and hosting are actually complete.

## Scope notes
- Checkout records demo orders; it does not process real payments.
- Email OTP is not implemented.
- Password reset delivery depends on a configured PHP mail transport and is not guaranteed by this package.
