<?php
$LOGIN_INFORMATION = array(
  'user123' => 'password123',
  'user456' => 'password456'
);
define('USE_USERNAME', true);
define('LOGOUT_URL', 'http://sphysics.in');
define('TIMEOUT_MINUTES', 0);
define('TIMEOUT_CHECK_ACTIVITY', true);
if(isset($_GET['help'])) {
  die('Include following code into every page you would like to protect, at the very beginning (first line):<br>&lt;?php include("' . str_replace('\\','\\\\',__FILE__) . '"); ?&gt;');
}
$timeout = (TIMEOUT_MINUTES == 0 ? 0 : time() + TIMEOUT_MINUTES * 60);
if(isset($_GET['logout'])) {
  setcookie("verify", '', $timeout, '/'); // clear password;
  header('Location: ' . LOGOUT_URL);
  exit();
}
if(!function_exists('showLoginPasswordProtect')) {
function showLoginPasswordProtect($error_msg) {
?>
<html>
<head>
  <title>Please enter password to access this page</title>
  <META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
  <META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
</head>
<body background="http://lh6.googleusercontent.com/-vuT6Zb3JRDk/UQLCvU2uoXI/AAAAAAAAASQ/wKbXgowi7b8/s800/IMAG0508.jpg">
  <style>
    input { border: 1px solid black; }
  </style>
  <div style="width:500px; margin-left:auto; margin-right:auto; text-align:center">
  <form method="post">
    <h3>Please enter username and password to access this page<br/>&#2325;&#2371;&#2346;&#2351;&#2366; &#2313;&#2346;&#2351;&#2379;&#2327;&#2325;&#2352;&#2381;&#2340;&#2381;&#2340;&#2366; &#2325;&#2366; &#2344;&#2366;&#2350; &#2357; &#2346;&#2366;&#2352;&#2339; &#2358;&#2348;&#2381;&#2342; &#2337;&#2366;&#2354;&#2375;&#2306;</h3>
    <font color="red"><?php echo $error_msg; ?></font><br />
<?php if (USE_USERNAME) echo 'Username &#2313;&#2346;&#2351;&#2379;&#2327;&#2325;&#2352;&#2381;&#2340;&#2381;&#2340;&#2366; &#2325;&#2366; &#2344;&#2366;&#2350;:<br /><input type="input" name="access_login" /><br />Password &#2346;&#2366;&#2352;&#2339; &#2358;&#2348;&#2381;&#2342;:<br />'; ?>
    <input type="password" name="access_password" /><p></p><input type="submit" name="Submit" value="Submit &#2332;&#2350;&#2366; &#2325;&#2352;&#2375;&#2306;" />
  </form>
  <br />
  </div>
</body>
</html>
<?php
 // stop at this point
  die();
}
}
// user provided password
if (isset($_POST['access_password'])) {
  $login = isset($_POST['access_login']) ? $_POST['access_login'] : '';
  $pass = $_POST['access_password'];
  if (!USE_USERNAME && !in_array($pass, $LOGIN_INFORMATION)
  || (USE_USERNAME && ( !array_key_exists($login, $LOGIN_INFORMATION) || $LOGIN_INFORMATION[$login] != $pass ) )
  ) {
    showLoginPasswordProtect("Incorrect username/password. &#2313;&#2346;&#2351;&#2379;&#2327;&#2325;&#2352;&#2381;&#2340;&#2381;&#2340;&#2366; &#2325;&#2366; &#2344;&#2366;&#2350;/&#2346;&#2366;&#2352;&#2339; &#2358;&#2348;&#2381;&#2342; &#2327;&#2354;&#2340; &#2361;&#2376;&#2404;");
  }
  else {
    // set cookie if password was validated
    setcookie("verify", md5($login.'%'.$pass), $timeout, '/');
   // Some programs (like Form1 Bilder) check $_POST array to see if parameters passed
    // So need to clear password protector variables
    unset($_POST['access_login']);
    unset($_POST['access_password']);
    unset($_POST['Submit']);
  }
}
else {
  // check if password cookie is set
  if (!isset($_COOKIE['verify'])) {
    showLoginPasswordProtect("");
  }
  // check if cookie is good
  $found = false;
  foreach($LOGIN_INFORMATION as $key=>$val) {
    $lp = (USE_USERNAME ? $key : '') .'%'.$val;
 if ($_COOKIE['verify'] == md5($lp)) {
      $found = true;
      // prolong timeout
      if (TIMEOUT_CHECK_ACTIVITY) {
        setcookie("verify", md5($lp), $timeout, '/');
      }
      break;
    }
  }
  if (!$found) {
    showLoginPasswordProtect("");
  }
}
?>
