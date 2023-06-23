# Coding and SQL Assessments

Your task is to design and implement a PHP-based RESTful API that interacts with a MySQL database and integrates with an external data source. The complexity of this task is intended to evaluate your ability to create robust, efficient code that adheres to best practices and coding standards.

## Task:

## Database Creation
- Create a MySQL database containing three tables: users, user_logs, and external_data
- The users table should have the following fields: id, name, email, password
- The user_logs table should contain: id, user_id, login_timestamp, logout_timestamp
- The external_data table should contain: id, user_id, data
- Create any 1 many-to-many table and 1 morph table that related to user.

## API Development
- Develop a PHP-based RESTful API that can perform the following operations: 
o	Create a new user (POST)
o	Retrieve all users (GET)
o	Retrieve a single user (GET)
o	Update a user's information (PUT)
o	Delete a user (DELETE)
o	Record a user's login and logout timestamps (POST)
o	Retrieve and store data from an external data source (e.g., a public API of your choice) (GET, POST)
o	Retrieve and store data for many-to-many relationship & morph relationship with user (GET,POST)

## Authentication
- Implement secure authentication for the API using JWT (JSON Web Tokens)
- Implement rate limiting for API requests to prevent abuse
Error Handling & Logging
- Implement proper error handling for the API
- Log errors and exceptions in a format and location that would be suitable for later analysis
Testing
- Write PHPUnit tests to ensure the API works as expected, including tests for the rate limiting and error handling features

## Submission:
Submit your code via a GitHub repository. Please ensure to include:
1.	The database schema
2.	The PHP code for the API
3.	PHPUnit test code
4.	A README.md file that explains how to set up and use the API, including how to run the tests

## Evaluation Criteria:
Your assignment will be evaluated based on the following:
1.	Code Quality: The code should be clean, efficient, and adhere to best practices and coding standards.
2.	Database Design: The database should be properly structured and normalized.
3.	Functionality: The API should work as described.
4.	Security: The authentication and rate limiting should provide a secure environment for the API.
5.	Error Handling & Logging: The solution should gracefully handle errors and log them appropriately.
6.	Testing: The tests should cover all crucial functionalities and edge cases.
7.	Documentation: The README.md should clearly explain how to set up and use the API.

Remember, the goal is not just to complete the task but to demonstrate your senior-level proficiency with PHP, MySQL, RESTful APIs, JWT authentication, rate limiting, error handling, logging, and PHPUnit testing. Good luck!
