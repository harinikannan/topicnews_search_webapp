<?php
    
    session_start();

   require_once('twitter-api-php-master/TwitterAPIExchange.php');
   
   $twitter_credentials = array(
    'oauth_access_token' => "2938523194-Wub69QkoPP4sdCSXmDrgBhkzWYmlzYKCVzHVDVd",
    'oauth_access_token_secret' => "ThWeQ8mdi9ZRVysJhu66RYrXnY7wO1kYJnKb5wWrWM9qq",
    'consumer_key' => "UAW27O9qrsEujWGHYv2NycfrY",
    'consumer_secret' => "wcJLpy6MuiyAIgt9Vq9tBgxoaiBO6eVOlTVbsMTUu0Kn8ZlLOJ",
   );
   
   $twitter_url = "https://api.twitter.com/1.1/search/tweets.json";
   $requestMethod = "GET";
   $twitter_query = "?q=".$_SESSION['query_value'];
   $twitter = new TwitterAPIExchange($twitter_credentials);
   $twitter_response = json_decode($twitter->setGetfield($twitter_query)
                ->buildOauth($twitter_url, $requestMethod)
                ->performRequest(), $assoc = TRUE);
   
    if($twitter_response["errors"][0]["message"]!="") {echo "<h3>Sorry, there was a problem.</h3>
    <p>Twitter returned the following error message:</p><p><em>".$twitter_response[errors][0]["message"]."</em></p>";exit();}
   
   $twitter_embed_url = "https://api.twitter.com/1.1/statuses/oembed.json";
   
   /*Return array of all status IDs*/
   #function accumulateTweetsId($string){
    //Instantiate array
    $id_array = array();
    //Traverse through statuses
    foreach ($twitter_response['statuses'] as $status){
     //store temp id
     $temp_id = $status['id'];
     //push temp id into array
     array_push($id_array, $temp_id);
    }
   # return $id_array;
   #}
   
   #function traverseEmbeddedTweets(array $twitter_id_array){
    foreach ($id_array as $id){
     $embed_string = json_decode($twitter->setGetfield("?id=".$id."&hide_media=true&hide_thread=true")
                     ->buildOauth($twitter_embed_url,$requestMethod)
                     ->performRequest(),$assoc=TRUE);
     #echo $embed_string;
     echo $embed_string['html'];
    }
   #}
   
   #$ids = accumulateTweetsId($twitter_response);
   #traverseEmbeddedTweets($ids);
 session_destroy();
?>

<html>
    <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
</html>