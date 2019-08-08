<?php
include('../common.php');
try{
	$db=new PDO('mysql:host=' . DBHOST . ';dbname=' . DBNAME, DBUSER, DBPASS, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING, PDO::ATTR_PERSISTENT=>PERSISTENT]);
}catch(PDOException $e){
	die('No Connection to MySQL database!');
}
header('Content-Type: text/html; charset=UTF-8');
session_start();
if(!empty($_SESSION['hosting_username'])){
	header('Location: home.php');
	exit;
}
$msg='';
$username='';
if($_SERVER['REQUEST_METHOD']==='POST'){
	$ok=true;
	if($error=check_captcha_error()){
		$msg.="<p class=\"alert alert-danger\">$error</p>";
		$ok=false;
	}elseif(!isset($_POST['username']) || $_POST['username']===''){
		$msg.='<p class="alert alert-danger">Error: username may not be empty.</p>';
		$ok=false;
	}else{
		$stmt=$db->prepare('SELECT username, password, id FROM users WHERE username=?;');
		$stmt->execute([$_POST['username']]);
		$tmp=[];
		if(($tmp=$stmt->fetch(PDO::FETCH_NUM))===false && preg_match('/^([2-7a-z]{16}).onion$/', $_POST['username'], $match)){
			$stmt=$db->prepare('SELECT users.username, users.password, users.id FROM users INNER JOIN onions ON (onions.user_id=users.id) WHERE onions.onion=?;');
			$stmt->execute([$match[1]]);
			$tmp=$stmt->fetch(PDO::FETCH_NUM);
		}
		if($tmp){
			$username=$tmp[0];
			$password=$tmp[1];
			$stmt=$db->prepare('SELECT new_account.approved FROM new_account INNER JOIN users ON (users.id=new_account.user_id) WHERE users.id=?;');
			$stmt->execute([$tmp[2]]);
			if($tmp=$stmt->fetch(PDO::FETCH_NUM)){
				if(REQUIRE_APPROVAL && !$tmp[0]){
					$msg.='<p class="alert alert-danger">Error: Your account is pending admin approval. Please try again later.</p>';
				}else{
					$msg.='<p class="alert alert-danger">Error: Your account is pending creation. Please try again in a minute.</p>';
				}
				$ok=false;
			}elseif(!isset($_POST['pass']) || !password_verify($_POST['pass'], $password)){
				$msg.='<p class="alert alert-danger">Error: wrong password.</p>';
				$ok=false;
			}
		}else{
			$msg.='<p class="alert alert-danger">Error: username was not found. If you forgot it, you can enter youraccount.onion instead.</p>';
			$ok=false;
		}
	}
	if($ok){
		$_SESSION['hosting_username']=$username;
		$_SESSION['csrf_token']=sha1(uniqid());
		session_write_close();
		header('Location: home.php');
		exit;
	}
} ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Free Darkweb Hosting Service. OnionMax Hosting. Free Tor Onion Hosting">
  <meta name="author" content="Muzahid">
  <title>OnionMax Hosting - Log in to Dashboard</title>
  <link rel="icon" href="assets/img/brand/favicon.png" type="image/png">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
  <link rel="stylesheet" href="assets/vendor/nucleo/css/nucleo.css" type="text/css">
  <link rel="stylesheet" href="assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">
  <link rel="stylesheet" href="assets/css/argon.css?v=1.1.0" type="text/css">
  <link rel="canonical" href="<?php echo CANONICAL_URL . $_SERVER['SCRIPT_NAME']; ?>">
</head>
<body class="bg-default">
 <?php require_once('navout.php'); ?>
  <div class="main-content">
    <div class="header bg-gradient-primary py-7 py-lg-8 pt-lg-9">
      <div class="container">
        <div class="header-body text-center mb-7">
          <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8 px-5">
              <h1 class="text-white">Welcome!</h1>
              <p class="text-lead text-white">Use these awesome forms to login to your dashboard or create new account for free.</p>
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
          <div class="card bg-secondary border-0 mb-0">
            <div class="card-header bg-transparent pb-5">
               <div class="text-center text-muted mb-4">
                <small>Sign in to dashboard</small>
              </div>
              <form role="form" action="login.php" method="POST">
                <div class="form-group mb-3">
                  <div class="input-group input-group-merge input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-circle-08"></i></span>
                    </div>
                    <input class="form-control" placeholder="Username" type="username" name="username" value="<?php if(isset($_POST['username'])){
				         echo htmlspecialchars($_POST['username']);
			            } ?>" autofocus required>
                  </div>
                </div>
                <div class="form-group">
                  <div class="input-group input-group-merge input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                    </div>
                    <input class="form-control" placeholder="Password" name="pass" type="password" required>
                  </div>
                </div>
               <div class="form-group">
               <?php send_captcha(); ?>
                </div>
                <div class="text-center">
                  <button type="submit" class="btn btn-primary my-4">Sign in</button>
                </div>
              </form>
                  <div class="col-xl-6">
           <div class="text-center text-xl-left text-muted">
             <a href="register.php" class="font-weight-bold ml-1" >Create an account</a>
           </div>
         </div>
            </div>
          </div>     
        </div>
      </div>
    </div>
  </div>
<?php require_once('footerout.php'); ?>
</body>
</html>