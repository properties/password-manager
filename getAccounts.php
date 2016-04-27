<?php


      /*> Get POST information <*/

      $url = htmlspecialchars($_POST["url"]);

      /*>  Connect to database <*/

      $conn = new PDO("mysql:host=localhost;dbname=DBNAME", "DBUSER", "DBPASSWORD");

      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



      /*>  get Accounts <*/

      $getAccounts = $conn->prepare("SELECT * from `pwd_accs` WHERE `site` = :url");
      $getAccounts->bindParam(':url', $url);
      $getAccounts->execute();

      $allAccounts = $getAccounts->fetchall();

      $htmlCode = '';
      foreach($allAccounts as $accountInfo)

      {


        $hiddenPassword = "*";
        $secretPassword = strlen($accountInfo["password"]);
        //echo $secretPassword;
        for ($i = 1; $i <= $secretPassword; $i++) {
          $hiddenPassword .= "*";
        }

        $htmlCode .= '
        <div class="md_modal_iconed_section_wrap  md_modal_iconed_section_link" style="border-bottom: 1px solid #ebebeb;">
        <i class="md_modal_section_icon md_modal_section_icon_more"></i>
        <div class="md_modal_section_select_wrap">
          <div class="dropdown md_modal_section_select"><button data-clipboard-text="'.$accountInfo["email"].'" class="btn btn-link dropdown-toggle copy">Copy</button></div>
          <div class="md_modal_section_param_name"><span style="color: #3a6d99;">Email:</span> '.$accountInfo["email"].'</div>
        </div>
        <div class="md_modal_section_select_wrap">
          <div class="dropdown md_modal_section_select"><button data-clipboard-text="'.$accountInfo["username"].'" class="btn btn-link dropdown-toggle copy">Copy</button></div>
          <div class="md_modal_section_param_name"><span style="color: #3a6d99;">Username:</span> '.$accountInfo["username"].'</div>
        </div>
        <div class="md_modal_section_select_wrap">
          <div class="dropdown md_modal_section_select"><button data-clipboard-text="'.$accountInfo["password"].'" class="btn btn-link dropdown-toggle copy">Copy</button></div>
          <div class="md_modal_section_param_name"><span style="color: #3a6d99;">Password:</span> '.$hiddenPassword.'</div>
        </div>
        <div class="md_modal_section_select_wrap">
          <div class="dropdown md_modal_section_select"><button data-clipboard-text="'.$accountInfo["extra"].'" class="btn btn-link dropdown-toggle copy">Copy</button></div>
          <div class="md_modal_section_param_name"><span style="color: #3a6d99;">Extra:</span> '.$accountInfo["extra"].'</div>
        </div>
        </div>';

      }

      $returnJson["Html"] = $htmlCode;

      echo json_encode($returnJson, JSON_PRETTY_PRINT);
