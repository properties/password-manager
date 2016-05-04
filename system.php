<?php

  /*
     Password Manager

     by Matthew (github: properties)
     version 1.6.4
  */

  include 'lib/config.php';

  error_reporting(0);

  ini_set('session.gc_maxlifetime', 3600);
  session_set_cookie_params(3600);
  session_start();

  if($_SESSION['login'] == 1 && $_SESSION['IP'] != $_SERVER['REMOTE_ADDR'])
  {
    $_SESSION = array();
    session_destroy();
    exit();
  }

  define('ACTION', $_POST['ACTION']);
  define('MAINPASSWORD', $_SESSION['MAINPASSWORD']);
  define('PHRASE', $_SESSION['PHRASE']);
  define('AES_256_CBC', 'aes-256-cbc');

  function encryptAES($encryptData) {
    return openssl_encrypt($encryptData, AES_256_CBC, PHRASE, 0, MAINPASSWORD);
  }

  function decryptAES($decryptData) {
    return openssl_decrypt($decryptData, AES_256_CBC, PHRASE, 0, MAINPASSWORD);
  }

  function randomChars($totalLenght, $allChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789")
  {
      $endString = "";
      $totalChars = strlen($allChars);
      for ($i = 0; $i < $totalLenght; $i++)
      {
          $randomPick = mt_rand(1, $totalChars);
          $randomChar = $allChars[$randomPick-1];
          $endString .= $randomChar;
      }
      return $endString;
  }


  if(ACTION == "addAccount")
  {

    $databaseConnection = new PDO("mysql:host=" . dbHost . ";dbname=" .dbName, dbUser, dbPassword);
    $addAccount = $databaseConnection->prepare("INSERT INTO `pwd_accs` (email, username, password, extra, site) VALUES (:email, :username, :password, :extra, :site)");

    $addAccount->bindParam(':email', encryptAES($_POST["email"]));
    $addAccount->bindParam(':username', encryptAES($_POST["username"]));
    $addAccount->bindParam(':password', encryptAES($_POST["password"]));
    $addAccount->bindParam(':extra', encryptAES($_POST["extra"]));
    $addAccount->bindParam(':site', $_POST["url"]);
    $addAccount->execute();

    $returnJson["Info"] = 'addAccount -> succes';
  }
  else if(ACTION == "addSite")
  {

    $databaseConnection = new PDO("mysql:host=" . dbHost . ";dbname=" .dbName, dbUser, dbPassword);
    $addSite = $databaseConnection->prepare("INSERT INTO `pwd_sites` (name, url) VALUES (:name, :url)");
    $addSite->bindParam(':name', htmlspecialchars($_POST["name"]));
    $addSite->bindParam(':url', htmlspecialchars($_POST["url"]));
    $addSite->execute();

    $returnJson["Info"] = 'addSite -> succes';

  }
  else if(ACTION == "getSites")
  {

    $databaseConnection = new PDO("mysql:host=" . dbHost . ";dbname=" .dbName, dbUser, dbPassword);
    $getSites = $databaseConnection->prepare("SELECT * from `pwd_sites`");
    $getSites->execute();
    $allSites = $getSites->fetchAll();

    foreach($allSites as $siteInfo) {

      $getSiteAmount = $databaseConnection->prepare("SELECT * from `pwd_accs` WHERE `site` = :url");
      $getSiteAmount->bindParam(':url', $siteInfo["url"]);
      $getSiteAmount->execute();
      $siteAmount = $getSiteAmount->rowCount();

      if(empty($siteInfo["img"])) {
        $siteInfo["img"] = 'http://'. htmlspecialchars($siteInfo["url"]) .'/favicon.ico';
      }

      $htmlCode .= '<li class="im_dialog_wrap"><a class="im_dialog" onclick="getAccounts(\''. htmlspecialchars($siteInfo["url"]) .'\')">
      <div class="im_dialog_meta pull-right text-right"><div class="im_dialog_date">'. htmlspecialchars($siteInfo["url"]) .'</div></div>
      <div class="im_dialog_photo pull-left" style="border-radius:0%;"><img class="im_dialog_photo" style="border-radius:0%;" src="'. htmlspecialchars($siteInfo["img"]) .'"></div> <div class="im_dialog_message_wrap">
      <div class="im_dialog_peer"><span>'. htmlspecialchars($siteInfo["name"]) .'</span></div><div class="im_dialog_message_notyping"><div class="im_dialog_message"><span><span><span class="im_dialog_chat_from_wrap"><span class="im_dialog_chat_from">Accounts</span><span>:</span></span></span></span><span>
      <span class="im_short_message_text">'. htmlspecialchars($siteAmount) .'</span></span></div></div></div></a></li>';

      $returnJson["Info"] = 'getSites -> succes';
      $returnJson["Html"] = $htmlCode;

    }

  }
  else if(ACTION == "getAccounts")
  {

    $databaseConnection = new PDO("mysql:host=" . dbHost . ";dbname=" .dbName, dbUser, dbPassword);
    $getAccounts = $databaseConnection->prepare("SELECT * from `pwd_accs` WHERE `site` = :url");
    $getAccounts->bindParam(':url', $_POST["url"]);
    $getAccounts->execute();
    $allAccounts = $getAccounts->fetchall();

    foreach($allAccounts as $accountInfo)
    {

      for ($i = 1; $i <= strlen(decryptAES($accountInfo["password"])); $i++) $hiddenPassword .= "•";

      $htmlCode .= '<div class="md_modal_iconed_section_wrap  md_modal_iconed_section_link"style="border-bottom: 1px solid #ebebeb;"><i class="md_modal_section_icon md_modal_section_icon_more"></i><div class="md_modal_section_select_wrap">
      <div class="dropdown md_modal_section_select"><button data-clipboard-text="' . htmlspecialchars(decryptAES($accountInfo["email"])) . '" class="btn btn-link dropdown-toggle copy">Copy</button></div>
      <div class="md_modal_section_param_name"><span style="color: #3a6d99;">Email:</span> ' . htmlspecialchars(decryptAES($accountInfo["email"])) . '</div></div><div class="md_modal_section_select_wrap">
      <div class="dropdown md_modal_section_select"><button data-clipboard-text="' . htmlspecialchars(decryptAES($accountInfo["username"])) . '" class="btn btn-link dropdown-toggle copy">Copy</button></div>
      <div class="md_modal_section_param_name"><span style="color: #3a6d99;">Username:</span> ' . htmlspecialchars(decryptAES($accountInfo["username"])) . '</div></div><div class="md_modal_section_select_wrap">
      <div class="dropdown md_modal_section_select"><button data-clipboard-text="'. htmlspecialchars(decryptAES($accountInfo["password"])) . '" class="btn btn-link dropdown-toggle copy">Copy</button></div>
      <div class="md_modal_section_param_name"><span style="color: #3a6d99;">Password:</span> '. $hiddenPassword . '</div></div><div class="md_modal_section_select_wrap">
      <div class="dropdown md_modal_section_select"><button data-clipboard-text="' . htmlspecialchars(decryptAES($accountInfo["extra"])) . '" class="btn btn-link dropdown-toggle copy">Copy</button></div>
      <div class="md_modal_section_param_name"><span style="color: #3a6d99;">Extra:</span> ' . htmlspecialchars(decryptAES($accountInfo["extra"])) . '</div></div></div>';

    }

    $returnJson["Info"] = 'getAccounts -> succes';
    $returnJson["Html"] = $htmlCode;

  }
  else if(ACTION == "loginAccount")
  {

    require_once 'lib/GoogleAuthenticator.php';
    $googleAuth = new PHPGangsta_GoogleAuthenticator();
    $checkResult = $googleAuth->verifyCode(secret2FA, $_POST["C2FA"], 2);

    if ($checkResult) {

      $databaseConnection = new PDO("mysql:host=" . dbHost . ";dbname=" .dbName, dbUser, dbPassword);
      $getSettings = $databaseConnection->prepare("SELECT * from `pwd_settings` WHERE `name` = 'checkpassword'");
      $getSettings->execute();
      $allSettings = $getSettings->fetchall();

      foreach($allSettings as $settingInfo)
      {
        $checkPassword = openssl_decrypt($settingInfo["value"], AES_256_CBC, $_POST["PHRASE"], 0, $_POST["MAINPASSWORD"]);
        if($checkPassword == $settingInfo["value2"])
        {

          $_SESSION['MAINPASSWORD'] = $_POST["MAINPASSWORD"];
          $_SESSION['PHRASE'] = $_POST["PHRASE"];
          $_SESSION['IP'] = $_SERVER['REMOTE_ADDR'];
          $_SESSION['login'] = 1;
          $returnJson["Info"] = 'loginAccount -> succes';

        }
        else
        {
          
          $htmlCode = '<p style="color: #c7254e;background-color: #f9f2f4;width: 500px;padding: 2px 4px;font-size: 90%;border-radius: 4px;">Mainpassword or Phrase is incorrect</p>';
          $returnJson["Failed"] = 'checkPassword -> failed';
          
        }
      }
    }
    else
    {
      $htmlCode = '<p style="color: #c7254e;background-color: #f9f2f4;width: 500px;padding: 2px 4px;font-size: 90%;border-radius: 4px;">2FA Code is not valid!</p>';
      $returnJson["Failed"] = '2FA -> failed';
    }

    $returnJson["Html"] = $htmlCode;

  }
  else if(ACTION == "installSite")
  {

    $secretAuth = $_POST["secretAuth"];
    $mainPassword = $_POST["mainPassword"];
    $phrasePassword = $_POST["phrasePassword"];

    $randomCharacters = randomChars(16);
    $randomEncryption = openssl_encrypt($randomCharacters, AES_256_CBC, $phrasePassword, 0, $mainPassword);

    $databaseConnection = new PDO("mysql:host=" . dbHost . ";dbname=" .dbName, dbUser, dbPassword);
    $installSQL = $databaseConnection->prepare("CREATE TABLE IF NOT EXISTS `pwd_accs` (`id` int(11) NOT NULL AUTO_INCREMENT,`email` varchar(535) NOT NULL,`username` varchar(535) NOT NULL,`password` varchar(535) NOT NULL,`extra` varchar(535) NOT NULL,`site` varchar(535) NOT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;CREATE TABLE IF NOT EXISTS `pwd_settings` (`id` int(11) NOT NULL AUTO_INCREMENT,`name` varchar(535) NOT NULL,`value` varchar(535) NOT NULL,`value2` varchar(535) NOT NULL,`info` varchar(535) NOT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;CREATE TABLE IF NOT EXISTS `pwd_sites` (`id` int(11) NOT NULL AUTO_INCREMENT,`name` varchar(535) NOT NULL,`url` varchar(535) NOT NULL,`img` varchar(535) NOT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=49;INSERT INTO `pwd_settings` (name, value, value2) VALUES ('checkpassword', :value, :value2)");
    $installSQL->bindParam(':value', $randomEncryption);
    $installSQL->bindParam(':value2', $randomCharacters);
    $installSQL->execute();

    $currentConfig = file_get_contents('lib/config.php');
    file_put_contents('lib/config.php', $currentConfig . '  define("secret2FA", "'. $secretAuth .'");');

    $returnJson["Info"] = 'installSite -> succes';

    unlink('install.html');

  }
  else if(ACTION == "generateQR")
  {

    require_once 'lib/GoogleAuthenticator.php';
    $googleAuth = new PHPGangsta_GoogleAuthenticator();
    $secretQR = $googleAuth->createSecret();
    $imageQR =  '<img src="'.$googleAuth->getQRCodeGoogleUrl(siteUrl, $secretQR).'">';
    $secretAuth = '<input hidden id="secretAuth" value="'.$secretQR.'">';

    $returnJson["secretQR"] = $secretAuth;
    $returnJson["imageQR"] = $imageQR;
    $returnJson["Info"] = 'generateQR -> succes';

  }
  else if(ACTION == "checkSession")
  {

    if($_SESSION['login'] == 1)
    {
      $returnJson["valid"] = 'session -> valid';
    }

    $returnJson["Info"] = 'checkSession -> succes';

  }

  echo json_encode($returnJson, JSON_PRETTY_PRINT);
