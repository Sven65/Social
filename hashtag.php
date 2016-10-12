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

if($_SESSION['login'] && isset($_GET['tag'])){
	include_once "include/social.php";
  include_once "include/format.php";
	$s = new social;
  $f = new Format;

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Social</title>
    <link href="/social/css/fa.css" rel="stylesheet">
    <link href="/social/css/tooltipster.css" rel="stylesheet">
    <link href="/social/css/boot.css" rel="stylesheet">
    <link href="/social/css/at.css" rel="stylesheet">
    <link href="/social/css/main.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>

    <?php include_once "include/nav.php";?>
    <?php echo "<input type='hidden' id='notifs' value='{$s->getNotifications($_SESSION['id'])}'>"; ?>
    <!-- Body -->
    <input name="id" class="hidden" value="<?php echo $_SESSION['id']; ?>">
  <!-- Posts -->
  <div class="row" id="posts">

      <div class="col-lg-5">
        <div>
           <?php
              $posts = [];
              foreach($s->getTag($_GET['tag']) as $t){
                array_push($posts, $t['post']);
              }
              foreach($s->fetchTagPosts($posts) as $x ){
            ?>
              <div class="media">
                <a class="pull-left" href="#">
                  <img class="media-object" alt="" src="http://gravatar.com/avatar/<?php echo $s->getAvatar($x['from']) ?>">
                </a>
                <div class="media-body">
                  <h5 class="media-heading"><a href="/social/u/<?php echo $s->getName($x['from']); ?>"><?php echo $s->getName($x['from']);?></a></h5>
                  <p><?php echo $f->format($x['body'], ["link", "mention", "hashtag"]); ?></p>
                  <ul class="list-inline">
                    <li><?php echo $x['posted'] ?></li>
                    <?php
                      if(!$s->likes($x['id'], $_SESSION['id'])){
                    ?>
                      <li><a href="l/<?php echo $x['id']; ?>">Like</a></li>
                    <?php
                      }else{
                    ?>
                      <li><a href="ul/<?php echo $x['id']; ?>">Unlike</a></li>
                    <?php
                      }
                    ?>
                    <li><?php echo $x['star']; ?> Likes</li>
                    <?php if($x['from'] == $_SESSION['id']){ ?> <a href="/social/rm/<?php echo $x['id']; ?>"><span class="glyphicon glyphicon-remove"></span> Remove</a> <?php } ?>
                  </ul>
                </div>
             </div>
            <br>
          <?php
            }
          ?>
        </div>
      </div>
    </div>
    
  	<script src="/social/js/jquery.js"></script>
  	<script src="/social/js/main.js"></script>
    <script src="/social/js/tooltipster.js"></script>
    <script src="/social/js/notification.js"></script>
    <script src="/social/js/bootstrap.min.js"></script>
  </body>
</html>
<?php
}else{
	header("Location: ./");
}