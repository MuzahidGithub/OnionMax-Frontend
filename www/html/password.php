<?php
include('../common.php');
try{
	$db=new PDO('mysql:host=' . DBHOST . ';dbname=' . DBNAME, DBUSER, DBPASS, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING, PDO::ATTR_PERSISTENT=>PERSISTENT]);
}catch(PDOException $e){
	die('No Connection to MySQL database!');
}
session_start();
$user=check_login();
if(!isset($_REQUEST['type'])){
	$_REQUEST['type']='acc';
}
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
	if($error=check_csrf_error()){
		$msg.='<p class="alert alert-danger">'.$error.'</p>';
	}
	if(!isset($_POST['pass']) || !password_verify($_POST['pass'], $user['password'])){
		$msg.='<p class="alert alert-danger">Wrong password.</p>';
	}elseif(!isset($_POST['confirm']) || !isset($_POST['newpass']) || $_POST['newpass']!==$_POST['confirm']){
		$msg.='<p class="alert alert-danger">Wrong password.</p>';
	}else{
		if($_REQUEST['type']==='acc'){
			$hash=password_hash($_POST['newpass'], PASSWORD_DEFAULT);
			$stmt=$db->prepare('UPDATE users SET password=? WHERE id=?;');
			$stmt->execute([$hash, $user['id']]);
			$msg.='<p class="alert alert-success">Successfully changed account password.</p>';
		}elseif($_REQUEST['type']==='sys'){
			$stmt=$db->prepare('INSERT INTO pass_change (user_id, password) VALUES (?, ?);');
			$hash=get_system_hash($_POST['newpass']);
			$stmt->execute([$user['id'], $hash]);
			$msg.='<p class="alert alert-success">Successfully changed system account password, change will take affect within the next minute.</p>';
		}elseif($_REQUEST['type']==='sql'){
			$stmt=$db->prepare("SET PASSWORD FOR '$user[mysql_user]'@'%'=PASSWORD(?);");
			$stmt->execute([$_POST['newpass']]);
			$db->exec('FLUSH PRIVILEGES;');
			$msg.='<p class="alert alert-success">Successfully changed MySQL password.</p>';
		}else{
			$msg.='<p class="alert alert-danger">Couldn\'t update password: Unknown reset type.</p>';
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Free Darkweb Hosting Service. OnionMax Hosting. Free Tor Onion Hosting">
  <meta name="author" content="Muzahid">
  <title>OnionMax Hosting - Change Passwords</title>
  <link rel="icon" href="assets/img/brand/favicon.png" type="image/png">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
  <link rel="stylesheet" href="assets/vendor/nucleo/css/nucleo.css" type="text/css">
  <link rel="stylesheet" href="assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">
  <link rel="stylesheet" href="assets/css/argon.css?v=1.1.0" type="text/css">
  <link rel="canonical" href="<?php echo CANONICAL_URL . $_SERVER['SCRIPT_NAME']; ?>">
</head>
<body class="bg-default">
  <div class="main-content">
    <div class="header bg-gradient-primary py-7 py-lg-8 pt-lg-9">
      <div class="container">
        <div class="header-body text-center mb-7">
          <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8 px-5">
              <h1 class="text-white">Change Passwords</h1>
              <p class="text-lead text-white">Use these awesome forms to change your account passwords. Please Remember your passwords, we don't have any recovery option.</p>
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
                <small>Change Your Passwords</small>
              </div>
              <form role="form" action="password.php" method="POST">
        <?php
                    echo '<input type="hidden" name="csrf_token" value="'.$_SESSION['csrf_token'].'">';
         ?>
                <div class="form-group mb-3">
                  <div class="input-group input-group-merge input-group-alternative">
                   <select name="type" class="form-control">
          <?php
                      echo '<option value="acc"';
if($_REQUEST['type']==='acc'){
	echo ' selected';
}
echo '>Account</option>';
echo '<option value="sys"';
if($_REQUEST['type']==='sys'){
	echo ' selected';
}
echo '>System account</option>';
echo '<option value="sql"';
if($_REQUEST['type']==='sql'){
	echo ' selected';
}
echo '>MySQL</option>';
              ?>       
                  </select>
                  </div>
                </div>
                <div class="form-group">
                  <div class="input-group input-group-merge input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                    </div>
                    <input class="form-control" placeholder="Current Password" name="pass" type="password" required>
                  </div>
                </div>
                <div class="form-group">
                  <div class="input-group input-group-merge input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                    </div>
                    <input class="form-control" placeholder="New Password" name="newpass" type="password" required>
                  </div>
                </div>
                <div class="form-group">
                  <div class="input-group input-group-merge input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                    </div>
                    <input class="form-control" placeholder="Confirm Password" name="confirm" type="password" required>
                  </div>
                </div>              
                <div class="text-center">
                  <button type="submit" class="btn btn-primary my-4" value="Reset">Change</button>
                </div>
              </form>
                <div class="col-xl-6">
                 <div class="text-center text-xl-left text-muted">
                  <a href="home.php" class="font-weight-bold ml-1" >Return to Dashboard</a>
                 </div>
               </div>
            </div>
          </div>     
        </div>
      </div>
    </div>
  </div>
</body>
</html>
