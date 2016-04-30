<?php

  session_start();

  if($_SESSION['login'] != 1)
  {
    header("Location: =login");
    exit();
  }

 ?>
<!DOCTYPE HTML>
<html lang="en" id="ng-app" style="display: block;">
<head>

  <meta name="theme-color" content="#497495">
  <title>Password Manager</title>
  <link rel="stylesheet" href="https://web.telegram.org/css/app.css">
  <link rel="stylesheet" href="https://web.telegram.org/css/desktop.css">
  <link rel="stylesheet" href="/css/main.css">
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

        <div class="im_dialogs_col_wrap noselect">
          <div class="im_dialogs_panel">
            <div class="im_dialogs_search">
              <input class="form-control im_dialogs_search_field no_outline ng-pristine ng-untouched ng-valid" type="search" placeholder="Search" ng-model="search.query" autocomplete="off">
              <a class="im_dialogs_search_clear tg_search_clear ng-hide">
                <i class="icon icon-search-clear"></i>
              </a>

              <div style="margin-top: 10px;">
                <button onclick="alertShowSite()" class="btn reply_markup_button" style="float: right;width:48%;">Add site</button>
                <button onclick="alertShowAccount()" class="btn reply_markup_button" style="float: right;width:48%;margin-right: 11px;">Add account</button>
              </div>

              <br style="clear:both;">
            </div>
          </div>

          <div my-dialogs-list="" class="im_dialogs_col" style="height: 482px;">
            <div class="im_dialogs_wrap nano has-scrollbar">
              <div class="im_dialogs_scrollable_wrap nano-content" tabindex="-1" style="right: -10px;">
                <ul id="allSites" class="nav nav-pills nav-stacked"></ul>
          </div>
        </div>
      </div>
    </div>

    <div class="im_history_col_wrap noselect" style="height: 550px;overflow: auto;">
      <div class="im_history_not_selected_wrap">
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

    function getSites()
    {
      $.post( "=system" , { ACTION:"getSites" } , function( siteInfo ){
        $('#allSites').html(siteInfo.Html);
      }, "json");

      return false;
    }

    function getAccounts(url)
    {
      $.post( "=system" , { ACTION:"getAccounts", url:url } , function( accountInfo ){
        $('#allAccounts').html(accountInfo.Html);
      }, "json");

      return false;
    }

    $(document).ready(function(){
      getSites();
    });

    $(document).ready(function(){
      $("#addSite_form").submit(function() {

        $.post("=system", {
          ACTION:"addSite",
          name:$("#addSite_name").val(),
          url:$("#addSite_url").val()
        }, function() {});

        $("#addSite").hide();
        getSites();

        return false;
      });

      $("#addAccount_form").submit(function() {

        $.post("=system", {
          ACTION:"addAccount",
          url:$("#addAccount_url").val(),
          email:$("#addAccount_email").val(),
          username:$("#addAccount_username").val(),
          password:$("#addAccount_password").val(),
          extra:$("#addAccount_extra").val()
        }, function() {});

        $("#addAccount").hide();
        getSites();

        return false;
      });

    });

    function alertHideAccount() {
      $("#addAccount").hide();
    }

    function alertShowAccount() {
      $("#addAccount").show();
    }

    function alertHideSite() {
      $("#addSite").hide();
    }

    function alertShowSite() {
      $("#addSite").show();
    }



  </script>

  <div id="addAccount" style="display:none">
    <div class="modal-backdrop fade in" style="z-index: 1051;"></div>
    <div tabindex="-1" role="dialog" class="modal fade md_simple_modal_window mobile_modal in" style="z-index: 1060; display: block;">
      <div onclick="alertHideAccount()" class="modal_close_wrap"><div class="modal_close"></div></div>
        <div class="modal-dialog"><div class="modal-content modal-content-animated" style="margin-top: 153.5px;"><div class="md_simple_modal_wrap">

          <div class="md_simple_modal_body">

            <form id="addAccount_form" action="#add" class="modal_simple_form ng-pristine ng-valid">

              <h4 my-i18n="profile_edit_modal_title">Add account</h4>

              <div class="md-input-group md-input-has-value md-input-animated">
                <label class="md-input-label">Site URL</label>
                <input id="addAccount_url" class="md-input" type="text">
              </div>

              <div class="md-input-group md-input-has-value md-input-animated">
                <label class="md-input-label">Email</label>
                <input id="addAccount_email" class="md-input" type="text">
              </div>

              <div class="md-input-group md-input-has-value md-input-animated">
                <label class="md-input-label">Username</label>
                <input id="addAccount_username" class="md-input" type="text">
              </div>

              <div class="md-input-group md-input-has-value md-input-animated">
                <label class="md-input-label">Password</label>
                <input id="addAccount_password" class="md-input" type="password">
              </div>

              <div class="md-input-group md-input-has-value md-input-animated">
                <label class="md-input-label">Extra</label>
                <input id="addAccount_extra" class="md-input" type="text">
              </div>

              <div class="md_simple_modal_footer">
                <button type="button" class="btn btn-md" onclick="alertHideAccount()">Cancel</button>
                <button type="submit" class="btn btn-md btn-md-primary">Save</button>
              </div>


            </form>

          </div>

        </div>
      </div></div>
    </div>
  </div>

  <div id="addSite" style="display:none">
    <div class="modal-backdrop fade in" style="z-index: 1051;"></div>
    <div tabindex="-1" role="dialog" class="modal fade md_simple_modal_window mobile_modal in" style="z-index: 1060; display: block;">
      <div onclick="alertHideSite()" class="modal_close_wrap"><div class="modal_close"></div></div>
        <div class="modal-dialog"><div class="modal-content modal-content-animated" style="margin-top: 153.5px;"><div class="md_simple_modal_wrap">

          <div class="md_simple_modal_body">

            <form id="addSite_form" action="#add" class="modal_simple_form ng-pristine ng-valid">

              <h4 my-i18n="profile_edit_modal_title">Add Site</h4>

              <div class="md-input-group md-input-has-value md-input-animated">
                <label class="md-input-label">Site URL</label>
                <input id="addSite_url" class="md-input" type="text">
              </div>

              <div class="md-input-group md-input-has-value md-input-animated">
                <label class="md-input-label">Name</label>
                <input id="addSite_name" class="md-input" type="text">
              </div>

              <div class="md_simple_modal_footer">
                <button type="button" class="btn btn-md" onclick="alertHideSite()">Cancel</button>
                <button type="submit" class="btn btn-md btn-md-primary">Save</button>
              </div>

            </form>

          </div>

        </div>
      </div></div>
    </div>
  </div>

</body>
</html>
