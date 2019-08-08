<?php
include('../common.php');
try{
	$db=new PDO('mysql:host=' . DBHOST . ';dbname=' . DBNAME, DBUSER, DBPASS, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING, PDO::ATTR_PERSISTENT=>PERSISTENT]);
}catch(PDOException $e){
	die('No Connection to MySQL database!');
}
session_start();
$user=check_login();
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
	if($error=check_csrf_error()){
		$msg.='<p class="alert alert-danger">'.$error.'</p>';
	}elseif(!isset($_POST['pass']) || !password_verify($_POST['pass'], $user['password'])){
		$msg.='<p class="alert alert-danger">Wrong password.</p>';
	}else{
		$stmt=$db->prepare('UPDATE users SET todelete=1 WHERE id=?;');
		$stmt->execute([$user['id']]);
		session_destroy();
		header('Location: login.php');
		exit;
	}
}
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="OnionMax Hosting. Free Fast Reliable Tor Web Hosting.">
  <meta name="author" content="onionmax">
  <title>OnionMax Hosting - Delete Your Account!</title>
  <link rel="icon" href="assets/img/brand/favicon.png" type="image/png">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
  <link rel="stylesheet" href="assets/vendor/nucleo/css/nucleo.css" type="text/css">
  <link rel="stylesheet" href="assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">
  <link rel="stylesheet" href="assets/css/argon.css?v=1.1.0" type="text/css">
</head>
<body class="bg-default">
  <div class="main-content">
    <div class="header bg-gradient-primary py-7 py-lg-8 pt-lg-9">
      <div class="container">
        <div class="header-body text-center mb-7">
          <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8 px-5">
              <h1 class="text-white">Delete Account</h1>
              <p class="text-lead text-white">This Will Permanently Delete your Hosting account and all data. Once Processed it cannot be undone or recovered.</p>
            </div>
          </div>
        </div>
      </div>
      <div class="separator separator-bottom separator-skew zindex-100">
        <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
          <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
        </svg>
      </div>
    </div>     
    <div class="container mt--8 pb-5">
      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
           <?php echo $msg; ?>
          <div class="card card-profile bg-secondary mt-5">         
            <div class="card-body pt-7 px-5">
              <div class="text-center mb-4">
                <h3>Enter Account Password to Proceed </h3>
              </div>
              <form role="form" action="delete.php" method="POST">
                <div class="form-group">
                  <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                  <div class="input-group input-group-merge input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                    </div>
                    <input class="form-control" placeholder="Password" name="pass" type="password" autofocus required>
                  </div>                                       
                </div>
                <div class="text-center">
                  <button type="submit" value="Delete" class="btn btn-danger mt-2">Delete</button>
                </div>
              </form>
               <div class="text-center">
                  <a href="home.php"><button type="button" class="btn btn-primary mt-2">No Don't Delete</button></a>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>