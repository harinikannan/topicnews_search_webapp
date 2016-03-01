<?php
//first part of Saran Chamling tutorial for twitter oauth:
session_start();

//just simple session reset on logout click
if($_GET["reset"]==1)
{
    session_destroy();
    header('Location: ./index.php');
}

// Include config file and twitter PHP Library by Abraham Williams (abraham@abrah.am)
include_once("sanwebe_oauth_tutorial/config.php");
include_once("sanwebe_oauth_tutorial/abraham_twitter_oauth/twitteroauth.php");
?>

<html>
    <!--Css will be modified from Saran Chamling's sample css styling for this web application's needs-->
    <head>
        <link href='http://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Questrial' rel='stylesheet' type='text/css'>
        <link href= 'style/style.css' rel="stylesheet">
    </head>
    <body>
    
        <!--Header-->
        <div class="bar-index">
            <!--Logo-->
            <div id="logo"> Social <br> Commentary </div>
            <div id="line"></div>
            <!--Twitter Logo-->
            <div id="twitterlogo">
                <?php
                    if(isset($_SESSION['status']) && $_SESSION['status']=='verified') 
                    {
                        //Success, redirected back from process.php with varified status.
                        //retrive variables
                        $screenname 		= $_SESSION['request_vars']['screen_name'];
                        $twitterid 			= $_SESSION['request_vars']['user_id'];
                        $oauth_token 		= $_SESSION['request_vars']['oauth_token'];
                        $oauth_token_secret         = $_SESSION['request_vars']['oauth_token_secret'];
            
                        //Show welcome message
                        //echo '<div class="welcome_txt">Welcome <strong>'.$screenname.'</strong> (Twitter ID : '.$twitterid.'). <a href="index.php?reset=1">Logout</a>!</div>';
                       echo '<div class="twitter-username-container">'.$screenname.'<a href="#" id ="logout-click"><img src="img/down-arrow.png" class="down-button"></a></div> <br/>';
                        //source: http://cdn.discourse.org/sitepoint/uploads/default/18154/2a3f47a3ada61e6c.png
                       echo '<div class="log-out-container"><a href="index.php?reset=1">Logout</a></div>';
                        $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $oauth_token, $oauth_token_secret);
                    }
                    else{
                    //login button
                    echo '<a href="sanwebe_oauth_tutorial/process.php"><img src="img/sign-in-with-twitter-gray.png" width="151" height="24" border="0" /></a>';
                    }
                ?>
            </div>
            
        </div>
        <!--Search Area-->
        <div id="search-container">
            <div id="sc-title"> SocialCommentary </div>
            <div class="search-area">
            <form action="searchreturn.php" name="searchForm" method="post">
                <input class="search-input" type="text" name="topic" placeholder="Search any topic">
                <!--input type="submit" onclick=-->
                <button id="submitButton" name="send" > Send </button>
            </form>
            </div>
        </div>
    
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <script>
        $(document).ready(function(){
            $('#logout-click').click(function(){
                $(".log-out-container").toggle();
            });
        });
    </script>
    
    </body>
</html>
