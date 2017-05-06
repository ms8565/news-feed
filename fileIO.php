<?php
$resultData = "ERROR";
$jsonFile = "http://www.se.rit.edu/~ms8565/news-feed/userData.json";

function getJSON(){
  $file = file_get_contents('./userData.json');
  return json_decode($file, true);

}

function getFavorites($username){
  $json = getJSON();
  
  foreach ($json as $user) {
    if ($user['username'] == $username) {
      return $user['favorites'];
    }
  }
  
  return "Could not find user";
}

function addFavorite($username, $jsonData){
  $json = getJSON();
  
  foreach ($json as $user) {
    if ($user['username'] == $username) {
      $favoriteData = json_decode($jsonData, true);
      
      //Check if favorite is already in list
      //Check favorites for entry
      foreach ($user['favorites'] as $favorite){
        if ($favorite['link'] == $favoriteData['link']) {
          return $favorite;
        }
      }
      
      //otherwise add it to favorites
      array_push($user['favorites'], $favoriteData);
      
      $json[$username] = $user;
      
      file_put_contents('./userData.json', json_encode($json));
      
      return $favoriteData;
    }
  }
  
  return "Could not find user";
}


function removeFavorite($username, $favoriteLink){
  $json = getJSON();
  
  foreach ($json as $user) {
    if ($user['username'] == $username) {
      
     //Check favorites for entry
      foreach ($user['favorites'] as $favorite){
        if ($favorite['link'] == $favoriteLink) {
          unset($user['favorites'][$favorite]);
          return $user['favorites'];
        }
      }
      
      return "Error finding favorite";
    }
  }
  
  return "Could not find user";
}

function createUser($username, $password){
  $json = getJSON();
  
  foreach ($json as $user) {
    if ($user['username'] == $username) {
      return "User already exists";
    }
  }
  
  //If the user doesn't exist
  $userData = array("username" => $username, "password" => $password, "favorites" => array());
  array_push($json, $userData);
  file_put_contents('./userData.json', json_encode($json));
    
  return $userData;
}

function login($username, $password){
  $json = getJSON();
  
  foreach ($json as $user) {
    if ($user['username'] == $username) {
      $storedPassword = $user['password'];
    
      if($password == $storedPassword){
        return $json['username'];
      }
      
      return "Invalid password"; 
    }
  }
  
  return "User doesn't exist";

}


  
//check what function is being called
if (isset($_GET["function"]) || isset($_POST["function"]) )
{
  switch($_GET["function"]){
    //If the client is calling getFavorites
    case "getFavorites":
      if(isset($_GET["username"])){
        
        $resultData = getFavorites($_GET["username"]);
      }
      else{
        $resultData = "Incorrect parameters";
      }
      break;
    //If the client is calling addFavorite
    case "addFavorite":
      if(isset($_POST["username"]) && isset($_POST["jsonData"])){
        $resultData = addFavorite($_POST["username"], $_POST["jsonData"]);
      }
      else{
        $resultData = "Incorrect parameters";
      }
      break;
    //If the client is calling createUser
    case "createUser":
      if(isset($_POST["username"]) && isset($_POST["password"])){
        $resultData = createUser($_POST["username"], $_POST["password"]);
      }
      else{
        $resultData = "Incorrect parameters";
      }
      break;
    case "removeFavorite":
      if(isset($_POST["username"]) && isset($_POST["link"])){
        $resultData = removeFavorite($_POST["username"], $_POST["link"]);
      }
      else{
        $resultData = "Incorrect parameters";
      }
      break;
    case "login":
      if(isset($_POST["username"]) && isset($_POST["password"])){
        $resultData = login($_POST["username"], $_POST["password"]);
      }
      else{
        $resultData = "Incorrect parameters";
      }
      break;
    default:
      $resultData = "Does not match any functions";
  }
  
  
}

//return JSON
header('Content-type:application/json;charset=utf-8');
echo json_encode($resultData);
?>
