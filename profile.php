<?php
/*

	Copyright Mackan <thormax5@gmail.com>

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
*/
session_start();

include_once "include/social.php";
include_once "include/format.php";

$s = new social;
$f = new Format;

if($_SESSION['login']){

	if(isset($_GET['user']) && !empty($_GET['user'])){
		$profile = $_GET['user'];
		#echo "Profile of ".$_GET['user'];
	}else{
		$profile = $s->getName($_SESSION['id']);
		#echo "Your profile";
	}
}else{
	if(isset($_GET['user'])){
		echo "Profile of ".$_GET['user'];
	}else{
		header("Location: ./");
	}
}
include("pages/profile.html");
