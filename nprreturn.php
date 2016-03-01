<?php

    session_start();
    
    /*Return url friendly search query for NPR*/
   function returnNPRFriendly($query){
        $query_to_array = str_split($query);
        $new_query="";
        foreach($query_to_array as $letter){
            if ($letter == " "){
                $new_query.="%20"; //does not affect for loop because you're adding %20 to new_query, not query
            }else{
                $new_query.=$letter;
            }
        }
        return $new_query;
   }

    $nprAPI = "MDE5NzUzNTAyMDE0MzU4NTM1MDhkOWE4Yw001";
    $npr_query = $_SESSION['query_value'];
    $html_npr_query = returnNPRFriendly($npr_query);
    $output = "JSON";
    $npr_fields = "title,teaser,storyDate,text,image,audio";
    $nprURL = 'http://api.npr.org/query?apiKey='.$nprAPI.'&searchTerm='.$html_npr_query.'&output='.$output.'&fields='.$npr_fields.'&numResults=15';
    $npr_curl = curl_init($nprURL);
    curl_setopt($npr_curl, CURLOPT_RETURNTRANSFER, true);
    $npr_curl_response = curl_exec($npr_curl);
    curl_close($npr_curl);
    $npr_response = json_decode($npr_curl_response,true);
    #print_r($npr_response);

?>