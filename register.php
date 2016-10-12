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

error_reporting(-1);

if(isset($_POST['name']) and isset($_POST['pass']) and isset($_POST['email'])){
	include_once "include/social.php";
	$s = new social;
	if($s->newUser($_POST['name'], $_POST['pass'], $_POST['email'])){
		echo "Success!";
	}else{
		echo "User already exists!";
	}
}else{
	echo "Something went wrong";
}
