<nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/social">Social</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <form class="navbar-form navbar-left" role="search" action="/social/search" method="POST">
            <div class="form-group">
              <input type="text" class="form-control" name="q" placeholder="Search">
            </div>
              <button type="submit" class="btn btn-default">Submit</button>
            </form>

            <div class="user">
              <i class="fa fa-bell-o nav-bell" id="noti-toggle" title="No new notifications"></i>
              <?php echo "<a href='/social/u/{$_SESSION['username']}'>".$_SESSION['username']."</a>"; ?>
              <a href="out">Logout</a>
          </div>
        </div><!--/.nav-collapse -->
      </div>
    </nav>