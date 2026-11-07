# PageTurn Online Bookstore — 12-minute capstone presentation
**Recording note:** Use the real running site in your browser for the live-demo sections. Do not narrate features that have not passed your local tests. Replace the bracketed items with your own name, internship details, GitHub URL and deployed URL.

## 0:00–1:00 — Introduction
Hello everyone. My name is [Your Name], and this is my ApexPlanet capstone project: PageTurn, an Online Bookstore Management System. The goal of this project is to create a practical web application where customers can discover books, maintain a shopping bag and place orders, while an administrator can manage the catalog and track order progress.

I developed the application using PHP for server-side logic, MySQL for persistent data, and HTML, CSS and JavaScript for the interface. The project also includes an AJAX-powered catalog suggestion endpoint and a Chart.js dashboard visualization. In this presentation I will explain the problem, architecture, demonstrate the customer and admin workflows, discuss security and testing, and finish with the deployment plan.

## 1:00–2:00 — Problem and objectives
Bookshops need a simple digital channel that allows customers to find products without visiting a physical store. Staff also need reliable ways to maintain book information, monitor stock and update order status. A useful solution must connect the customer interface to a consistent database and protect administrative actions.

My objectives were to create a responsive storefront, implement customer authentication, organize books by category, provide catalog search, support a database-backed cart and checkout flow, record orders, and provide administrator tools for catalog and order management. I also wanted the code to be maintainable, with shared components and security checks rather than duplicating logic on every page.

## 2:00–3:30 — Technology and architecture
The frontend is built with semantic HTML, custom responsive CSS and JavaScript. PHP handles page requests and business logic. MySQL stores users, categories, books, carts, cart items, orders and order items. The application uses mysqli prepared statements for database queries.

The request flow begins in the browser and reaches Apache and PHP. A shared bootstrap file starts a secure session, loads the database connection and includes reusable helper and authorization functions. Page controllers then perform their specific work. The catalog also calls a JSON endpoint using fetch so that search suggestions can appear as a user types.

The database is relational. A user can have one cart. A cart contains multiple cart items, each referencing a book. An order belongs to a user and has multiple order items. Each order item stores the unit price at the time of purchase, so later catalog price changes do not rewrite order history. Foreign keys and unique constraints protect these relationships.

## 3:30–4:30 — Homepage and catalog demonstration
[Show the actual homepage in the browser.] The homepage introduces the bookstore brand and displays featured books from the database. The navigation takes customers to the catalog, sign-in and bag pages.

[Click Books.] The catalog retrieves active books and displays their title, author, category and price. Customers can search by title or author and filter by category. As I type into the search field, the browser requests suggestions from the PHP JSON endpoint. Selecting a suggestion opens the corresponding book detail page.

[Open a book.] The detail page displays its description, price and stock availability. The add-to-bag action is protected by authentication, so a visitor is asked to sign in before adding a product to a personal cart.

## 4:30–5:45 — Customer account and cart
[Show registration and sign-in pages, then sign in with a test customer account.] Passwords are never stored as plain text. PHP password hashing is used when accounts are created, and password verification is used during sign-in. The session identifier is regenerated after successful authentication.

[Show the cart.] The cart is associated with the signed-in user. Customers can change quantities or remove items. The application calculates the subtotal from database prices and quantities; it does not trust a total posted by the browser.

[Show checkout.] The checkout form collects a delivery address and phone number. This capstone implements a demo order flow and does not collect payment. Before creating the order, the server checks available stock. It then creates the order and order-item records and reduces stock within a database transaction. If a stock check fails, the transaction is rolled back.

## 5:45–7:30 — Order history and admin dashboard
[Place a test order, then open My Orders.] The customer can see the order number, date, status and total. This confirms that the checkout flow records data in the database.

[Sign out and sign in with the administrator account created locally.] The admin dashboard summarizes customer, book and order counts, along with revenue from eligible order statuses. A Chart.js bar chart visualizes the current record counts. The dashboard links to book management, category management, customer management and order management.

[Show book management.] Administrators can add and edit book details, set price and stock, upload a cover image and archive books. The upload handler checks file size and MIME type and generates a random server filename. Category management allows the administrator to add categories. Customer management allows a customer account to be activated or blocked. Order management allows the administrator to update the order status.

## 7:30–9:00 — Database and security
[Open phpMyAdmin and show the seven tables.] The schema contains users, categories, books, cart, cart_items, orders and order_items. Primary keys identify records. Foreign keys maintain relationships. Indexes support common lookups, and unique constraints prevent duplicate emails and duplicate category names or slugs.

Security is built into the shared helpers. Prepared statements protect SQL operations from injection. Output is escaped before it is rendered in HTML. POST forms include CSRF tokens that are checked on the server. Sessions use HttpOnly and SameSite cookie settings, and the secure flag is enabled when HTTPS is detected. Admin pages re-check the current user's role and active status in the database rather than relying only on a browser-side control. Image uploads are limited to supported image MIME types and a maximum file size.

## 9:00–10:00 — Testing
I prepared a manual test checklist covering database import, account registration, login and logout, book search, category filtering, cart quantity updates, checkout, stock reduction, order history, admin access, order status changes and image upload validation.

[Show only test results you actually completed.] The source package passed PHP syntax linting in the build environment. Full end-to-end testing must be performed with the mysqli PHP extension and a running MySQL or MariaDB server. I will not describe a test as passed until I have run it and verified the result in the browser and database.

## 10:00–11:00 — GitHub and deployment
The project includes a README with local XAMPP setup instructions, a database SQL file and a gitignore that excludes local database credentials. To publish it, I initialize Git, commit the project, add my own GitHub repository as the remote and push the main branch. I do not commit passwords or local database configuration.

For deployment, I will use a PHP/MySQL host that supports the required PHP version and extensions. I create a database and user, import the SQL file, set the production database credentials securely, configure the base URL and enable HTTPS. After deployment I will test customer and administrator workflows using the public URL. The public URL should only be shown here after deployment is complete and verified.

## 11:00–12:00 — Conclusion
To conclude, PageTurn demonstrates the core structure of a small ecommerce application: a customer storefront, a relational database, authentication, cart and order workflows, and administrator tools. The project helped me practice PHP application structure, database relationships, secure form handling, session management and responsive interface design.

The next improvements I would prioritize are verified email OTP, a fully configured password-reset email service, payment gateway integration, richer sales analytics, pagination, automated tests and deployment automation. Thank you for watching my ApexPlanet capstone presentation. I welcome feedback and suggestions.
