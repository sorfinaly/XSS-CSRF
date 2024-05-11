# Authentication

This assignment implements a student details form using HTML, CSS, JavaScript, and PHP. It allows users to input their personal details, validates the input data both client-side and server-side, and displays the submitted information by the students.

## File Descriptions

### 1. db.php

This PHP script named db.php contains database configuration details such as the host, username, password, and database name.


### 2. index.html
This file is where user can do login where user insert email and password. If user already registered, user will redirect to the next page which is `student_details.php`. If user has not register, user will be redirected to `register.html` to register new account. 

![image](https://github.com/sorfinaly/XSS-CSRF/assets/85787305/911068bc-e358-4d69-945d-ec0194f82b9b)

### 3. login.php
This PHP script starts a session and includes a database connection file. It checks if an email and password are provided via a POST request. If so, it retrieves the user's data from the database based on the email. If a user is found, it verifies the password using password_verify. If successful, it sets session variables for user ID, email, and password, then redirects to `student_details.php`. If authentication fails, or no credentials are provided, it displays an error message and redirects back to the login page.



### 4. register.html
This file is where user register new account where user insert email and password. After registration successful, user will prompt to click `login here`. 

![image](https://github.com/sorfinaly/XSS-CSRF/assets/85787305/098a05fc-5037-4eaf-8167-938f1e72c956)

### 5. register.php

This PHP script starts a session and includes a database connection file. If a POST request is received, it retrieves email and password data, hashes the password, and inserts the user into the database. If successful, it echoes a registration success message with a login link; otherwise, it echoes a failure message.

### 6. student_details.php

After successful login, this file will use the key-in email that user entered during logging. If the email already been registered in the database, student's record such as name, matrix number, address and more will be displayed by searching database using student's email. If student's email are not found in the database, student will be prompt to fill in the form in the `form.html`. This page will displayed student's record along with button update, delete and logout. 

### 7. crud.php

This file is containing various method such as insert, update delete and validation. This server-side will handle request from js if the student click on certain request. 

### 8. form.php

This file contains the user interface for the student details form. It includes input fields for the user's name, matriculation number, email, addresses, and phone numbers. Each input field has a `pattern` attribute for basic client-side validation. Upon submission, the form sends the data to `form.php` for further validation and processing.

![image](https://github.com/sorfinaly/XSS-CSRF/assets/85787305/16447072-6034-4ac9-a734-fc31e8667b5e)

### 9. logout.php

This file is to clear the session data then redirect user to `index.html`

### 10. content-security-policy.php



## How the Files are Connected

- User will need to enter credentials to login in `index.html`. If authentication successful by using login.php, user will redirect to `student_details.php`. If user has not fill in the form in `form.html`, user will need to fill in the form first before being able to see student_details. Then, user can click update button to update the data that will be redirected to `form.html`. If the user click delete button, the record will be deleted. If the user click logout button, user will be logout and will be redirected to `index.html`. Every files is using `include content-security-policy.php` to ensure that every files is using the same content policy. 



## Error Handling

If there are validation errors, the error messages are displayed next to the respective input fields on `form.html`, providing clear feedback to the user. The form data is not cleared, allowing the user to correct the errors and resubmit the form. If the input is successfully validated, the sanitized data is displayed in a tabular format on the same page.

![image](https://github.com/sorfinaly/XSS-CSRF/assets/85787305/5dc7add5-982d-459c-ae92-efce9bfd6704)