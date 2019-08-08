<?php
include('../common.php');
try{
	$db=new PDO('mysql:host=' . DBHOST . ';dbname=' . DBNAME, DBUSER, DBPASS, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING, PDO::ATTR_PERSISTENT=>PERSISTENT]);
}catch(PDOException $e){
	die('No Connection to MySQL database!');
}
session_start();
$user=check_login();
if(isset($_POST['action']) && $_POST['action']==='add_db'){
	if($error=check_csrf_error()){
		die($error);
	}
	add_user_db($db, $user['id']);
}
if(isset($_POST['action']) && $_POST['action']==='del_db' && !empty($_POST['db'])){
	if($error=check_csrf_error()){
		die($error);
	} ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="OnionMax Hosting Service. Free Fast Reliable Tor Web Hosting.">
  <meta name="author" content="muzahid">
  <title>OnionMax Hosting - Delete Database</title>
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
              <h1 class="text-white">Delete Database</h1>
              <p class="text-lead text-white">This Will Delete your <?php echo htmlspecialchars($_POST['db']); ?> Database. Once Processed it cannot be undone or recovered.</p>
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
          <div class="card card-profile bg-secondary mt-5">         
            <div class="card-body pt-7 px-5">
              <div class="text-center mb-4">
                <h3>Enter Account Password to Proceed </h3>
              </div>
              <form role="form" action="home.php" method="POST">
                <div class="form-group">
                  <div class="input-group input-group-merge input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                    </div>
                    <input class="form-control" placeholder="Password" type="password" autofocus required>
                  </div>
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="db" value="<?php echo htmlspecialchars($_POST['db']); ?>">
                </div>
                <div class="text-center">
                  <button type="submit" name="action" value="del_db_2" class="btn btn-danger mt-2">Delete</button>
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
<?php
exit;
}
if(isset($_POST['action']) && $_POST['action']==='del_db_2' && !empty($_POST['db'])){
	if($error=check_csrf_error()){
		die($error);
	}
	del_user_db($db, $user['id'], $_POST['db']);
}
if(isset($_POST['action']) && $_POST['action']==='del_onion' && !empty($_POST['onion'])){
	if($error=check_csrf_error()){
		die($error);
	} ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="muzahid">
  <title>OnionMax Hosting - Delete Onion Domain</title>
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
              <h1 class="text-white">Delete Onion Domain</h1>
              <p class="text-lead text-white">This Will Delete your <?php echo htmlspecialchars($_POST['onion']); ?> Domain. it will delete all related data to this domain. Once Processed it cannot be undone or recovered.</p>
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
          <div class="card card-profile bg-secondary mt-5">         
            <div class="card-body pt-7 px-5">
              <div class="text-center mb-4">
                <h3>Enter Account Password to Proceed </h3>
              </div>
              <form role="form" action="home.php" method="POST">
                <div class="form-group">
                  <div class="input-group input-group-merge input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                    </div>
                    <input class="form-control" placeholder="Password" type="password" autofocus required>
                  </div>
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="onion" value="<?php echo htmlspecialchars($_POST['onion']); ?>">
                </div>
                <div class="text-center">
                  <button type="submit" name="action" value="del_onion_2" class="btn btn-danger mt-2">Delete</button>
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
<?php
exit;
}
if(isset($_POST['action']) && $_POST['action']==='add_onion'){
	if($error=check_csrf_error()){
		die($error);
	}
	$ok = true;
	if(isset($_REQUEST['onion_type']) && $_REQUEST['onion_type']==='custom' && isset($_REQUEST['private_key']) && !empty(trim($_REQUEST['private_key']))){
		$priv_key = trim($_REQUEST['private_key']);
		$data = private_key_to_onion($priv_key);
		$onion = $data['onion'];
		$onion_version = $data['version'];
		if(!$data['ok']){
			$msg = "<p class=\"alert alert-info\">$data[message]</p>";
			$ok = false;
		} else {
			$check=$db->prepare('SELECT null FROM onions WHERE onion=?;');
			$check->execute([$onion]);
			if($check->fetch(PDO::FETCH_NUM)){
				$msg = '<p class="alert alert-danger">Error: onion already exists.</p>';
				$ok = false;
			}
		}
	}else{
		$onion_version = 3;
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
	$stmt = $db->prepare('SELECT COUNT(*) FROM onions WHERE user_id = ?;');
	$stmt->execute([$user['id']]);
	$count = $stmt->fetch(PDO::FETCH_NUM);
	if($count[0]>=MAX_NUM_USER_ONIONS) {
		$ok = false;
	}
	if($ok){
		add_user_onion($db, $user['id'], $onion, $priv_key, $onion_version);
	}
}
if(isset($_POST['action']) && $_POST['action']==='del_onion_2' && !empty($_POST['onion'])){
	if($error=check_csrf_error()){
		die($error);
	}
	del_user_onion($db, $user['id'], $_POST['onion']);
}
if(isset($_POST['action']) && $_POST['action']==='add_domain' && !empty($_POST['domain'])){
	if($error=check_csrf_error()){
		die($error);
	}
	$error = add_user_domain($db, $user['id'], $_POST['domain']);
	if(!empty($error)){
		$msg = "<p class=\"alert alert-danger\">$error</p>";
	}else{
		enqueue_instance_reload($db);
	}
}
if(isset($_POST['action']) && $_POST['action']==='del_domain' && !empty($_POST['domain'])){
	if($error=check_csrf_error()){
		die($error);
	} ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="muzahid">
  <title>OnionMax Hosting - Delete Clearnet Domain</title>
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
              <h1 class="text-white">Delete Clearnet Domain</h1>
              <p class="text-lead text-white">This Will Delete your <?php echo htmlspecialchars($_POST['domain']); ?> Domain. it will delete all related data to this domain. Once Processed it cannot be undone or recovered.</p>
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
          <div class="card card-profile bg-secondary mt-5">         
            <div class="card-body pt-7 px-5">
              <div class="text-center mb-4">
                <h3>Enter Account Password to Proceed </h3>
              </div>
              <form role="form" action="home.php" method="POST">
                <div class="form-group">
                  <div class="input-group input-group-merge input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                    </div>
                    <input class="form-control" placeholder="Password" type="password" autofocus required>
                  </div>
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    	<input type="hidden" name="domain" value="<?php echo htmlspecialchars($_POST['domain']); ?>">
                </div>
                <div class="text-center">
                  <button type="submit" name="action" value="del_domain_2" class="btn btn-danger mt-2">Delete</button>
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
<?php
exit;
}
if(isset($_POST['action']) && $_POST['action']==='del_domain_2' && !empty($_POST['domain'])){
	if($error=check_csrf_error()){
		die($error);
	}
	del_user_domain($db, $user['id'], $_POST['domain']);
	enqueue_instance_reload($db);
}
if(isset($_REQUEST['action']) && isset($_REQUEST['onion']) && $_REQUEST['action']==='edit_onion'){
	if($error=check_csrf_error()){
		die($error);
	}
	$stmt=$db->prepare('SELECT onions.version FROM onions INNER JOIN users ON (users.id=onions.user_id) WHERE onions.onion = ? AND users.id = ? AND onions.enabled IN (0, 1);');
	$stmt->execute([$_REQUEST['onion'], $user['id']]);
	if($onion=$stmt->fetch(PDO::FETCH_NUM)){
		$stmt=$db->prepare('UPDATE onions SET enabled = ?, enable_smtp = ?, num_intros = ?, max_streams = ? WHERE onion = ?;');
		$enabled = isset($_REQUEST['enabled']) ? 1 : 0;
		$enable_smtp = isset($_REQUEST['enable_smtp']) ? 1 : 0;
		$num_intros = intval($_REQUEST['num_intros']);
		if($num_intros<3){
				$num_intros = 3;
		}elseif($onion[0]==2 && $num_intros>10){
			$num_intros = 10;
		}elseif($num_intros>20){
			$num_intros = 20;
		}
		$max_streams = intval($_REQUEST['max_streams']);
		if($max_streams<0){
			$max_streams = 0;
		}elseif($max_streams>65535){
			$max_streams = 65535;
		}
		$stmt->execute([$enabled, $enable_smtp, $num_intros, $max_streams, $_REQUEST['onion']]);
		enqueue_instance_reload($db, substr($_REQUEST['onion'], 0, 1));
	}
}
if(isset($_REQUEST['action']) && isset($_POST['domain']) && $_POST['action']==='edit_domain'){
	if($error=check_csrf_error()){
		die($error);
	}
	$stmt=$db->prepare('SELECT null FROM domains WHERE domain = ? AND user_id = ? AND enabled IN (0, 1);');
	$stmt->execute([$_POST['domain'], $user['id']]);
	if($onion=$stmt->fetch(PDO::FETCH_NUM)){
		$stmt=$db->prepare('UPDATE domains SET enabled = ? WHERE domain = ?;');
		$enabled = isset($_POST['enabled']) ? 1 : 0;
		$stmt->execute([$enabled, $_POST['domain']]);
		enqueue_instance_reload($db);
	}
} ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="OnionMax Hosting Service. Onionmax Tor Darkweb hosting. Get free Tor Hosting now.">
  <meta name="author" content="muzahid">
  <title>OnionMax Hosting | Dashboard</title>
  <link rel="icon" href="assets/img/brand/favicon.png" type="image/png">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
  <link rel="stylesheet" href="assets/vendor/nucleo/css/nucleo.css" type="text/css">
  <link rel="stylesheet" href="assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">
  <link rel="stylesheet" href="assets/css/argon.css?v=1.1.0" type="text/css">
  <style type="text/css">#custom_onion:not(checked)+#private_key{display:none;}#custom_onion:checked+#private_key{display:block;}</style>
</head>
<body>
   <?php require_once('sidenav.php'); ?>
  <div class="main-content" id="panel">
  <?php require_once('topnav.php'); ?>
    <div class="header bg-primary pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">
<?php
              echo "<h6 class=\"h2 btn btn-sm btn-default text-white d-inline-block mb-0\">USER: $user[username] <span class=\"text-success\">●</span></h6>";
    ?>        
            </div>
            <div class="col-lg-6 col-5 text-right">
              <a href="#" class="btn btn-sm btn-neutral">New</a>
              <a href="#" class="btn btn-sm btn-neutral">Options</a>
            </div>
          </div>
        <?php 	if(!empty($msg)){
				echo $msg;
			}  ?>
          <div class="row">
            <div class="col-xl-3 col-md-6">
              <div class="card card-stats">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Message (Upcoming)</h5>
                      <p class="text-center mt-3 mb-0 text-sm">This is a friendly message for you from hosting administrator ☺</p>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                        <i class="ni ni-active-40"></i>
                      </div>
                    </div>
                  </div>            
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-md-6">
              <div class="card card-stats">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Disk Usages (Upcoming)</h5>
                      <span class="h2 font-weight-bold mb-0">5,356 MB</span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                        <i class="ni ni-chart-pie-35"></i>
                      </div>
                    </div>
                  </div>
                  <p class="mt-3 mb-0 text-sm">
                    <span class="text-success mr-2"> 50%</span>
                    <span class="text-nowrap">Total Space 10 GB</span>
                  </p>
                </div>
              </div>
            </div>        
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid mt--6">
      <div class="row">
       <div class="col-xl-8">
       <div class="card-deck">
            <div class="card bg-gradient-default">          
              <div class="card-body">
                <h5 class="card-title text-uppercase text-white text-muted mb-0">Webmail</h5>
                <form action="squirrelmail/src/redirect.php" method="POST" target="_blank\">
          <?php
               echo "<input type=\"hidden\" name=\"login_username\" value=\"$user[system_account]\">";
             ?>
                  <div class="form-group">
                    <label class="form-control-label text-light" for="exampleFormControlInput1">Enter Account Password</label>
                    <input type="password" name="secretkey" class="form-control" id="exampleFormControlInput1" placeholder="Password">
                  </div>
                  <div class="text-center">
                   <button class="btn btn-icon btn-primary" type="submit" value="Login to webmail">
                     <span class="btn-inner--icon"><i class="ni ni-email-83"></i></span>
                     <span class="btn-inner--text">Log in</span>
                   </button>
                  </div>
                 </form>
              </div>
            </div>
            <div class="card bg-gradient-danger">
              <div class="card-body">
                <h5 class="card-title text-uppercase text-muted text-white mb-0">File Manager</h5>           
                 <form action="files.php" method="post" target="_blank">
                  <div class="form-group">
                    <label class="form-control-label text-light" for="exampleFormControlInput1">Enter Account Password</label>
                    <input type="password" name="ftp_pass" class="form-control" id="exampleFormControlInput1" placeholder="Password">
                  </div>
                  <div class="text-center">
                   <button class="btn btn-icon btn-primary" type="submit">
                     <span class="btn-inner--icon"><i class="ni ni-folder-17"></i></span>
                     <span class="btn-inner--text">Log in</span>
                   </button>
                  </div>
                 </form>      
              </div>   
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xl-8">
             <div class="card">
              <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col text-center">
                  <h3 class="mb-0">Your Email Address</h3>
                </div>                
              </div>
            </div>
            <div class="table-responsive">            
              <table class="table align-items-center table-flush">
                  <tr>
      <?php
                    echo "<th class=\"text-center\" scope=\"col\">$user[system_account]@" . ADDRESS . " </th>";
             ?>   
                  </tr>            
               </table>
              </div>
             </div>
          <div class="card">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Onion Domains</h3>
                </div>
                <div class="col text-right">                               
                </div>
              </div>
            </div>
            <div class="table-responsive">             
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Onion</th>
                    <th scope="col">Private Key</th>
                    <th scope="col">Enabled</th>
                    <th scope="col">SMTP Enabled</th>
                    <th scope="col">Nr of intros</th>
                    <th scope="col">Max Streams</br> per circuit</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                <?php                
                  $stmt=$db->prepare('SELECT onion, private_key, enabled, enable_smtp, num_intros, max_streams FROM onions WHERE user_id = ?;');
                  $stmt->execute([$user['id']]);
                  $count_onions = 0;
                    while($onion=$stmt->fetch(PDO::FETCH_ASSOC)){
	                 ++$count_onions;
	                 echo "<form action=\"home.php\" method=\"post\"><input type=\"hidden\" name=\"csrf_token\" value=\"$_SESSION[csrf_token]\"><input type=\"hidden\" name=\"onion\" value=\"$onion[onion]\"><tr><th scope=\"row\"><a href=\"http://$onion[onion].onion\" target=\"_blank\">$onion[onion].onion</a></th><td>";
	               if(isset($_REQUEST['show_priv'])){
		                echo "<pre>$onion[private_key]</pre>";
                      $hidepriv.='<a href="#!" class="btn btn-sm btn-primary">See all</a>';
	               }else{
		                echo '<a href="home.php?show_priv=1">Show private key</a>';
	               }                                 
                    echo '</td><td><label><input type="checkbox" name="enabled" value="1"';
	                 echo $onion['enabled'] ? ' checked' : '';
	                 echo '>Enabled</label></td>';
                  	echo '<td><label><input type="checkbox" name="enable_smtp" value="1"';
	                 echo $onion['enable_smtp'] ? ' checked' : '';
	                 echo '>Enabled</label></td>';
	                 echo '<td><input type="number" name="num_intros" min="3" max="20" value="'.$onion['num_intros'].'"></td>';
	                 echo '<td><input type="number" name="max_streams" min="0" max="65535" value="'.$onion['max_streams'].'"></td>';
	           if(in_array($onion['enabled'], [0, 1])){
		        echo '<td><button class="btn btn-sm btn-primary" type="submit" name="action" value="edit_onion">Save</button>';
		        echo '<button class="btn btn-sm btn-danger" type="submit" name="action" value="del_onion">Delete</button></td>';
	}else{
		echo '<td>Unavailable</td>';
	}
	echo '</tr></form>';
} ?>                 
                </tbody>
              </table>
            </div>
          </div>
          <div class="card">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">System Accounts</h3>
                </div>
                <div class="col text-right">
                  <a href="password.php" class="btn btn-sm btn-primary">Change Passwords</a>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <!-- Projects table -->
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Username</th>
                    <th scope="col">Host</th>
                    <th scope="col">FTP Port</th>
                    <th scope="col">SFTP Port</th>
                    <th scope="col">POP3 Port</th>
                    <th scope="col">IMAP Port</th>
                    <th scope="col">SMTP Port</th>
                  </tr>
                </thead>
                <tbody>
                <?php foreach(SERVERS as $server=>$tmp){
	                   echo "<tr><th scope=\"row\">$user[system_account]</th><td>$server</td><td>$tmp[ftp]</td><td>$tmp[sftp]</td><td>$tmp[pop3]</td><td>$tmp[imap]</td><td>$tmp[smtp]</td></tr>";
                      } ?>                  
                </tbody>
              </table>
            </div>
          </div>
              <div class="text-center">
                   <a class="btn btn-md btn-primary" href="/phpmyadmin/" target="_blank">PhpMyAdmin</a>
                   <a class="btn btn-md btn-primary" href="/adminar/" target="_blank">Adminar</a>
                  </div>
           <p></p>      
      </div>
        <div class="col-xl-4">
          <div class="card">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">MySQL Databases</h3>
                </div>
                <div class="col text-right">
    <?php 
         $stmt=$db->prepare('SELECT mysql_database FROM mysql_databases WHERE user_id = ?;');
         $stmt->execute([$user['id']]);
         $count_data= 0;
         while($mysql=$stmt->fetch(PDO::FETCH_ASSOC)){
            ++$count_data;
            }
         if($count_data<MAX_NUM_USER_DBS){
            echo '<p><form action="home.php" method="post"><input type="hidden" name="csrf_token" value="'.$_SESSION['csrf_token'].'"><button class="btn btn-sm btn-primary" type="submit" name="action" value="add_db">Add new database</button></form></p>';
             }
    ?>  
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Database</th>
                    <th scope="col">Host</th>
                    <th scope="col">User</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
   <?php 
              $stmt=$db->prepare('SELECT mysql_database FROM mysql_databases WHERE user_id = ?;');
$stmt->execute([$user['id']]);
$count_dbs = 0;
while($mysql=$stmt->fetch(PDO::FETCH_ASSOC)){
	++$count_dbs;
	echo '<form action="home.php" method="post">';
	echo '<input type="hidden" name="csrf_token" value="'.$_SESSION['csrf_token'].'">';
	echo '<input type="hidden" name="db" value="'.$mysql['mysql_database'].'">';
	echo "<tr><th scope=\"row\">$mysql[mysql_database]</th><td>localhost</td><td>$user[mysql_user]</td><td><button class=\"btn btn-sm btn-danger\" type=\"submit\" name=\"action\" value=\"del_db\">Delete</button></td></tr>";
	echo '</form>';    
     } ?>                 
              </tbody>
             </table>
            </div>
          </div>                
        </div>       
      </div>
      <div class="row">
       <div class="col-xl-8">
       <div class="card-deck">
            <div class="card ">                                   
             <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Clearnet Domains</h3>
                </div>                                             
              </div>
            </div>
                <div class="table-responsive">            
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Domain</th>
                    <th scope="col">Enabled</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
           <?php 
                   $stmt=$db->prepare('SELECT domain, enabled FROM domains WHERE user_id = ?;');
	$stmt->execute([$user['id']]);
	$count_domains = 0;
	while($domain=$stmt->fetch(PDO::FETCH_ASSOC)){
		++$count_domains;
		echo "<form action=\"home.php\" method=\"post\"><input type=\"hidden\" name=\"csrf_token\" value=\"$_SESSION[csrf_token]\"><input type=\"hidden\" name=\"domain\" value=\"$domain[domain]\"><tr><th scope=\"row\"><a href=\"https://$domain[domain]\" target=\"_blank\">$domain[domain]</a></th>";
		echo '<td><label><input type="checkbox" name="enabled" value="1"';
		echo $domain['enabled'] ? ' checked' : '';
		echo '>Enabled</label></td>';
		if(in_array($domain['enabled'], [0, 1])){
			echo '<td><button class="btn btn-sm btn-primary" type="submit" name="action" value="edit_domain">Save</button>';
			echo '<button class="btn btn-sm btn-danger" type="submit" name="action" value="del_domain">Delete</button></td>';
		}else{
			echo '<td>Unavailable</td>';
		}
		echo '</tr></form>';
     }
            ?>             
                </tbody>
              </table>
            </div>
            </div>
            <div class="card bg-gradient-sucess">              
              <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">System Logs</h3>
                </div>                                             
              </div>
            </div>
                <div class="table-responsive">         
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Date</th>
                    <th scope="col">Access Log</th>
                    <th scope="col">Error Log</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th scope="row">
                      Today
                    </th>
                    <td>
                     <a href="log.php?type=access&amp;old=0" target="_blank">access.log</a>
                    </td>
                    <td>
                     <a href="log.php?type=error&amp;old=0" target="_blank">error.log</a>
                    </td>
                  </tr>
                  <tr>
                    <th scope="row">
                      Yesterday
                    </th>
                    <td>
                      <a href="log.php?type=access&amp;old=1" target="_blank">access.log</a>
                    </td>
                    <td>
                     <a href="log.php?type=error&amp;old=1" target="_blank">error.log</a>
                    </td>
                  </tr>          
                </tbody>
              </table>
            </div>       
            </div>
          </div>
        </div>
      </div>
          <div class="row">
       <div class="col-xl-8">
       <div class="card-deck">
            <div class="card bg-gradient-success">          
              <div class="card-body">
                <h5 class="card-title text-uppercase text-white text-muted mb-0">Add New Onion Domain</h5>
          <?php 
             if($count_onions<MAX_NUM_USER_ONIONS){
             echo '<form action="home.php" method="POST">';
             echo "<input type=\"hidden\" name=\"csrf_token\" value=\"$_SESSION[csrf_token]\">";
             echo '<div class="custom-control custom-radio mb-3">';                     
             echo '<input name="onion_type" class="custom-control-input" value="3" id="radio1" type="radio"';
             echo (!isset($_POST['onion_type']) || isset($_POST['onion_type']) && $_POST['onion_type']==3) ? ' checked' : '';
             echo '>';
             echo '<label class="custom-control-label" for="radio1">Random v3 Onion</label>';               
             echo '</div>';
             echo '<div class="custom-control custom-radio mb-3">';
             echo '<input name="onion_type" class="custom-control-input" value="2" id="radio2" type="radio"';
             echo isset($_POST['onion_type']) && $_POST['onion_type']==2 ? ' checked' : '';
             echo '>';
             echo '<label class="custom-control-label" for="radio2">Random v2 Onion</label>';
             echo '</div>';
             echo '<div class="custom-control custom-radio mb-3">';
             echo '<label class="custom-control-label" for="custom_onion">Custom Private Key';
             echo '<input id="custom_onion" name="onion_type" class="custom-control-input" value="custom" type="radio"';
             echo isset($_POST['onion_type']) && $_POST['onion_type']==='custom' ? ' checked' : '';
             echo '>';
             echo '<textarea id="private_key" class="form-control" placeholder="Enter Your Private Key" name="private_key" rows="3" cols="28">';
             echo isset($_REQUEST['private_key']) ? htmlspecialchars($_REQUEST['private_key']) : ''; 
             echo '</textarea>';                      
             echo '</label>';
             echo '</div>';                
             echo '<div class="text-center">';
             echo '<button type="submit" name="action" class="btn btn-icon btn-primary" value="add_onion" type="button">';
             echo '<span class="btn-inner--icon"><i class="ni ni-fat-add"></i></span>';
             echo '<span class="btn-inner--text">Add Onion</span>';
             echo '</button>';
             echo '</div>';
             echo '</form>';
              } else{
             echo '<div class="text-center">';           
             echo '<p class="h1 font-weight-bold mb-0 text-primary">! Oops </p>';
             echo '<h4 class="text-primary">You have already added maximum number of onion domain</h4>';
             echo '<h4 class="text-muted text-white">if you need more contact me</h4>';            
             echo '<a href="contact.php"><button class="btn btn-primary" type="button">Contact</button></a>';
             echo '</div>';
              }
          ?>
              </div>
            </div>
            <div class="card bg-gradient-success">             
              <div class="card-body">
                <h5 class="card-title text-uppercase text-muted text-white mb-0">Add New Clearnet Domain</h5>           
             <?php 
                 if($count_domains<MAX_NUM_USER_DOMAINS){
                  echo "<form action=\"home.php\" method=\"post\"><input type=\"hidden\" name=\"csrf_token\" value=\"$_SESSION[csrf_token]\">";
                   echo '<div class="form-group">';
                    echo '<div class="text-center">';
                         echo '<h4 class="text-primary">You can use any available subdomain of onionmax.com</br>Contact me for finishing setup</h4>';
                      echo '</div>';
                        echo '<label class="form-control-label" for="exampleFormControlInput1">Enter Your Own Domain</label>';    
                    echo '<input type="text" name="domain" class="form-control" id="exampleFormControlInput1" placeholder="Enter Clearnet Domain" value="';
                    echo isset($_POST['domain']) ? htmlspecialchars($_POST['domain']) : '';
		              echo '">';                   
                  echo '<label>  </label>';
                  echo '<div class="text-center">';
                   echo '<button class="btn btn-icon btn-primary" type="submit" name="action" value="add_domain">';
                     echo '<span class="btn-inner--icon"><i class="ni ni-fat-add"></i></span>';
                     echo '<span class="btn-inner--text">Add Domain</span>';
                  echo '</button>';
                  echo '</div>';                 
                 echo '</form>';
                }else{
             echo '<div class="text-center">';           
             echo '<p class="h1 font-weight-bold mb-0 text-primary">! Oops </p>';
             echo '<h4 class="text-primary">You have already added maximum number of Clearnet domain</h4>';
             echo '<h4 class="text-muted text-white">if you need more contact me</h4>';             
             echo '<a href="contact.php"><button class="btn btn-primary" type="button">Contact</button></a>';
             echo '</div>';
              }
             ?>     
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