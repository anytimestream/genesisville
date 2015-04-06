<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>GenesisVille Ltd | Login</title>
        <link href="css/login.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div id="content">
            <div class="name"></div>
            <form id="login" action="" method="post">
                <br/><br/><br/>
                <div class="row">
                    <h3>Control Panel</h3>
                </div>
                <div class="row error">
                    <center><?php if (isset($_POST['username'])) echo 'Invalid UserName or Password'; ?></center>
                </div>
                <div class="row">
                    <label>Username:</label>
                    <input type="text" name="username"/>
                </div>
                <div class="row">
                    <label>Password:</label>
                    <input type="password" name="password"/>
                </div>
                <div><center><button class="btn">Login</button></center></div>
            </form>
        </div>
    </body>
</html>
