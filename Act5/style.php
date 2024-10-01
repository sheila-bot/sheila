<?php
header("Content-type: text/css; charset: UTF-8");
?>

body {
    margin: 0;
    padding: 0;
    background-image: url('geo1.jpeg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed; 
    height: 100vh;
    cursor: url('cursor.png') 4 8, auto;
    cursor: pointer;
}

.login-container {
position: fixed;
top: 50%;
left: 50%;
transform: translate(-50%, -50%);
background: rgba(255, 255, 255, 0.2); 
backdrop-filter: blur(3px); 
-webkit-backdrop-filter: blur(3px); 
border: 2px solid rgba(200, 150, 200, 0.5); 
border-radius: 20px;
padding: 20px;
box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.7);
text-align: center;
width: 30%;

}

.login-container .error {
    color: red;
    margin-bottom: 10px;
}

.login-container input[type="text"], 
.login-container input[type="password"] {
    font-family: Arial, sans-serif;
    width: calc(100% - 50px);
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}

.login-container .login-image {
    width: 12%;
    cursor: pointer;
}
.container {
position: fixed;
top: 50%;
left: 50%;
width: 70%;
height: 70%;
transform: translate(-50%, -50%);
display: flex;
flex-direction: column;
justify-content: center;
align-items: center;
text-align: center;
background: rgba(255, 255, 255, 0.2); 
backdrop-filter: blur(3px); 
-webkit-backdrop-filter: blur(3px); 
border: 2px solid rgba(255, 255, 255, 0.2); 
padding: 20px;
border-radius: 10px;
box-shadow: 0px 4px 30px rgba(0, 0, 0, 0.1); /* Slight shadow for depth */

}


  .g-recaptcha {
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .login-container {
    display: flex;
    flex-direction: column;
    align-items: center;
  }
  #registerForm button[type="submit"] {
    margin-top: 20px;
  }


.welcome-text {
    font-size: 5rem; 
    text-align: center; 
    margin-top: 20px; 
    color: #ffffff; 
    -webkit-text-stroke: 2px #800080; 
    text-shadow: 2px 2px 5px rgba(128, 0, 128, 0.7); 
}

.logout-image {
    width: 60%; /* Adjust the size as needed */
    max-width: 150px; /* Ensure it stays small enough */
    cursor: pointer;
}

h1, h2, h3, h4, h5, h6 {
    font-family: 'Courier New', monospace;
    color: black
}