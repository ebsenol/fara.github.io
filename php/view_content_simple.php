<!DOCTYPE html>
<html lang="en">
<head>
<!-- <meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script> -->
  
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

  <script src="http://code.jquery.com/jquery-latest.js"></script>
  <link rel="stylesheet" href="/css/upvote/jquery.upvote.css" type="text/css" media="screen">
  <script type="text/javascript" src="/css/upvote/jquery.upvote.js"></script>

  <script type="text/javascript" src="/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
  <link rel="stylesheet" href="/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" />
  <title>Fara</title>

</head>
<body >

<!-- Fixed navbar -->
   <nav id="navbarmain"  class="navbar navbar-inverse navbar-fixed-top">
       <div class="container">
         <div class="navbar-header">
           <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
             <span class="sr-only">Toggle navigation</span>
             <span class="icon-bar"></span>
             <span class="icon-bar"></span>
             <span class="icon-bar"></span>
      </button>
         <a class="navbar-brand" href="index.php">Fara</a>
    </div>

    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <li class ="active"><a href="homepage.php">Home</a></li>
        <li <input type="text" name="search" placeholder="Search.."> </li> 
        <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">Categories
            <span class="caret"></span></a>
            <ul class="dropdown-menu">
             <?php
               $sql =  "SELECT name " .
                   "FROM Category;";
            
              $result = mysqli_query($db, $sql);
              $res_array = array();
              if( $result->num_rows > 0)
                while($row = mysqli_fetch_array($result))
                  array_push($res_array, $row);
                
              foreach($res_array as $req)
              {
                $catName = $req['name'];
                echo "<li><a href='view_category.php?category=".$catName."'>". ($catName) . "</a></li>";
                  }
             ?>
            </ul>
        </li>
        
        </ul>
         <ul class="nav navbar-nav navbar-right">
        <li
          <form class="navbar-form navbar-left" role="search">
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Search">
          </div>
          <button type="submit"  class="btn btn-default">
            <span class="glyphicon glyphicon-search"></span>
          </button>
          </form>
        </li>
        <li> <p class="navbar-text"> <?php if ($usermode == 1) echo "Logged in as ".$username.""; else echo "Guest"; ?>  </p></li>
        <?php if ($usermode == 1) echo "<li><a href='logout.php'>Log out</a></li>"; else echo "<li><a href='login.php'>Log in</a></li>"; ?>
        

         </ul>
      </div>
    </div>
  </nav>

  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  
  <!-- posting comments  -->
  <div class="container">
  <div class="post-comments">

    <form>
      <div class="form-group">
        <label for="comment">Your Comment</label>
        <textarea name="comment" class="form-control" rows="3"></textarea>
      </div>
      <button type="submit" class="btn btn-default">Send</button>
    </form>

    <div class="comments-nav">
      <ul class="nav nav-pills">
        <li role="presentation" class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                  there are 2593 comments <span class="caret"></span>
                </a>
          <ul class="dropdown-menu">
            <li><a href="#">Best</a></li>
            <li><a href="#">Hot</a></li>
          </ul>
        </li>
      </ul>
    </div>

    <div class="row">

      <div class="media">
        <!-- first comment -->

        <div class="media-heading">
          <button class="btn btn-default btn-xs" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseExample"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></button> <span class="label label-info">12314</span> terminator 12 hours ago
        </div>

        <div class="panel-collapse collapse in" id="collapseOne">

          <div class="media-left">
            <div class="vote-wrap">
              <div class="save-post">
                <a href="#"><span class="glyphicon glyphicon-star" aria-label="Save"></span></a>
              </div>
              <div class="vote up">
                <i class="glyphicon glyphicon-menu-up"></i>
              </div>
              <div class="vote inactive">
                <i class="glyphicon glyphicon-menu-down"></i>
              </div>
            </div>
            <!-- vote-wrap -->
          </div>
          <!-- media-left -->


          <div class="media-body">
            <p>yazmayın artık amk, görmeyeyim sol framede. insan bi meraklanıyor, ümitleniyor. sonra yine özlem dolu yazıları görüp hayal kırıklığıyla okuyorum.</p>
            <div class="comment-meta">
              <span><a href="#">delete</a></span>
              <span><a href="#">report</a></span>
              <span><a href="#">hide</a></span>
              <span>
                        <a class="" role="button" data-toggle="collapse" href="#replyCommentT" aria-expanded="false" aria-controls="collapseExample">reply</a>
                      </span>
              <div class="collapse" id="replyCommentT">
                <form>
                  <div class="form-group">
                    <label for="comment">Your Comment</label>
                    <textarea name="comment" class="form-control" rows="3"></textarea>
                  </div>
                  <button type="submit" class="btn btn-default">Send</button>
                </form>
              </div>
            </div>
            <!-- comment-meta -->

            <div class="media">
              <!-- answer to the first comment -->

              <div class="media-heading">
                <button class="btn btn-default btn-collapse btn-xs" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseExample"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></button> <span class="label label-info">12314</span> vertu 12 sat once yazmis
              </div>

              <div class="panel-collapse collapse in" id="collapseTwo">

                <div class="media-left">
                  <div class="vote-wrap">
                    <div class="save-post">
                      <a href="#"><span class="glyphicon glyphicon-star" aria-label="Save"></span></a>
                    </div>
                    <div class="vote up">
                      <i class="glyphicon glyphicon-menu-up"></i>
                    </div>
                    <div class="vote inactive">
                      <i class="glyphicon glyphicon-menu-down"></i>
                    </div>
                  </div>
                  <!-- vote-wrap -->
                </div>
                <!-- media-left -->


                <div class="media-body">
                  <p>yazmayın artık amk, görmeyeyim sol framede. insan bi meraklanıyor, ümitleniyor. sonra yine özlem dolu yazıları görüp hayal kırıklığıyla okuyorum.</p>
                  <div class="comment-meta">
                    <span><a href="#">delete</a></span>
                    <span><a href="#">report</a></span>
                    <span><a href="#">hide</a></span>
                            <span>
                              <a class="" role="button" data-toggle="collapse" href="#replyCommentThree" aria-expanded="false" aria-controls="collapseExample">reply</a>
                            </span>
                    <div class="collapse" id="replyCommentThree">
                      <form>
                        <div class="form-group">
                          <label for="comment">Your Comment</label>
                          <textarea name="comment" class="form-control" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-default">Send</button>
                      </form>
                    </div>
                  </div>
                  <!-- comment-meta -->
                </div>
              </div>
              <!-- comments -->

            </div>
            <!-- answer to the first comment -->

          </div>
        </div>
        <!-- comments -->

      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $('[data-toggle="collapse"]').on('click', function() {
      var $this = $(this),
              $parent = typeof $this.data('parent')!== 'undefined' ? $($this.data('parent')) : undefined;
      if($parent === undefined) { /* Just toggle my  */
          $this.find('.glyphicon').toggleClass('glyphicon-plus glyphicon-minus');
          return true;
      }

      /* Open element will be close if parent !== undefined */
      var currentIcon = $this.find('.glyphicon');
      currentIcon.toggleClass('glyphicon-plus glyphicon-minus');
      $parent.find('.glyphicon').not(currentIcon).removeClass('glyphicon-minus').addClass('glyphicon-plus');
  });
</script>

    </body>
</html>