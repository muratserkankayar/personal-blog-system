CREATE DATABASE IF NOT EXISTS mvc_blog_system
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE mvc_blog_system;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS comments;
DROP TABLE IF EXISTS posts;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL
);

CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    content TEXT NOT NULL,
    excerpt TEXT,
    category_id INT,
    user_id INT NOT NULL,
    status ENUM('draft', 'published') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Passwords:
-- admin@example.com / Admin123!
-- user@example.com  / User123!
INSERT INTO users (id, username, email, password, role) VALUES
(1, 'admin', 'admin@example.com', '$2y$12$R.rUQcfVDoEwj.rCWSpxEu9ggDimbcvSBGhs/n0egec9uTW90q/wa', 'admin'),
(2, 'demo_user', 'user@example.com', '$2y$12$BA3SJIqgOFIbQRNFiU4mbe0bHQM8YUMsI41ZKKYDdL.aQ2bHOnEI2', 'user');

INSERT INTO categories (id, name, slug) VALUES
(1, 'Web Development', 'web-development'),
(2, 'University Life', 'university-life'),
(3, 'Productivity', 'productivity'),
(4, 'Technology', 'technology');

INSERT INTO posts
    (id, title, slug, content, excerpt, category_id, user_id, status, created_at, updated_at)
VALUES
(1, 'Welcome to the Personal Blog System', 'welcome-to-the-personal-blog-system',
 'This application demonstrates a complete Model-View-Controller architecture in pure PHP. It includes secure authentication, role-based authorization, content management, comments, search, pagination, validation, and CSRF protection.',
 'A guided introduction to the features and architecture of this MVC project.',
 1, 1, 'published', '2026-05-20 09:00:00', '2026-05-20 09:00:00'),
(2, 'Why MVC Makes PHP Projects Easier to Maintain', 'why-mvc-makes-php-projects-easier-to-maintain',
 'MVC separates database operations, request handling, and presentation. Models own data access, controllers coordinate application rules, and views focus on escaped HTML output. This separation makes each part easier to understand, test, and change.',
 'A practical explanation of responsibilities in a pure PHP MVC application.',
 1, 2, 'published', '2026-05-21 10:00:00', '2026-05-21 10:00:00'),
(3, 'Five Habits for a Productive Study Week', 'five-habits-for-a-productive-study-week',
 'Plan the week before it begins, choose a small number of priorities, protect focused work time, review notes regularly, and leave enough room for rest. Sustainable routines are more useful than one intense day.',
 'Simple habits that make university work more predictable and manageable.',
 3, 2, 'published', '2026-05-22 11:00:00', '2026-05-22 11:00:00'),
(4, 'Prepared Statements and SQL Injection', 'prepared-statements-and-sql-injection',
 'Prepared statements keep SQL structure separate from user-provided values. PDO sends parameters independently, preventing malicious input from changing the meaning of a query. Every dynamic value in this project uses a prepared statement.',
 'Why PDO prepared statements are a mandatory security practice.',
 1, 1, 'published', '2026-05-23 12:00:00', '2026-05-23 12:00:00'),
(5, 'Escaping Output to Prevent XSS', 'escaping-output-to-prevent-xss',
 'Cross-site scripting becomes possible when untrusted text is inserted into HTML without encoding. The central e helper converts special characters safely, and post content is rendered as escaped plain text with preserved line breaks.',
 'How consistent output escaping protects visitors from injected scripts.',
 4, 1, 'published', '2026-05-24 13:00:00', '2026-05-24 13:00:00'),
(6, 'A Calm Approach to Final Project Planning', 'a-calm-approach-to-final-project-planning',
 'Start by turning the rubric into a checklist. Build the smallest complete vertical flow, then add security and quality checks as part of each feature. Keep a short demonstration script so the final presentation is clear and repeatable.',
 'Turn a large final project into a sequence of verifiable outcomes.',
 2, 2, 'published', '2026-05-25 14:00:00', '2026-05-25 14:00:00'),
(7, 'What CSRF Tokens Actually Protect', 'what-csrf-tokens-actually-protect',
 'A CSRF token proves that a state-changing form originated from the current application session. The server stores a random token, embeds it in forms, and rejects submissions whose value is absent or different.',
 'A concise explanation of request-forgery protection for PHP forms.',
 4, 1, 'published', '2026-05-26 15:00:00', '2026-05-26 15:00:00'),
(8, 'Designing Useful Validation Messages', 'designing-useful-validation-messages',
 'Validation should explain exactly what needs to change while preserving non-sensitive form values. Good messages are specific, placed near the relevant field, and backed by server-side checks even when HTML validation is present.',
 'Validation is part of both application security and user experience.',
 1, 2, 'published', '2026-05-27 16:00:00', '2026-05-27 16:00:00'),
(9, 'Making Bootstrap Interfaces Feel Consistent', 'making-bootstrap-interfaces-feel-consistent',
 'Consistency comes from repeating a small set of spacing, card, button, badge, and typography decisions. Responsive containers and tables handle different screen sizes without creating a separate mobile application.',
 'Practical UI choices for a clean and responsive Bootstrap project.',
 1, 2, 'published', '2026-05-28 17:00:00', '2026-05-28 17:00:00'),
(10, 'Authorization Is More Than Hiding Buttons', 'authorization-is-more-than-hiding-buttons',
 'Hiding an edit button improves the interface but does not secure an action. Controllers must load the requested record and verify ownership or administrator status again before every update or deletion.',
 'Why server-side ownership checks are essential for protected CRUD actions.',
 4, 1, 'published', '2026-05-29 18:00:00', '2026-05-29 18:00:00'),
(11, 'Testing Models with an In-Memory Database', 'testing-models-with-an-in-memory-database',
 'Dependency-injected PDO connections let model tests use a temporary SQLite database. Tests can create a small schema, exercise real SQL, and remain isolated from the developer MySQL database.',
 'A fast way to test model behavior without modifying development data.',
 1, 2, 'published', '2026-05-30 19:00:00', '2026-05-30 19:00:00'),
(12, 'Preparing a Four-Minute Project Demonstration', 'preparing-a-four-minute-project-demonstration',
 'Open with the public pages, demonstrate search and pagination, register or log in, create a draft, publish it, add a comment, show ownership protection, and finish with the administrator dashboard and category management.',
 'A compact order for demonstrating every important grading requirement.',
 2, 2, 'published', '2026-05-31 20:00:00', '2026-05-31 20:00:00'),
(13, 'Draft: Ideas for the Next Version', 'draft-ideas-for-the-next-version',
 'This draft is intentionally private. It demonstrates that unpublished content is visible to its author and administrators but absent from public lists and public searches.',
 'A private draft used to demonstrate visibility rules.',
 4, 2, 'draft', '2026-06-01 09:00:00', '2026-06-01 09:00:00');

INSERT INTO comments (post_id, user_id, content, created_at) VALUES
(1, 2, 'The feature overview makes the project easy to understand.', '2026-06-01 12:00:00'),
(2, 1, 'Clear separation of responsibilities also makes code review much easier.', '2026-06-01 12:15:00'),
(12, 1, 'This order fits comfortably into a short presentation.', '2026-06-02 08:30:00');
