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
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Free Tor Onion Web Hosting. Get your free onion hosting now. Full Featured, Secured, Fast.">
  <meta name="author" content="Muzahid">
  <title>OnionMax Hosting - Create an account</title>
  <link rel="icon" href="assets/img/brand/favicon.png" type="image/png">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
  <link rel="stylesheet" href="assets/vendor/nucleo/css/nucleo.css" type="text/css">
  <link rel="stylesheet" href="assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">
  <link rel="stylesheet" href="assets/css/argon.css?v=1.1.0" type="text/css">
  <link rel="canonical" href="<?php echo CANONICAL_URL . $_SERVER['SCRIPT_NAME']; ?>">
  <style type="text/css">#custom_onion:not(checked)+#private_key{display:none;}#custom_onion:checked+#private_key{display:block;}</style>
</head>
<body class="bg-default">
<?php require_once('navout.php'); ?>
<?php
if($_SERVER['REQUEST_METHOD']==='POST'){
	$ok=true;
	$onion='';
	$onion_version=3;
	$public_list=0;
	$php=0;
	$autoindex=0;
	$hash='';
	$priv_key='';
   $msg='';
	if(isset($_POST['public']) && $_POST['public']==1){
		$public_list=1;
	}
	if(isset($_POST['php']) && array_key_exists($_POST['php'], PHP_VERSIONS)){
		$php = $_POST['php'];
	}
	if(isset($_POST['autoindex']) && $_POST['autoindex']==1){
		$autoindex=1;
	}
	if($error=check_captcha_error()){
		$msg.="<p class='alert alert-danger'>$error</p>";
		$ok=false;
	}elseif(empty($_POST['pass'])){
		$msg.='<p class="alert alert-danger">Error: password empty.</p>';
		$ok=false;
	}elseif(empty($_POST['passconfirm']) || $_POST['pass']!==$_POST['passconfirm']){
		$msg.='<p class="alert alert-danger">Error: password confirmation does not match.</p>';
		$ok=false;
	}elseif(empty($_POST['username'])){
		$msg.='<p class="alert alert-danger">Error: username empty.</p>';
		$ok=false;
	}elseif(preg_match('/[^a-z0-9\-_\.]/', $_POST['username'])){
		$msg.='<p class="alert alert-danger">Error: username may only contain characters that are in the rage of a-z (lower case) - . _ and 0-9.</p>';
		$ok=false;
	}elseif(strlen($_POST['username'])>50){
		$msg.='<p class="alert alert-danger">Error: username should not be longer than 50 characters.</p>';
		$ok=false;
	}else{
		$stmt=$db->prepare('SELECT null FROM users WHERE username=?;');
		$stmt->execute([$_POST['username']]);
		if($stmt->fetch(PDO::FETCH_NUM)){
			$msg.='<p class="alert alert-danger">Error: this username is already registered.</p>';
			$ok=false;
		}
	}
	if($ok){
		if(isset($_REQUEST['onion_type']) && $_REQUEST['onion_type']==='custom' && isset($_REQUEST['private_key']) && !empty(trim($_REQUEST['private_key']))){
			$priv_key = trim($_REQUEST['private_key']);
			$data = private_key_to_onion($priv_key);
			$onion = $data['onion'];
			$onion_version = $data['version'];
			if(!$data['ok']){
				$msg.="<p class='alert alert-danger'>$data[message]</p>";
				$ok = false;
			} else {
				$check=$db->prepare('SELECT null FROM onions WHERE onion=?;');
				$check->execute([$onion]);
				if($check->fetch(PDO::FETCH_NUM)){
				   $msg.='<p class="alert alert-danger">Error onion already exists.</p>';
					$ok = false;
				}
			}
		}else{
			if(isset($_REQUEST['onion_type']) && in_array($_REQUEST['onion_type'], [2, 3])){
				$onion_version = $_REQUEST['onion_type'];
			}
			$check=$db->prepare('SELECT null FROM onions WHERE onion=?;');
			do{
				$data = generate_new_onion($onion_version);
				$priv_key = $data['priv_key'];
				$onion = $data['onion'];
				$onion_version = $data['version'];
				$check->execute([$onion]);
			}while($check->fetch(PDO::FETCH_NUM));
		}
		$priv_key=trim(str_replace("\r", '', $priv_key));
		$hash=password_hash($_POST['pass'], PASSWORD_DEFAULT);
	}
	$check=$db->prepare('SELECT null FROM users WHERE dateadded>?;');
	$check->execute([time()-60]);
	if($ok && $check->fetch(PDO::FETCH_NUM)){
		$msg.='<p class="alert alert-danger">To prevent abuse a site can only be registered every 60 seconds, but one has already been registered within the last 60 seconds. Please try again.</p>';
		$ok=false;
	}elseif($ok){
		$mysql_user = add_mysql_user($db, $_POST['pass']);
		$stmt=$db->prepare('INSERT INTO users (username, system_account, password, dateadded, public, php, autoindex, mysql_user, instance) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);');
		$stmt->execute([$_POST['username'], substr("$onion.onion", 0, 32), $hash, time(), $public_list, $php, $autoindex, $mysql_user, get_new_tor_instance($db)]);
		$user_id = $db->lastInsertId();
		$stmt = $db->prepare('INSERT INTO disk_quota (user_id, quota_size, quota_files) VALUES (?, ?, ?);');
		$stmt->execute([$user_id, DEFAULT_QUOTA_SIZE, DEFAULT_QUOTA_FILES]);
		add_user_onion($db, $user_id, $onion, $priv_key, $onion_version);
		add_user_db($db, $user_id);
		$stmt=$db->prepare('INSERT INTO new_account (user_id, password) VALUES (?, ?);');
		$stmt->execute([$user_id, get_system_hash($_POST['pass'])]);
		if(EMAIL_TO!==''){
			$title="A new hidden service $onion has been created";
			$emailmsg="A new hidden service http://$onion.onion has been created";
			$headers="From: www-data <www-data>\r\nContent-Type: text/plain; charset=UTF-8\r\n";
			mail(EMAIL_TO, $title, $emailmsg, $headers);
		}
		$msg.="<p class='alert alert-success'>Your onion domain <a href=\"http://$onion.onion\" target=\"_blank\">$onion.onion</a> has successfully been created. Please wait up to one minute until the changes have been processed. You can then login <a href=\"login.php\">here</a>.</p>";
	}
}
?>
  <div class="main-content">
    <div class="header bg-gradient-primary py-7 py-lg-8 pt-lg-9">
      <div class="container">
        <div class="header-body text-center mb-7">
          <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8 px-5">
              <h1 class="text-white">Create an account</h1>
              <p class="text-lead text-white">Use these awesome forms to create a free hosting account or Log in to your existing account.</p>
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
        <div class="col-lg-6 col-md-8">
        <?php echo $msg; ?>
          <div class="card bg-secondary border-0">
            <div class="card-header bg-transparent pb-5">
           <div class="text-center text-muted mb-4">
                <small>Sign up for free Hosting</small>
              </div>
              <form role="form" action="register.php" method="POST">
                <div class="form-group">
                  <div class="input-group input-group-merge input-group-alternative mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-hat-3"></i></span>
                    </div>
                    <input class="form-control" placeholder="Username" name="username" type="text" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                  </div>
                </div>
                <div class="form-group">
                  <div class="input-group input-group-merge input-group-alternative mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                    </div>
                    <input class="form-control" placeholder="Password" name="pass" type="password" required >
                  </div>
                </div>
                <div class="form-group">
                  <div class="input-group input-group-merge input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                    </div>
                    <input class="form-control" placeholder="Confirm Password" name="passconfirm" type="password" required >
                  </div>
                </div>
                <div class="form-group">
                 <div class="input-group input-group-merge input-group-alternative">
               <select name="php" class="form-control">
                 <option value="0">No PHP</option>
                 <?php
                 foreach(PHP_VERSIONS as $key => $version){
             	  echo "<option value=\"$key\"";
	             echo ((isset($_POST['php']) && $_POST['php']==$key) || (!isset($_POST['php']) && $version===DEFAULT_PHP_VERSION)) ? ' selected' : '';
	             echo ">PHP $version</option>";
                }
               ?>
              </select>
                 </div>
                 </div>
               <div class="form-group">
               <?php
                send_captcha();
                if($_SERVER['REQUEST_METHOD']!=='POST' || (isset($public_list) && $public_list==1)){
               	$public_list=' checked';
                }else{
	              $public_list='';
                }
               if(isset($autoindex) && $autoindex==1){
	              $autoindex=' checked';
               }else{
	             $autoindex='';
               }
               ?>
              </div>
               <div class="text-muted"><small>Select Onion Address Type</small></div>
                <div>
                <div>
                </div>
                </div>
                  <div class="custom-control custom-radio mb-3">                     
                    <input name="onion_type" class="custom-control-input" value="3" id="radio1" type="radio"<?php echo (!isset($_POST['onion_type']) || isset($_POST['onion_type']) && $_POST['onion_type']==3) ? ' checked' : ''; ?>>
                    <label class="custom-control-label" for="radio1">Random v3 Onion</label>                
                   </div>
                   <div class="custom-control custom-radio mb-3">
                     <input name="onion_type" class="custom-control-input" value="2" id="radio2" type="radio"<?php echo isset($_POST['onion_type']) && $_POST['onion_type']==2 ? ' checked' : ''; ?>>
                     <label class="custom-control-label" for="radio2">Random v2 Onion</label>
                    </div>
                     <div class="custom-control custom-radio mb-3">
                        <label class="custom-control-label" for="custom_onion">Custom Private Key
                        <input id="custom_onion" name="onion_type" class="custom-control-input" value="custom" type="radio"<?php echo isset($_POST['onion_type']) && $_POST['onion_type']==='custom' ? ' checked' : ''; ?>>
                        <textarea id="private_key" class="form-control" placeholder="Enter Your Private Key" name="private_key" rows="3" cols="28">
                        <?php echo isset($_REQUEST['private_key']) ? htmlspecialchars($_REQUEST['private_key']) : ''; ?>
                        </textarea>                      
                        </label>
                        </div>
                  <div class="text-muted"><small>Please Check Rules & Terms Before Agree</small></div>
                  <div class="row my-4">
                  <div class="col-12">
                    <div class="custom-control custom-control-alternative custom-checkbox">
                      <input class="custom-control-input" id="customCheckRegister" type="checkbox" name="public" value="1"<?php echo $public_list; ?>>
                      <label class="custom-control-label" for="customCheckRegister">
                        <span class="text-muted">Publish Site On <a href="list.php" target="_blank">Hosted Site List</a></span>
                      </label>
                    </div>
                  </div>
                </div>
                 <div class="row my-4">
                  <div class="col-12">
                    <div class="custom-control custom-control-alternative custom-checkbox">
                      <input class="custom-control-input" id="customCheckRegister2" type="checkbox" name="autoindex" value="1"<?php echo $autoindex; ?>>
                      <label class="custom-control-label" for="customCheckRegister2">
                        <span class="text-muted">Enable Auto Index (listing of files)</span>
                      </label>
                    </div>
                  </div>
                </div>
             <div class="row my-4">
                  <div class="col-12">
                    <div class="custom-control custom-control-alternative custom-checkbox">
                      <input class="custom-control-input" id="customCheckRegister3" type="checkbox" name="accept_privacy" required>
                      <label class="custom-control-label" for="customCheckRegister3">
                        <span class="text-muted">I agree with <a href="terms.php" target="_blank">Terms & Condition</a></span>
                      </label>
                    </div>
                  </div>
                </div>
                <div class="text-center">
                  <button type="submit" value="Register" class="btn btn-primary mt-4">Create account</button>
                </div>
              </form>
              <h4> </h4>
                <div class="col-xl-6">
                <div class="text-center text-xl-left text-muted">
                <a href="login.php" class="font-weight-bold ml-1" >Sign in</a>
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