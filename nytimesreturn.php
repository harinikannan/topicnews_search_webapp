<?php

   session_start();
   
   /*Return url friendly search query for NYtimes*/
   function returnNYFriendly($q){
      //first turn the string into an array to iterate on
      $query_to_array = str_split($q);
      //create string you will keep concatenating
      $ny_query="";
        foreach ($query_to_array as $l){
            if($l == " "){
                $ny_query .="+";
            }else{
                $ny_query.=$l;
            }
        }
        return $ny_query;
   }
   
   $nytimesAPI = 'be0e45721be5b1838238ebf35b5f80f3:1:72305624';
   $nytimes_query = "";
   $nytimes_query = $_SESSION['query_value'];
   $html_nytimes_query = returnNYFriendly($nytimes_query);
   $nytimesURL = 'http://api.nytimes.com/svc/search/v2/articlesearch.json?q='.$html_nytimes_query.'&api-key='.$nytimesAPI;
   $nytimes_curl = curl_init($nytimesURL);
   curl_setopt($nytimes_curl, CURLOPT_RETURNTRANSFER, true);
   $nytimes_curl_response = curl_exec($nytimes_curl);
   curl_close($nytimes_curl);
   $nytimes_response = json_decode($nytimes_curl_response, true);
   
   //print_r($nytimes_response);

?>