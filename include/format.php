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


class Format{

	public function __construct(){

	}

	private function linkParse($text){
		$regexUrl = '%^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@|\d{1,3}(?:\.\d{1,3}){3}|(?:(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)(?:\.(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)*(?:\.[a-z\x{00a1}-\x{ffff}]{2,6}))(?::\d+)?(?:[^\s]*)?$%iu';
		if(preg_match($regexUrl, $text, $url)){
			return preg_replace($regexUrl, "<a href='{$url[0]}''>{$url[0]}</a> ", $text);
		}else{
			return $text;
		}
	}

	private function bold($text){
		$regex = "/\*([^\*]+)\*/";
		return preg_replace($regex, '&lt;b&gt;$1&lt;&sl;b&gt;', $text);
	}

	private function italic($text){
		$regex = "/\\/([^\\/]+)\\//";
		return preg_replace($regex, '&lt;i&gt;$1&lt;&sl;i&gt;', $text);
	}

	private function strike($text){
		$regex = "/~([^~]+)~/";
		return preg_replace($regex, '&lt;s&gt;$1&lt;&sl;s&gt;', $text);
	}

	private function sup($text){
		$regex = "/\\^([^\\^]+)\\^/";
		return preg_replace($regex, '&lt;sup&gt;$1&lt;&sl;sup&gt;', $text);
	}

	private function sub($text){
		$regex = "/:([^:]+):/";
		return preg_replace($regex, '&lt;sub&gt;$1&lt;&sl;sub&gt;', $text);
	}

	private function under($text){
		$regex = "/_([^_]+)_/";
		return preg_replace($regex, '&lt;ins&gt;$1&lt;&sl;ins&gt;', $text);
	}

	private function mention($text){
		$regex = "/(^|[^a-z0-9_])@([a-z0-9_]+)/i";
		return preg_replace($regex, '$1<a href="/social/u/$2">@$2</a>', $text);
	}

	private function hashtag($text){
		$regex = "/#(.+?)(?=[\\s.,:,]|$)/i";

		/*preg_match_all($regex, $text, $tags);
		print_r($tags);
		/*if(count($tags) >= 3){
			foreach($tags[2] as $x){
				//$this->notify($this->getId($x), "@".$this->getName($from)." mentioned you!");
				echo $x;
			}
		}*/

		return preg_replace($regex, '<a href="/social/h/$1">#$1</a>', $text);

	}

	private function toHTML($text){
		return str_replace("&lt;", "<", str_replace("&gt;", ">", str_replace("&sl;", "/", $text)));
	}

	private function antiScript($text){
		return preg_replace("/<\\/script>/", "&lt;/script&gt;", preg_replace("/<script>/", "&lt;script&gt;", $text));
	}

	public function format($text, $options){
		if(in_array("link", $options)){
			$text = $this->linkParse($text);
		}
		if(in_array("bold", $options)){
			$text = $this->bold($text);
		}
		if(in_array("italic", $options)){
			$text = $this->italic($text);
		}
		if(in_array("strike", $options)){
			$text = $this->strike($text);
		}
		if(in_array("sup", $options)){
			$text = $this->sup($text);
		}
		if(in_array("sub", $options)){
			$text = $this->sub($text);
		}
		if(in_array("under", $options)){
			$text = $this->under($text);
		}
		if(in_array("mention", $options)){
			$text = $this->mention($text);
		}
		if(in_array("hashtag", $options)){
			$text = $this->hashtag($text);
		}
		if(in_array("antiScript", $options)){
			return $this->antiScript($this->toHTML($text));
		}else{
			return $this->toHTML($text);
		}
		
	}
}
