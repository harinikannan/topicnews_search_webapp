<?php

    session_start();
    
    /*An instantiation of articleObject is a article with the following attributes:
     *a url, leading paragraph, headline, any multimedia, publication date, and the newspaper source.
     *Each object represents a distiguishable article*/
    class articleObject {
      
      private $web_url = "";
      private $lead_paragraph = "";
      private $headline = "";
      private $multimedia = "";
      private $date;
      private $real_date;
      private $source = "";
      
      /*Set the object's publication date in terms of days for sorting purposes*/
      public function setDate($d){
        $this->date = $d;
      }
      
      /*Set the object's publication date in terms of Month Day, Year*/
      public function setRealDate($dy){
        $this->real_date = $dy;
      }
      
      /*Set the object's newspaper source, $s == "nytimes" || $s=="npr" for now*/
      public function setSource($s){
        $this->source = $s;
      }
      
      /*Set object's URL, this will be the direct url for the article itself*/
      public function setURL($url){
         $this->web_url = $url;
      }
      
      /*Set object's leading paragraph, this is a snippet of information about the article*/
      public function setParagraph($para){
         $this->lead_paragraph = $para;
      }
      
      /*Set object's headline, this is the title of the article*/
      public function setHeadline($head){
         $this->headline = $head;
      }
      
      /*Set object's multimedia, this is any image associated with the article*/
      public function setMultimedia($media){
         $this->multimedia = $media;
      }
      
      /*Echo the object's title*/
      public function echoTitle(){
        echo $this->headline;
      }
      
      /*Echo the object's Source*/
      public function echoSource(){
        echo $this->source;
      }
      
      /*Echo the object's leading paragraph*/
      public function echoParagraph(){
        echo $this->lead_paragraph;
      }
      
      /*Echo the object's web url*/
      public function echoURL(){
        echo $this->web_url;
      }
      
      /*Echo the object's date*/
      public function echoRealDate(){
        echo $this->real_date;
      }
      
      /*Return the object's date*/
      public function getDate(){
        return $this->date;
      }
      
      /*Return the object's newspaper source*/
      public function getSource(){
        return $this->source;
      }
      
      /*Return the object's leading paragraph*/
      public function getParagraph(){
        return $this->lead_paragraph;
      }
      
      /*Echo correct img link*/
      public function returnImgSrc(){
        if ($this->source == "nytimes"){
            echo "img/nyt-t-logo.png";
        }else{
            echo "img/NPR-logo-square.png";
        }
      }
      
   }
   
   /*Return NPR date in terms of Month Day, Year*/
   function gregorianNPR($date){
        $npr_year = substr($date, 12, 4);
        $npr_month = "".$date[8].$date[9].$date[10];
        $npr_day = substr($date, 5,2);
        return $npr_month." ".$npr_day.", ".$npr_year;
   }
   
   /*Return NY times date in terms of Mont Day, Year*/
   function gregorianNY($date){
        $ny_year = substr($date,0,4);
        $ny_month = numToWord(substr($date, 5, 2));
        $ny_day = substr($date, 8,2);
        return $ny_month." ".$ny_day.", ".$ny_year;
   }
   
   /*Return the publication date of an npr article in terms of days*/
   function nprDate($date){
        $npr_year = (int)substr($date, 12,4) * 365;
        $npr_month = ((findMonth($date[8].$date[9].$date[10])) * 365) / 12;
        $npr_day = (int)substr($date,5,2);
        $npr_date = $npr_year + $npr_month + $npr_day;
        return $npr_date;
    }
    
    /*Return the publicationd ate for an nytimes article in terms of days*/
   function nytimesDate($date){
      $nytimes_year_days = (int)substr($date, 0,4) * 365;
      $nytimes_month_days = (findMonth(substr($date, 5, 2)) * 365) / 12;
      $nytimes_days = (int)substr($date, 8,2);
      $nytimes_date = $nytimes_year_days + $nytimes_month_days + $nytimes_days;
      return $nytimes_date;
   }
   
   /*Return array containing articleObjects, each an article from the npr responses*/ 
   function nprArticleObject(array $npr_json_response, array $article_accumulation){
        $npr_iteration = 0;
        foreach ($npr_json_response['list']['story'] as $npr_content){
            //Create article object
            $npr_article = new articleObject();
            //push article into list
            array_push($article_accumulation, $npr_article);
            //set source attribute
            $npr_article->setSource("npr");
            //set date attribute
            
            $npr_article->setDate(nprDate($npr_json_response['list']['story'][$npr_iteration]['storyDate']['$text']));
            #echo "NPR STORY DATE: ".$npr_json_response['list']['story'][(string)$iteration]['storyDate']['$text']."<br/>";
            $npr_article->setRealDate(gregorianNPR($npr_json_response['list']['story'][$npr_iteration]['storyDate']['$text']));
            //set URL attribute
            $npr_article->setURL($npr_content['link'][0]['$text']);
            //set headline attribute
            $npr_article->setHeadline($npr_content['title']['$text']);
            //set leading paragraph
            $npr_article->setParagraph($npr_content['teaser']['$text']);
            
            $npr_iteration++;
        }
        return $article_accumulation;
    }
    
    /*Return array containing articleObjects, each an article from the nytimes responses*/
    function nytimesArticleObject(array $ny_json_response, array $article_accumulation){
        $ny_iteration = 0;
      foreach($ny_json_response['response']['docs'] as $ny_content){
         $ny_article = new articleObject();
         array_push ($article_accumulation, $ny_article);
         $ny_article->setSource("nytimes");
         $ny_article->setDate(nytimesDate($ny_json_response['response']['docs'][$ny_iteration]['pub_date']));
         #echo "NYTIMES STORY DATE: ".$ny_json_response['response']['docs'][$iteration]['pub_date']."<br/>";
         $ny_article->setRealDate(gregorianNY($ny_json_response['response']['docs'][$ny_iteration]['pub_date']));
         $ny_article->setURL($ny_content['web_url']);
         $ny_article->setHeadline($ny_content['headline']['main']);
         $ny_article->setParagraph($ny_content['snippet']);
        
        $ny_iteration++;
      }
      return $article_accumulation;
   }
   
   /*Return an integer representing the month provided by it's corresponding string representation*/
   function findMonth($month){
        if($month == "Jan" || $month =="01"){
            return 1;
        }
        
        elseif($month=="Feb" || $month=="02"){
            return 2;
        }
        
        elseif($month=="Mar" || $month=="03"){
            return 3;
        }
        
        elseif($month=="Apr" || $month=="04"){
            return 4;
        }
        
        elseif($month=="May" || $month=="05"){
            return 5;
        }
        
        elseif($month=="Jun" || $month=="06"){
            return 6;
        }
        
        elseif($month=="Jul" || $month=="07"){
            return 7;
        }
        
        elseif($month=="Aug" || $month=="08"){
            return 8;
        }
        
        elseif($month=="Sept" || $month=="09"){
            return 9;
        }
        
        elseif($month=="Oct" || $month=="10"){
            return 10;
        }
        
        elseif($month=="Nov" || $month=="11"){
            return 11;
        }
        
        elseif($month=="Dec" || $month=="12"){
            return 12;
        }
        
        else{
            return 0;
        }
   }
   
   function numToWord($month){
    if($month =="01"){
            return "Jan";
        }
        
        elseif($month=="02"){
            return "Feb";
        }
        
        elseif($month=="03"){
            return "Mar";
        }
        
        elseif($month=="04"){
            return "Apr";
        }
        
        elseif($month=="05"){
            return "May";
        }
        
        elseif($month=="06"){
            return "Jun";
        }
        
        elseif($month=="07"){
            return "Jul";
        }
        
        elseif($month=="08"){
            return "Aug";
        }
        
        elseif($month=="09"){
            return "Sept";
        }
        
        elseif($month=="10"){
            return "Oct";
        }
        
        elseif($month=="11"){
            return "Nov";
        }
        
        elseif($month=="12"){
            return "Dec";
        }
        
        else{
            return "";
        }
   }
   
   /*Return an array in which two elements have been swapped*/
   function swap(array $arts, $j, $k){
    $tem = $arts[$j];
    $arts[$j] = $arts[$k];
    $arts[$k] = $tem;
    return $arts;
   }
   
   /*Return an array after an element as been pushed down into its properly sorted position*/
   function pushDown(array $a, $i){
     $temp = $i;
     
     while($temp > 0){
        $temp_article_date = $a[$temp]->getDate();
        //add check later if date is not set
        if($temp_article_date > $a[$temp-1]->getDate()){
            $a = swap($a, $temp, $temp-1);
        }
        
        $temp = $temp-1;
     }
     
     return $a;
   }
   
   /*Return an array that has been sorted as per the rules of insertionSort*/
   function insertionSort(array $b){
    //precondition: b[0..count] is unknown
    //inv: b[0..x-1] is sorted, b[x..count] is unsorted
    $x = 0;
    while($x<count($b)){
        $b = pushDown($b, $x);
        $x = $x+1;
    }
    return $b;
   }
   
   /*Iterate through the articles printing information about the title, source, and paragraph*/
   function iterateArticles(array $art){
      foreach($art as $content){
         echo $content->echoTitle();
         echo "<br/>";
         echo "Source: ".$content->getSource();
         echo "<br/>";
         echo "Teaser: ".$content->getParagraph();
         echo "<br/>";
      }
   }
   

?>