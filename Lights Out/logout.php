<?php
  //distruggo la sessione  
  session_start();
  session_unset(); 
  session_destroy(); 
  $_SESSION = array(); // reimposto array SESSION
  header("Location: pages/login.php");
?> 