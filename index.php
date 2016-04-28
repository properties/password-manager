<?php


  $conn = new PDO("mysql:host=localhost;dbname=DBNAME", "DBUSER", "DBPASSWORD");

  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $getSites = $conn->prepare("SELECT * FROM `pwd_sites`");
  $getSites->execute();

  $allSites = $getSites->fetchAll();

 ?>

<!doctype html>
<html lang="en" id="ng-app" style="display: block;">
<head>

  <meta charset="utf-8">
  <meta name="theme-color" content="#497495">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <title>Password Manager</title>
  <link rel="stylesheet" href="/css/app.css">
  <link rel="stylesheet" href="/css/desktop.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

  <style>
    ::-webkit-scrollbar {
      width: 9px;
      -webkit-transition: none;
      -moz-transition: none;
      -o-transition: none;
      transition: none;
      background: rgba(216, 223, 225, 0.45);
      position: absolute;
      top: 10px;
      right: 8px;
   }

   ::-webkit-scrollbar-thumb {
      background: rgba(137, 160, 179, 0.5);
   }
  </style>
</head>

<body class="non_osx non_msie is_1x">

  <br><br>
  <div class="page_wrap" ng-view="">
    <div my-head="">
      <div class="tg_page_head tg_head_wrap noselect clearfix">
        <div class="tg_head_split">
          <div class="tg_head_main_wrap"></div>
        </div>
      </div>
    </div>

    <div class="im_page_wrap clearfix im_page_peer_not_selected">

      <div class="im_page_split clearfix">

        <div class="im_dialogs_col_wrap noselect">
          <div class="im_dialogs_panel">
            <div class="im_dialogs_search">
              <input class="form-control im_dialogs_search_field no_outline ng-pristine ng-untouched ng-valid" type="search" placeholder="Search" ng-model="search.query" autocomplete="off">
              <a class="im_dialogs_search_clear tg_search_clear ng-hide">
                <i class="icon icon-search-clear"></i>
              </a>
            </div>
          </div>

          <div my-dialogs-list="" class="im_dialogs_col" style="height: 482px;">
            <div class="im_dialogs_wrap nano has-scrollbar">
              <div class="im_dialogs_scrollable_wrap nano-content" tabindex="-1" style="right: -10px;">

                <!-- Websites -->
                <ul class="nav nav-pills nav-stacked">

                  <?php
                  foreach($allSites as $siteInfo) {

                    $getSiteAmount = $conn->prepare("SELECT * from `pwd_accs` WHERE `site` = :url");
                    $getSiteAmount->bindParam(':url', $siteInfo["url"]);
                    $getSiteAmount->execute();

                    $siteAmount = $getSiteAmount->rowCount();
                  ?>
                    <li class="im_dialog_wrap">
                      <a class="im_dialog" onclick="getAccounts('<?php echo $siteInfo["url"] ?>')">

                      <div class="im_dialog_meta pull-right text-right">
                        <div class="im_dialog_date"><?php echo $siteInfo["url"] ?></div>
                      </div>

                      <div class="im_dialog_photo pull-left" style="border-radius:0%;">
                        <img class="im_dialog_photo" style="border-radius:0%;" src="<?php echo $siteInfo["img"] ?>">
                      </div>

                      <div class="im_dialog_message_wrap">
                        <div class="im_dialog_peer"><span><?php echo $siteInfo["name"] ?></span></div>

                        <div class="im_dialog_message_notyping">
                          <div class="im_dialog_message">
                            <span><span>
                                <span class="im_dialog_chat_from_wrap">
                                  <span class="im_dialog_chat_from">Accounts</span>
                                  <span>:</span>
                                </span>
                            </span></span>

                            <span>
                              <span class="im_short_message_text"><?php echo $siteAmount ?></span>
                            </span>
                          </div>
                        </div>
                      </div>
                    </a>
                  </li>
                <?php } ?>
              </ul>


          </div>
        </div>
      </div>
    </div>

    <div class="im_history_col_wrap noselect" style="height: 550px;overflow: auto;">
      <div class="im_history_not_selected_wrap">
        <!-- Accounts -->
        <script>
          function getAccounts(url)
          {
            $.post( "getAccounts.php" , { url:url } , function( accountInfo ){
              $('#allAccounts').html(accountInfo.Html);
            }, "json");

            return false;
          }
        </script>
        <div id="allAccounts" class="im_history_not_selected vertical-aligned" style="text-align: inherit;padding: 10px 10px;">
          <br><br><br><br><center><p>Choose an website</p></center>
        </div>

      </div>
    </div>
    </div>
    </div>
    </div>

    <script src="dist/clipboard.min.js"></script>
    <script>
    new Clipboard('.copy');

    function setColor(btn, color) {
      var property = document.getElementById(btn);
      property.style.backgroundColor = "#FFFFFF"
      }

</script>

</body>
</html>
