<?php
  session_start();
  $password = "password";
  $salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
  $salt = sprintf("$2a$%02d$", $cost) . $salt;
  $hash = crypt($password, $salt);
 if ($sessionid!=NULL) 
  {
    header("location:./menu.php");
  }
  print $sessionid;
  print $hash;
?>

<html>

<head>
  <script src="./common.js"></script>
  <!-- use cryptoJS to encrypt in javascript<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/components/aes-min.js"></script>-->
    <script>
    var xhr = null;
    function verifyPwd()
    {
      var uname = document.getElementById("txtUsr").value;
      var pwd = document.getElementById("txtPwd").value;
      console.log(pwd);
      //var newpwd = CryptoJS.AES.encrypt(pwd,"U2FsdGVkX18ZUVvShFSES21qHsQEqZXMxQ9zgHy+bu0=");
      xhr = getXHR();
      //console.log(newpwd);
      xhr.onreadystatechange = processResponse;
      var strToSend = "usr=" + uname + "&pwd=" + pwd;
      xhr.open("POST", "/systemsynq/verifyPassword.php", true);
      xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhr.send(strToSend);
    }

    var xhr = null;
    function registerUser()
    {
      var uname = document.getElementById("registerUsr").value;
      var pwd = document.getElementById("registerPwd").value;
      xhr = getXHR();
      //xhr.onreadystatechange = processResponse;
      var strToSend = "usr=" + uname + "&pwd=" + pwd;
      console.log(strToSend);
      xhr.open("POST", "/systemsynq/register.php", true);
      xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhr.send(strToSend);

    }

    function processResponse()
    {
      var lblMsg = document.getElementById("lblMsg");
      if (xhr.readyState == 4)
      {
        var txt = xhr.responseText;
        var iResponse = parseInt(txt);
        if (isNaN(iResponse) || iResponse == 0) 
        {
          lblMsg.innerHTML = "Invalid username/password!";
        }
        else
        {
          window.location = "./menu.php";
        }
      }
      return iResponse;
    }
  </script>
</head>

<body style="background-color:#f7f7f7;">
  <center>
    <div class="login-page">
      <div class="form">
        <img src="https://i.imgur.com/Ribeov8.png" alt="System Synq" style="width:856px;height:435px;">
        </form>
        <form class="login-form">
          <input type="text" id="txtUsr" />
          <input type="password" id="txtPwd" />
          <input type="button" id="btnLogin" value="login" onclick="verifyPwd()" />
	  <p class="message">Register below.</p>
	  <input type="text" id="registerUsr" />
          <input type="password" id="registerPwd" />
          <input type="button" id="btnRegister" value="register" onclick="registerUser()" />
	  <form action="send.php" method="post" onsubmit="return checkControls();">
        </form>
      </div>
    </div>
  </center>
</body>

</html>

