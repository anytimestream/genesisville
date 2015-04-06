<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>GenesisVille Ltd | Change Password</title>
        <link href="css/login.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div id="content" style="width: 380px">
            <div class="name"></div>
            <form id="login" action="" method="post">
                <br/><br/><br/>
                <div class="row">
                    <h3>Change Password</h3>
                </div>
                <div class="row error">
                    <center><?php if (isset($_GET['msg'])) echo $_GET['msg']; ?></center>
                </div>
                <div class="row">
                    <label style="width: 130px" class="label w200">Current Password:</label>
                    <input inputtype="textbox" id="password" name="password" type="password"/>
                </div>
                <div class="row">
                    <label style="width: 130px" class="label w200">New Password:</label>
                    <input inputtype="textbox" id="password2" name="password2" type="password"/>
                </div>
                <div class="row">
                    <label style="width: 130px" class="label w200">Confirm Password:</label>
                    <input inputtype="textbox" id="password3" name="password3" type="password"/>
                </div>
                <div><center><button class="btn">Update</button></center></div>
                <div><center><a style="color: #eee; display: block; padding: 5px 0; font-size: 14px" href="<?php echo CONTEXT_PATH.'/'.  strtolower(UserService::GetUser()->getValue('type'))?>">Click here to return</a></center></div>
            </form>
        </div>
    </body>
</html>
