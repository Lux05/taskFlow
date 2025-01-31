# TaskFlow - Simple Task Management System

TaskFlow is a lightweight task management system built with PHP that helps users organize and track their tasks using a Kanban-style board interface.

## Features

- 🔐 User authentication system
- 📋 Kanban board interface with three columns (To Do, In Progress, Done)
- ✨ Task management (Create, Read, Update, Delete)
- 🎯 Priority levels (Low, Medium, High)
- 📱 Responsive design
- 🔄 Real-time status updates using AJAX

## Technologies Used

- PHP 7.4+
- MySQL 5.7+
- Bootstrap 5
- JavaScript (ES6+)
- HTML5
- CSS3

## Installation

1. Clone the repository
```bash
git clone https://github.com/yourusername/taskflow.git
```

2. Create MySQL database and import the schema:
```sql
CREATE DATABASE task_manager;
USE taskflow;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    status ENUM('todo', 'in_progress', 'done') DEFAULT 'todo',
    priority ENUM('low', 'medium', 'high') DEFAULT 'medium',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

3. Configure your database connection:
   - Open `includes/config.php`
   - Update the database credentials:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'task_manager');
```

4. Place the project in your web server directory (e.g., htdocs for XAMPP)

5. Access the application through your web browser:
```
http://localhost/task_manager
```

## Project Structure

```
task_manager/
├── includes/
│   ├── config.php     # Database and app configuration
│   ├── db.php        # Database connection functions
│   ├── auth.php      # Authentication functions
│   └── functions.php # Helper functions and task operations
├── templates/
│   ├── header.php    # Common header template
│   └── footer.php    # Common footer template
├── index.php        # Main task board
├── login.php        # Login page
├── register.php     # Registration page
├── add_task.php     # Add new task
├── edit_task.php    # Edit existing task
├── logout.php       # Logout handler
└── README.md        # Project documentation
```

## Security Features

- Password hashing using PHP's password_hash()
- Protection against SQL injection using prepared statements
- XSS protection through input sanitization
- Session-based authentication
- Form validation and sanitization

## Future Improvements

- [ ] Add task categories/tags
- [ ] Implement task due dates
- [ ] Add file attachments
- [ ] Add task search and filtering
- [ ] Implement drag-and-drop for tasks
- [ ] Add task statistics and reporting
- [ ] Email notifications for task updates

## Contributing

This project is for demonstration purposes, but if you'd like to contribute:

1. Fork the repository
2. Create a new branch (`git checkout -b feature/improvement`)
3. Make your changes
4. Commit your changes (`git commit -am 'Add new feature'`)
5. Push to the branch (`git push origin feature/improvement`)
6. Create a Pull Request

## License

This project is open source and available under the [MIT License].

## Author

Lux's - Initial work

## Acknowledgments

- Bootstrap team for the fantastic UI framework
- PHP community for inspiration and resources
