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

/*
	Errors:
	e1: String length invalid
	
*/

class Validate{
	
	public function __construct(){
		
	}

	public function name($name){
		if(strlen($name) <= 15 && strlen($name) >= 1){
			$re = "/^[a-zA-Z0-9_]+/i";
		}else{
			return "e1";
		}
	}
}