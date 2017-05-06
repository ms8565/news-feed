<?php
$resultData = "ERROR";
$jsonFile = "userData.json";

function getJSON(){
  return json_decode(file_get_contents($jsonFile));
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
      $favoriteData = json_decode($jsonData);
      
      //Check if favorite is already in list
      //Check favorites for entry
      foreach ($user['favorites'] as $favorite){
        if ($favorite['link'] == $favoriteData['link']) {
          return $favorite;
        }
      }
      
      //otherwise add it to favorites
      array_push($user['favorites'], $favoriteData);
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
          //unset($user['favorites'][$favorite]);
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
if (isset($_GET["function"]))
{
  switch($_GET["function"]){
    //If the client is calling getFavorites
    case "getFavorites":
      if(isset($_GET["username"])){
        
        //$resultData = getFavorites($_GET["username"]);
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
    default:
      $resultData = "Does not match any functions";
  }
  
  
}
$resultData = "No function provided";

//return JSON
header('Content-type:application/json;charset=utf-8');
echo json_encode($resultData);
?>
