<?php
$resultData = "ERROR";
$jsonFile = "userData.json";

function getFavorites($username){
  
}
function addFavorite($username, $jsonData){
  
}
function createUser($username, $password){
  
}
function login($username, $password){
  //if json contains username:
  
  $storedPassword = ""; //get from json file
  
  if(password_verify($password, $storedPassword)){
    //return the user
  }
}
  
//check what function is being called
if (isset($_GET["function"]))
{
  switch($_GET["function"]){
    //If the client is calling getFavorites
    case "getFavorites":
      if(isset($_GET["username"])){
        return getFavorites($_GET["username"]);
      }
      else{
        return "Incorrect parameters";
      }
      break;
    //If the client is calling addFavorite
    case "addFavorite":
      if(isset($_POST["username"]) && isset($_POST["jsonData"])){
        return addFavorite($_POST["username"], $_POST["jsonData"]));
      }
      else{
        return "Incorrect parameters";
      }
      break;
    //If the client is calling createUser
    case "createUser":
      if(isset($_POST["username"]) && isset($_POST["password"])){
        return createUser($_POST["username"], $_POST["password"]));
      }
      else{
        return "Incorrect parameters";
      }
      break;
  }
  
}

//return JSON
header('Content-type:application/json;charset=utf-8');
echo json_encode($resultData);
?>

<!--
  //Read from the json
  else if($_SEVER['REQUEST_METHOD'] === 'GET') {

  }-->
