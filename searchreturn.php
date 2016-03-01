<?php
    require_once("classes.php");
    session_start();

    //capture the query
    $search_query = $_POST['topic'];
    $_SESSION['query_value'] = $search_query;
    
    //include files
    include 'nytimesreturn.php';
    include 'nprreturn.php';
    
    //store the json response for npr into an array of npr articles- function in classes.php, response from nytimesreturn.php
    $npr_articles = array();
    $npr_articles = nprArticleObject($npr_response, $npr_articles);
    
    //store the json response for npr into an array of nytimes articles- function in classes.php, response from nytimesreturn.php
    $nytimes_articles = array();
    $nytimes_articles = nytimesArticleObject($nytimes_response, $nytimes_articles);
    
    //Put all the articles into one array
    $merge_articles = array();
    //First push nytimes articles
    foreach($nytimes_articles as $ny){
     array_push($merge_articles, $ny);
    }
    //Then push npr articles
    foreach($npr_articles as $np){
     array_push($merge_articles, $np);
    }
    
    //Sort the articles based on publication date
    $sorted_articles = array();
    $sorted_articles = insertionSort($merge_articles);
    
    //twitter username
    $twitter_username = "";
    if(isset($_SESSION['status']) && $_SESSION['status']=='verified'){
        $twitter_username = $_SESSION['request_vars']['screen_name'];
    }else if(isset($_POST['username'])){
        $twitter_username = $_POST['username'];
    }else{
        $twitter_username = "";
    }
    

?>

<html>
    <head>
        <link href='http://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Questrial' rel='stylesheet' type='text/css'>
        <link href= 'style/style.css' rel="stylesheet">
    </head>
    
    <body>
        <!--Header-->
        <div class="bar-search">
            <!--Logo-->
            <div id="logo"> Social <br> Commentary </div>
            <!--Bar-->
            <div id="line"></div>
            <!--Additional re-search form-->
            <div id="searchBar">
                <form action="searchreturn.php" name="revisedSearchForm" method="post">
                   <input id="new-search-input" type="text" name="topic" placeholder="New Topic">
                    <!--Hidden input for username-->
                    <input type="hidden" name="username" value="<?php echo $twitter_username ?>">
                    <!--input type="submit" onclick=-->
                    <button id="submitButton" name="send"> Send </button>
                </form>
            </div>
            <!--Twitter Logo-->
            <div id="twitterlogo">
                <?php
                if($twitter_username!=""){
                echo '<div class="twitter-username-container">'.$twitter_username.'<a href="#"><img id="logout-click" src="img/down-arrow.png" class="down-button"></a></div> <br/>';
                echo '<div class="log-out-container"><a href="index.php?reset=1">Logout</a></div>';
                }
                ?>
            </div>
        </div>
        
        <!--Content-->
        <div id="content">
        <!--SearchResults Header-->
        <div class="search-results">
            <h1 class='search-results-title'> Search results for: <?php echo $search_query ?> </h1>
            <br>
            <div id="post-tweet">
                Post your own commentary!
                <br>
                <a href="https://twitter.com/intent/tweet?hashtags=<?php echo $search_query ?>" target="_blank">
                    <!--Source: https://about.twitter.com/company/brand-assets-->
                    <img id="tweet-button" src="img/tweetbutton.png">
                </a>
            </div>
        </div>
        <!--Articles-->
        <div class="articleDiv">
            <?php
                foreach($sorted_articles as $style_article){ ?>
                    <a class="click" target="_blank" href="<?php $style_article->echoURL(); ?>" >
                    <div class='individual-articles'>
                        <img class="source" src="<?php $style_article->returnImgSrc(); ?>" >
                        <h1 class='articleTitle'> <?php $style_article->echoTitle(); ?> </h1>
                        <h1 class='sourceTitle'> <?php $style_article->echoSource(); ?> </h1>
                        <h1 class='sourceTitle'> <?php $style_article->echoRealDate(); ?> </h1>
                        <p class='snippet'> <?php $style_article->echoParagraph(); ?> </p>
                    </div> </a>
            <?php } ?>
        </div>
        <!--Space-->
        <div class="div-spacer"> </div>
        <!--Twitter responses-->
        <div class="twitterDiv">
            <?php
            #$return_id_array = accumulateTweetsId($twitter_response);
            #traverseEmbeddedTweets($return_id_array);
            include 'twitterreturn.php';
            ?>
        </div>
        </div>
    
    <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
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