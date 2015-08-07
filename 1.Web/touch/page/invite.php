<?php
require_once('../config.php'); 

$site_url = $site['url'];
$script = array('invite');
$page['title'] = "邀请码";

$invite_code = "";
if($_GET)
{
	$invite_code = $_REQUEST['invite_code'];

	if(!isset($invite_code))
	{
		header("Location: $site_url./page/404.php");
	}
}

include '../layout/header.php'; 
?>
    <div class="invite-page">

             <div class="invite-content">
             <p>
                <input type="text" id="text-invite" value="<?=$invite_code ?>" placeholder="输入邀请码">
              
              </p>

              <p>  <button id="btn-invite">确定</button>
              </p>
            </div>
        
    </div>
<?php include '../layout/footer.php';?>