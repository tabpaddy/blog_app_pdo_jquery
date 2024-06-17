
This project is a blog application developed using PHP and MySQL (with PDO) following Object-Oriented Programming (OOP) principles. It also includes an admin page utilizing jQuery for dynamic data fetching.

## Table of Contents
- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
- [Folder Structure](#folder-structure)
- [Database Setup](#database-setup)
- [Configuration](#configuration)
- [jQuery for Admin Panel](#jquery-for-admin-panel)


## Features
- User authentication (login and registration)
- Admin panel for managing posts and categories
- Create, read, update, and delete posts
- Search functionality
- Categorize posts
- Display featured posts
- Author profile display

## Installation

### Prerequisites
- PHP >= 7.4
- MySQL >= 5.7
- Composer
- A web server like Apache or Nginx
- XAMPP (or similar local server environment)

### Steps
1. **Clone the repository**
   ```bash
   git clone https://github.com/tabpaddy/blog_app_pdo.git
   cd blog_app_pdo
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Set up environment variables**
   Rename the `.env.example` file to `.env` and update the database configuration:
   ```env
   DB_HOST=localhost
   DB_USER=root
   DB_PASS=yourpassword
   DB_NAME=blog
   ```

4. **Set up the database**
   - Import the `blog.sql` file located in the `database` folder into your MySQL server.
   - You can use a tool like phpMyAdmin or the MySQL command line to import the SQL file.

## Usage
- Start your local server (XAMPP or any other preferred method).
- Navigate to the project directory and open it in your browser:
  ```
  http://localhost/blog_app_pdo
  ```

### Admin Panel
- To access the admin panel, navigate to:
  ```
  http://localhost/blog_app_pdo/admin
  ```
- Use your admin credentials to log in and manage posts and categories.

## Folder Structure
```
blog_app_pdo/
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── config/
│   └── database.php
├── controller/
│   └── post.php
├── model/
│   └── posts.php
├── partials/
│   ├── header.php
│   └── footer.php
├── view/
│   ├── index.php
│   ├── post.php
│   ├── category-post.php
│   ├── search.php
│   ├── singlepost.php
│   └── admin/
│       ├── index.php
│       ├── manage-category.php
        ├── manage-user.php
        ├── add-posts.php
        ├── add-user.php
        ├── add-category.php
        ├── edit-posts.php
        ├── edit-users.php
        ├── edit-cartigoty.php
│       └── manage-categories.php
├── README.md
└── index.php
```

## Database Setup
The database schema is provided in the `database/blog.sql` file. Import this file to create the necessary tables and some seed data.

## jQuery for Admin Panel
jQuery is used in the admin panel for dynamic data fetching and updating without page reloads. 

### Example Usage
In `assets/js/admin.js`, you might have something like:
```js
$(document).ready(function() {
    // Fetch posts
    $.ajax({
        url: 'controller/post.php',
        type: 'GET',
        data: { action: 'fetchPosts' },
        success: function(response) {
            $('#postTable').html(response);
        }
    });

    // Delete post
    $(document).on('click', '.delete-post', function() {
        var postId = $(this).data('id');
        $.ajax({
            url: 'controller/post.php',
            type: 'POST',
            data: { action: 'deletePost', id: postId },
            success: function(response) {
                // Refresh the post list
            }
        });
    });
});
```

.

## License
This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Contact
For further information or inquiries, please contact:

- Your Name: [yourname@example.com](mailto:yourname@example.com)
- GitHub: [https://github.com/yourusername](https://github.com/tabpaddy)

tails.
