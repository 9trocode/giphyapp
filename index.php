<?php
/**
*@package CodeClass Giphy API app challenge
*@author sayo_paul : Ademola Abisayo Paul
*/
    //ensures we are dealing with the first word only
    function prepareQuery($query){
      $prepared= str_word_count($query,1);
      $prep=$prepared[0];
      return $prep;
      }

      //adds the search query to the api link
    function prepareLink($query){
      $link="http://api.giphy.com/v1/gifs/search?q=".$query."&api_key=dc6zaTOxFJmzC&limit=10&offset=0";
      return $link;
    }

    //gets the json from the api and converts it to a PHP object
    //also handles all exceptions in case anything goes wrong
    function displayOutput($link){
      $json=file_get_contents($link);
        if($json===FALSE){
            $output="<section class='col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3'><p class='alert alert-danger'> You seem to have a slow connection. Something went wrong... </p></section>";
            return $output;
        }else{
            $obj=json_decode($json);
            $array=$obj->data; //takes the returned data array and asigns it to a variable
              if (!empty($array)){ //ensures the returned object contains search results
                  $output="<section class='col-xs-12 col-sm-12 col-md-12 col-lg-12 col-sm-offset-1 col-md-offset-1'>";
                      for($i=0;$i<=9;$i++){  //loops through all the search results and displays them
                          $url=$obj->data[$i]->images->fixed_height->url;
                          $output.="<img class='gif img img-responsive img-thumbnail' src='$url' height='130px'>";
                      }
                      $output.="</section>";
                      return $output;
                }elseif(empty($array) || json_last_error() !== JSON_ERROR_NONE){ //displays error incase there are no search results or there is an error
                      if(empty($array)){
                          $output="<section class='col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3'><p class='alert alert-warning'><em> Your search returned no results :'( </em></p></section>";
                          return $output;
                      }else{
                          $output="<section class='col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3'><p class='alert alert-danger'> There was an error in the JSON object... </p></section>";
                          return $output;
                    }
                }
          }

    }
    //end of functions. If statement below checks to ensure that the submit button was clicked and passes the query as a parameter to the functions
    if(isset($_POST['submitted'])){
        $query=strip_tags($_POST['query']);
        if(!empty($query)){
            $query= prepareQuery($query);
            $link=prepareLink($query);
            $output=displayOutput($link);
            $outputHTML=$output;
        }elseif(empty($query)){
            $message="<section class='col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3'><p class='alert alert-danger'> You didn't type in a search query </p></section>";
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title> GIPHY SEARCH APP </title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel='stylesheet' type='text/css' href='css/main.css'>
        <link rel='stylesheet' type='text/css' href='css/bootstrap.min.css'>
    </head>
    <body>
      <section id='container' class='container-fluid'>
          <section class='row'>
                <section class='col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3'>
                    <h2 class='heading'> THE GIPHY SEARCH APP <small> &nbsp;by sayo_paul </small> </h2>
                    <h5 class='heading'>Giphy is an animated GIF search engine. Search for GIFs right here </h5>
                      <form  id='form' role='form' method='post' action='index.php' >
                        <section class='form-group'>
                          <input class='form-control' name='query' type='search' placeholder=' ... search ' required>

                          <input type='submit' class='btn' id='submit-button' name='submitted' value='Search Giphy' >
                        </section>

                    </form>
                </section>
                  <?php
                  // Checks to see if $outputHTML has a value. If it doesn't, we put a default value
                    if (empty($outputHTML)){
                        $outputHTML="<section class='col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3'> <p class='white-text'>Type in a search term </p> </section>";
                    }
                    echo $outputHTML;
                  ?>
          </section>
        </section>
        <!-- JS files used -->
        <script type='text/javascript' src='js/jquery-2.1.4.min.js'></script>
        <script type='text/javascript' src='js/bootstrap.min.js'></script>
    </body>
</html>
