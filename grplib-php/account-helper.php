<?php

function actLoginCheck($user_id, $password) {
global $mysql;
$search_user = $mysql->query('SELECT * FROM people WHERE people.user_id = "'.$user_id.'" LIMIT 1');
      if(!$search_user ||$search_user->num_rows == 0) {
return 'none'; }
$user = $search_user->fetch_assoc();
		if($user['ban_status'] >= 4) {
return 'ban'; }

$parts = explode('$', $user['user_pass']);
if(crypt($password, sprintf('$%s$%s$%s$', $parts[1], $parts[2], $parts[3])) != $user['user_pass']) {
if(password_hash($_POST['password'],PASSWORD_BCRYPT,['salt'=>'zvHy85=EZLaw8?5ct!Ov9YEiP(Gi)itI']) != $user['user_pass']) {
return 'fail'; } }

return $user;

}

function setLoginVars($user, $login) {
if($login == true) {
      $_SESSION['signed_in'] = true;       
	  $_SESSION['pid'] = $user['pid'];
      $_SESSION['user_id'] = $user['user_id'];
} else {
      $_SESSION['signed_in'] = false;       
	  $_SESSION['pid'] = null;
      $_SESSION['user_id'] = null;
	}
}

function check_reCAPTCHA($secret) {
if(empty($_POST['g-recaptcha-response'])) {
return false;
	}
            $ch = curl_init();
			curl_setopt_array($ch, [CURLOPT_URL=>'https://www.google.com/recaptcha/api/siteverify', CURLOPT_POST=>true, CURLOPT_HEADER=>true, 
			CURLOPT_HTTPHEADER=>['Content-Type: application/x-www-form-urlencoded'], 
			CURLOPT_POSTFIELDS=>'secret='.$secret.'&response='.urlencode($_POST['g-recaptcha-response']).'&remoteip='.urlencode($_SERVER['REMOTE_ADDR']), 
			CURLOPT_RETURNTRANSFER=>true]);
            $response = curl_exec($ch);
            $body = substr($response, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
            curl_close($ch);
            if(json_decode($body, true)['success'] != true) {
			return false;
			}
return true;
}