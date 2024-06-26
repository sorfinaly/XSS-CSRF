<?php
session_start();
include 'content-security-policy.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Details</title>

<style>
  @import url("https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i,600,600i&display=swap");

.form {
  font-family: "Source Sans Pro", sans-serif;
  font-size: 20px;
}

.form * {
  box-sizing: border-box;
  line-height: 1.5;
}

.form__title {
  font-size: 2em;
  font-weight: 600;
}

.form__item {
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  margin-right:30px;
  
}

.form__item > * {
  align-self: flex-start;
}

.form__label {
  font-weight: 600;
  padding: 10px 0;
  width: calc(100% - 400px); /*to set the width of the label400px but 100% of the parent container*/
}

.form__input {
  -webkit-appearance: none;

  width: 100%;
  max-width: 400px;

  /* Fix for Safari/iOS date fields */
  min-height: calc(0.9em + (0.8em * 2) + 0.6em);

  padding: 0.3em;
  font-size: 0.9em;
  font-family: "Source Sans Pro", sans-serif;

  outline: none;
  border: 1px solid #dddddd;
  border-radius: 4px;
  background: #f9f9f9;
  transition: background 0.25s, border-color 0.25s, color 0.25s;
}

.form__input:focus {
  background: #ffffff;
}

.form__input::placeholder {
  color: #bbbbbb;
}

.form__input--error {
  color: #d50000;
  background: #fff8f8;
  border-color: #d50000;
}

.form__input--error::placeholder {
  color: #ffbfbf;
}

.form__error {
  padding-bottom: 10px;
  width: calc(100% - 260px);
  margin-left: auto;
  text-align: center;
  color: #d50000;
  font-size: 0.9em;
  visibility: hidden;
}

.form__input--error + .form__error {
  visibility: visible;
}

.form__input--small {
  max-width: 250px;
}

textarea.form__input {
  resize: none;
  min-height: 100px;
}

.form__btn {
  font-family: "Source Sans Pro", sans-serif;
  font-weight: 600;
  font-size: 1.1em;
  padding: 10px 16px;
  margin: 10px auto; /* Add this line to center the button */

  color: #ffffff;
  background: #14b64a;
  border: 2px solid #0fa942;
  border-radius: 5px;

  cursor: pointer;
  outline: none;
}

.form__btn:active {
  background: #0fa942;
}

</style>


</head>
<body>

<div style=" display: flex; align-items: center; justify-content: center;">
  <form class="form" id="studentform" action="crud.php" method="POST">
   
    <div class="form__title" style="background-color: rgb(232, 224, 224);">Student Details</div>

    <p class="form__desc">
      Please fill out the form below to sign up for a student account.
    </p>
    <div class="form__item">
      <label for="name" class="form__label">Name (Legal/Office): </label>
      <input type="text" class="form__input" name="name" id="name" pattern="^[A-Za-z\s]+$" placeholder="Ali bin Adam" required  >
      <span class="form__error">Only alphabets are allowed</span>
    </div>
    <div class="form__item">
      <label for="matricno" class="form__label">Matric Number: </label>
      <input type="text" class="form__input form__input" name="matricno" id="matricno" pattern="^\d{7}$" placeholder="eg: XXXXXXX" required>
      <span class="form__error">eg: 2017347</span>
    </div>
    <div class="form__item">
        <label for="curraddress" class="form__label">Current Address: </label>
        <textarea maxlength="100" class="form__input" name="curraddress" id="curraddress" pattern="^[A-Za-z0-9/\s,\-.]+$" required></textarea>
        <span class="form__error">Special char allowed: . , / </span>
    </div>
    
    <div class="form__item">
      <label for="homeaddress" class="form__label">Home Address: </label>
      <textarea maxlength="100" class="form__input" name="homeaddress" id="homeaddress" pattern="^[A-Za-z0-9/\s,\-.]+$" required></textarea>
      <span class="form__error">A sample error message</span>
    </div>
    <div class="form__item">
      <label for="email" class="form__label">Email (Gmail Account): </label>
      <input type="email" class="form__input"  id="email" name="email" pattern="[a-z0-9._%+-]+@gmail+\.[a-z]{2,}$" placeholder="example@gmail.com" required>
      <span class="form__error">eg: example@gmail.com</span>
    </div>
    <div class="form__item">
        <label for="mobilephone" class="form__label">Mobile Phone Number: </label>
        <input type="text" class="form__input"  id="mobilephone" name="mobilephone" pattern="\d{3}[\-]\d{3}[\-]\d{4}" placeholder="XXX-XXX-XXXX" >
        <span class="form__error">eg: 010-123-5690</span>
    </div>
    <div class="form__item">
      <label for="homephone" class="form__label">Home Phone Number (Emergency): </label>
      <input type="text" class="form__input" id="homephone" name="homephone" pattern="\d{3}[\-]\d{3}[\-]\d{4}" placeholder="XXX-XXX-XXXX" >
      <span class="form__error">eg: 010-123-5690</span>
    </div>
    
    <input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>">
    <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
    

    <div class="form__item">

      <button class="form__btn" type="submit">Submit</button>
    </div>
  </form>
</div>

<script>

  // Function to dynamically encode user input before displaying it
  function encodeHtmlEntities(input) {
    return input.replace(/[\u00A0-\u9999<>\&]/gim, function(i) {
      return '&#' + i.charCodeAt(0) + ';';
    });
  }

  var csrfToken = "<?php echo isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : ''; ?>";

  // Validate form inputs
  function validateFormInputs() {
    var isValid = true;
    var inputs = document.querySelectorAll(".form__input, .form__textarea");

    inputs.forEach(function(input) {
      const errorMessage = input.nextElementSibling; // Select the next sibling element (error message) of the input
      const pattern = input.getAttribute("pattern"); // Get the pattern attribute value of the input (if applicable)
      const inputValue = input.value; // Get the value entered by the user

      if (pattern && inputValue && !new RegExp(pattern).test(inputValue)) {
        input.classList.add("form__input--error"); // Add error class to input
        errorMessage.style.visibility = "visible"; // Show error message
        isValid = false;
      } else {
        input.classList.remove("form__input--error"); // Remove error class from input
        errorMessage.style.visibility = "hidden"; // Hide error message
      }
    });

    return isValid;
  }


document.getElementById('studentform').addEventListener('submit', function(event) {
  event.preventDefault(); // Prevent the default form submission

  var formData = new FormData(this);

  // Check if ID parameter is present in URL
  var urlParams = new URLSearchParams(window.location.search);
  var id = urlParams.get('id');

  // Set CSRF token in form data
  formData.append('csrf_token', csrfToken);

  // Set ID in form data if present
  if (id) {
    formData.append('id', id);
  }

  // Send form data to crud.php for processing
  fetch('crud.php', {
    method: 'POST',
    body: formData
  })
  .then(response => {
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    return response.text();
  })
  .then(data => {
    // Handle success response
    console.log('Response from server:', data);
    // Redirect to student_details.php or any other page as per your requirement
    window.location.href = 'student_details.php?id=' + id; // Redirect to student_details.php with ID parameter
  })
  .catch(error => {
    // Handle network errors
    console.error('There was an error with the network:', error);
  });
});

  </script>

  
</body>
</html>