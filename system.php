<?php

  /*
     Password Manager

     by Matthew (github: properties)
     version 1.3
  */

  //session_start();
  define('ACTION', $_POST['ACTION']);
  define('MAINPASSWORD', $_SESSION['MAINPASSWORD']);
  define('PHRASE', $_SESSION['PHRASE']);
  define('AES_256_CBC', 'aes-256-cbc');

  function encryptAES($encryptData) {
    return openssl_encrypt($encryptData, AES_256_CBC, sha1(PHRASE), 1, MAINPASSWORD);
  }

  function decryptAES($decryptData) {
    return openssl_decrypt($decryptData, AES_256_CBC, sha1(PHRASE), 1, MAINPASSWORD);
  }

  $databaseConnection = new PDO("mysql:host=localhost;dbname=DBNAME", "DBUSER", "DBPASS");
  $databaseConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  if(ACTION == "addAccount")
  {

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

    $addSite = $databaseConnection->prepare("INSERT INTO `pwd_sites` (name, url) VALUES (:name, :url)");
    $addSite->bindParam(':name', htmlspecialchars($_POST["name"]));
    $addSite->bindParam(':url', htmlspecialchars($_POST["url"]));
    $addSite->execute();

    $returnJson["Info"] = 'addSite -> succes';

  }
  else if(ACTION == "getSites")
  {

    $getSites = $databaseConnection->prepare("SELECT * from `pwd_sites`");
    $getSites->execute();
    $allSites = $getSites->fetchAll();

    foreach($allSites as $siteInfo) {

      $getSiteAmount = $databaseConnection->prepare("SELECT * from `pwd_accs` WHERE `site` = :url");
      $getSiteAmount->bindParam(':url', $siteInfo["url"]);
      $getSiteAmount->execute();
      $siteAmount = $getSiteAmount->rowCount();

      $htmlCode .= '<li class="im_dialog_wrap"><a class="im_dialog" onclick="getAccounts(\''. htmlspecialchars($siteInfo["url"]) .'\')">
      <div class="im_dialog_meta pull-right text-right"><div class="im_dialog_date">'. htmlspecialchars($siteInfo["url"]) .'</div></div>
      <div class="im_dialog_photo pull-left" style="border-radius:0%;"><img class="im_dialog_photo" style="border-radius:0%;" src="http://'. htmlspecialchars($siteInfo["url"]) .'/favicon.ico"></div> <div class="im_dialog_message_wrap">
      <div class="im_dialog_peer"><span>'. htmlspecialchars($siteInfo["name"]) .'</span></div><div class="im_dialog_message_notyping"><div class="im_dialog_message"><span><span><span class="im_dialog_chat_from_wrap"><span class="im_dialog_chat_from">Accounts</span><span>:</span></span></span></span><span>
      <span class="im_short_message_text">'. htmlspecialchars($siteAmount) .'</span></span></div></div></div></a></li>';

      $returnJson["Info"] = 'getSites -> succes';
      $returnJson["Html"] = $htmlCode;

    }

  }
  else if(ACTION == "getAccounts")
  {

    $getAccounts = $databaseConnection->prepare("SELECT * from `pwd_accs` WHERE `site` = :url");
    $getAccounts->bindParam(':url', $_POST["url"]);
    $getAccounts->execute();
    $allAccounts = $getAccounts->fetchall();

    foreach($allAccounts as $accountInfo)
    {

      for ($i = 1; $i <= strlen(decryptAES($accountInfo["password"])); $i++) $hiddenPassword .= "*";

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


  echo json_encode($returnJson, JSON_PRETTY_PRINT);



?>
