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


class social{
	protected $db;

	public function __construct(){
		$this->db = new PDO('mysql:host=localhost;dbname=social','', '') or die("Error!");
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	public function userExist($name){
		$query = $this->db->prepare("SELECT * FROM `users` WHERE `name` = ?");
		$query->bindValue(1, $name);
		$query->execute();
		if($query->rowCount() > 0){
			return true;
		}else{
			return false;
		}
	}

	public function emailExist($email){
		$query = $this->db->prepare("SELECT * FROM `users` WHERE `email` = ?");
		$query->bindValue(1, $email);
		$query->execute();
		if($query->rowCount() > 0){
			return true;
		}else{
			return false;
		}
	}

	public function newUser($name, $pass, $email){
		$salt = uniqid();
		$pass = hash("SHA512", $pass.$salt);
		if(!$this->userExist($name)){
			if(!$this->emailExist($email)){
				$query = $this->db->prepare("INSERT INTO `users` (`name`, `pass`, `salt`, `posts`, `subs`, `email`) VALUES (?, ?, ?, ?, ?, ?)");
				$query->bindValue(1, $name);
				$query->bindValue(2, $pass);
				$query->bindValue(3, $salt);
				$query->bindValue(4, 0);
				$query->bindValue(5, json_encode(array()));
				$query->bindValue(6, trim($email));

				$query->execute();
				$this->sub($this->getId($name), $this->getId($name));
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	public function login($name, $pass){
		$query = $this->db->prepare("SELECT `salt` FROM `users` WHERE `name` = ?");
		$query->bindValue(1, $name);
		$query->execute();

		$salt = $query->fetchAll()[0]["salt"];
		$query = null;

		$query = $this->db->prepare("SELECT * FROM `users` WHERE `name` = ? AND `pass` = ?");
		$query->bindValue(1, $name);
		$query->bindValue(2, hash("SHA512", $pass.$salt));
		$query->execute();

		$data = $query->fetchAll()[0];

		if($query->rowCount() > 0){
			$_SESSION['login'] = true;
			$_SESSION['username'] = $data["name"];
			$_SESSION['id'] = $data['id'];
			header("Location: ./main");
		}else{
			header("Location: ./?e=2");
		}
	}

	public function getPosts($id){
		$query = $this->db->prepare("SELECT `posts` FROM `users` WHERE `id` = ?");
		$query->bindValue(1, $id);
		$query->execute();

		return $query->fetchAll()[0]['posts'];
	}

	public function getSubs($id){
		$query = $this->db->prepare("SELECT `subs` FROM `users` WHERE `id` = ?");
		$query->bindValue(1, $id);
		$query->execute();

		return $query->fetchAll()[0]['subs'];
	}

	public function fetchPosts($id){
		$query = $this->db->prepare("SELECT * FROM `posts` WHERE `from` = ? ORDER BY `posted` DESC");
		$query->bindValue(1, $id);
		$query->execute();
		return $query->fetchAll();
	}

	public function newPost($from, $body){
		$query = $this->db->prepare("INSERT INTO `posts` (`from`, `body`, `posted`, `star`, `liked_by`) VALUES (?,?,?,?,?) ");
		$query->bindValue(1, $from);
		$query->bindValue(2, nl2br(htmlentities($body)));
		$query->bindValue(3, date('Y-m-d H:i:s'));
		$query->bindValue(4, 0);
		$query->bindValue(5, json_encode(array()));

		$query->execute();

		$query = $this->db->prepare("UPDATE `users` SET `posts` = ? WHERE `id` = ?");
		$query->bindValue(1, $this->getPosts($from)+1);
		$query->bindValue(2, $from);
		$query->execute();

		# Mentions

		$regex = "/(^|[^a-z0-9_])@([a-z0-9_]+)/i";
		preg_match_all($regex, $body, $mentions);
		if(count($mentions) >= 3){
			foreach($mentions[2] as $x){
				$this->notify($this->getId($x), "@".$this->getName($from)." mentioned you!");
			}
		}

		# Hashtags

		$regex = "/#(.+?)(?=[\\s.,:,]|$)/i";

		preg_match_all($regex, $body, $tags);
		if(count($tags) >= 2){
			foreach($tags[0] as $x){
				$this->tag($this->getLastPost($from), $x);
			}
		}

	}

	public function getName($id){
		$query = $this->db->prepare("SELECT `name` FROM `users` WHERE `id` = ?");
		$query->bindValue(1, $id);
		$query->execute();

		$x = $query->fetchAll();
		if($x != null){
			return $x[0]['name'];
		}
	}

	public function getAvatar($id){
		$query = $this->db->prepare("SELECT `email` FROM `users` WHERE `id` = ?");
		$query->bindValue(1, $id);
		$query->execute();

		return md5(trim($query->fetchAll()[0]['email']));

	}

	public function sub($id, $sid){
		$subs = json_decode($this->getSubs($id));

		array_push($subs, $sid);

		$query = $this->db->prepare("UPDATE `users` SET `subs` = ? WHERE `id` = ?");
		$query->bindValue(1, json_encode($subs));
		$query->bindValue(2, $id);

		$query->execute();
	}

	public function getId($name){
		$query = $this->db->prepare("SELECT `id` FROM `users` WHERE `name` = ?");
		$query->bindValue(1, $name);

		$query->execute();

		return $query->fetchAll()[0]['id'];
	}

	public function search($q){
		$query = $this->db->prepare("SELECT * FROM `users` WHERE `name` LIKE ?");
		$query->bindValue(1, "%".$q."%");

		$query->execute();

		return $query->fetchAll();
	}

	public function getProfile($id){
		$query = $this->db->prepare("SELECT * FROM `users` WHERE `id` = ?");
		$query->bindValue(1, $id);

		$query->execute();

		return $query->fetchAll();
	}

	public function isSub($id, $sid){
		$subs = json_decode($this->getSubs($id));

		return in_array($sid, $subs);
	}

	public function unSub($id, $sid){
		$subs = json_decode($this->getSubs($id));

		array_splice($subs, array_search($sid, $subs), 1);

		$query = $this->db->prepare("UPDATE `users` SET `subs` = ? WHERE `id` = ?");
		$query->bindValue(1, json_encode($subs));
		$query->bindValue(2, $id);

		$query->execute();
	}

	public function removePost($id, $uid){
		$query = $this->db->prepare("DELETE FROM `posts` WHERE `id` = ?");
		$query->bindValue(1, $id);

		$query->execute();

		$query = $this->db->prepare("UPDATE `users` SET `posts` = ? WHERE `id` = ?");
		$query->bindValue(1, $this->getPosts($uid)-1);
		$query->bindValue(2, $uid);
		$query->execute();
	}

	public function getLikers($id){
		$query = $this->db->prepare("SELECT `liked_by` FROM `posts` WHERE `id` = ?");
		$query->bindValue(1, $id);

		$query->execute();

		return $query->fetchAll()[0]["liked_by"];
	}

	public function getLikes($id){
		$query = $this->db->prepare("SELECT `star` FROM `posts` WHERE `id` = ?");
		$query->bindValue(1, $id);

		$query->execute();

		return $query->fetchAll()[0]["star"];
	}

	public function likes($id, $uid){
		$likers = json_decode($this->getLikers($id));

		return in_array($uid, $likers);
	}

	public function like($id, $uid){

		$likers = json_decode($this->getLikers($id));
		array_push($likers, $uid);

		$query = $this->db->prepare("UPDATE `posts` SET `star` = ?, `liked_by` = ? WHERE `id` = ?");
		$query->bindValue(1, $this->getLikes($id)+1);
		$query->bindValue(2, json_encode($likers));
		$query->bindValue(3, $id);

		$query->execute();
	}

	public function unlike($id, $uid){

		$likers = json_decode($this->getLikers($id));
		array_splice($likers, array_search($uid,$likers), 1);
		
		$query = $this->db->prepare("UPDATE `posts` SET `star` = ?, `liked_by` = ? WHERE `id` = ?");
		$query->bindValue(1, $this->getLikes($id)-1);
		$query->bindValue(2, json_encode($likers));
		$query->bindValue(3, $id);

		$query->execute();
	}

	public function setBio($id, $bio){
		$query = $this->db->prepare("UPDATE `users` SET `bio` = ? WHERE `id` = ?");
		$query->bindValue(1, $bio);
		$query->bindValue(2, $id);
		$query->execute();
	}

	public function fetchMPosts($ids){
		
		$id = json_decode($ids);

		$s = implode(',', array_fill(0, count($id), "?"));

		$query = $this->db->prepare("SELECT * FROM `posts` WHERE `from` IN ($s) ORDER BY `posted` DESC");

		$query->execute($id);
		return $query->fetchAll();
	}

	public function getAllUsers(){
		
		$query = $this->db->prepare("SELECT * FROM `users`");
		
		$query->execute();
		
		return $query->fetchAll();

	}

	public function notify($user, $message){
		$query = $this->db->prepare("INSERT INTO `notifications` (`toUsr`, `body`, `seen`) VALUES (?,?,?)");

		$query->bindValue(1, $user);
		$query->bindValue(2, $message);
		$query->bindValue(3, 0);

		$query->execute();
	}

	public function getNotifications($user){
		$query = $this->db->prepare("SELECT * FROM `notifications` WHERE `toUsr` = ?");

		$query->bindValue(1, $user);

		$query->execute();

		return json_encode($query->fetchAll());
	}

	public function getLastPost($user){
		$query = $this->db->prepare("SELECT `id` FROM `posts` WHERE `from` = ? ORDER BY `posted` DESC LIMIT 1");

		$query->bindValue(1, $user);

		$query->execute();

		return $query->fetchAll()[0]['id'];
	}

	public function tag($post, $tag){
		$query = $this->db->prepare("INSERT INTO `tags` (`post`,`tag`) VALUES (?, ?)");

		$query->bindValue(1, $post);
		$query->bindValue(2, $tag);

		$query->execute();

	}

	public function getTag($tag){
		$query = $this->db->prepare("SELECT * FROM `tags` WHERE `tag` = ?");

		$query->bindValue(1, "#".$tag);
		$query->execute();

		return $query->fetchAll();
	}

	public function fetchTagPosts($ids){
		
		$id = $ids;

		$s = implode(',', array_fill(0, count($id), "?"));

		$query = $this->db->prepare("SELECT * FROM `posts` WHERE `id` IN ($s) ORDER BY `posted` DESC");

		$query->execute($id);
		return $query->fetchAll();
	}
}