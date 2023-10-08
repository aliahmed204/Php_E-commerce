# Php E-commerce 
Native PHP e-commerce project
## Overview
The E-Commerce Website is a comprehensive platform designed to facilitate online buying 
and selling. It focuses on key resources, including users, categories, and items. 
Additionally, users can engage in discussions through comments and place orders for 
products. The dashboard provides users with valuable insights through various reports, 
such as favorite categories, favorite items, and recent comments on their items.
### Key Resources
Users, Categories, Items, Comments, Orders, 	Carts, 	Cart Items,

## Features
User Activities: Users can buy and sell products, make comments, and place orders.
Orders: Users can add items to their carts, review cart contents, and complete orders
User Registration: New users can register accounts with validation checks.
Reports: Users can view reports like favorite categories, favorite items, and recent 
comments on their items.
Dashboard: A user-friendly dashboard for easy navigation and access to key features.
User Groups: Differentiate between admin and regular users for varying levels of access 
and permissions.

### User Registration
Registration System: Users can register for an account to access the website's features
Registration Form: A user-friendly registration form is provided, including fields for 
 username, password, email, full name, and other required details.
Validation Checks: The system enforces validation checks during registration to ensure 
 data accuracy and security:
Username Validation: Checks for uniqueness and valid characters.
Password Validation: Ensures password complexity and security.
Email Validation: Validates the email format and uniqueness.
Full Name Validation: Ensures valid characters and length. 
Registration Confirmation: After successful registration, users receive a confirmation 
message or email with account activation instructions if required.
User Authentication: Registered users can log in using their username and password.

### Role-Based Access Control
Administrator Role: Administrators have elevated privileges, enabling them to perform 
 the following actions:
 Create, edit, and delete categories.
 Create, edit, and delete items.
 Manage user accounts (create, edit, delete).
 Approve or reject items, categories, and comments.
	
 User Role: Regular users have limited access until their actions are approved by 
 administrators:
 User Registration: Users can register for an account.
 Item Submission: Users can add items to the system, but these items are not immediately visible until approved.
 Comment Submission: Users can post comments, but comments may require approval before becoming visible.
 Approval Process: The system includes an approval process for items, categories, and 
 comments:
 Item and Category Approval: Administrators review and approve or reject items and categories to ensure quality and compliance with guidelines.
 Comment Approval: Administrators can approve or reject user comments to maintain a positive and safe user experience.

### User Experience
User Registration: New users can register for an account and submit items, comments, or profile edits.
Item Submission: Users can add items, but these items are not immediately visible until approved by administrators.
Comment Submission: Users can post comments, but comments may require approval before becoming visible to others.
Profile Editing: Users can edit their profiles,

### SQL Query Validation
Data Integrity: SQL queries include data integrity checks and constraints to prevent invalid or malicious data from being inserted or manipulated.
Parameterized Queries: SQL queries use parameterized statements or prepared statements to protect against SQL injection attacks.
Input Validation: User inputs are sanitized and validated to prevent unauthorized or harmful data entry.
Access Control: SQL queries enforce access control and permissions, ensuring that users can only access data and perform actions they are authorized for.



 
