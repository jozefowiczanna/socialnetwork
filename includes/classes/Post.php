<?php 
  class Post {
    private $user_obj;
    private $con;

    public function __construct($con, $user) {
      $this->con = $con;
      $this->user_obj = new User($con, $user);
    }

    public function deletePost($post_id) {
      $query = mysqli_query($this->con, "UPDATE posts SET deleted='yes' WHERE id='$post_id'");
    }

    public function submitPost($body, $user_to) {
      // TODO replace with htmlspecialchars??
      $body = strip_tags($body);
      $body = str_replace('\r\n', '\n', $body);
      $body = nl2br($body);
      $body = mysqli_real_escape_string($this->con, $body);
      $check_empty = preg_replace('/\s+/', '', $body);

      if ($check_empty != "") {
        $date_added = date("Y-m-d H:i:s");
        $added_by = $this->user_obj->getUsername();

        if($user_to == $added_by) {
          $user_to = "none";
        }
        
        mysqli_query($this->con, "SET CHARSET utf8");
        mysqli_query($this->con, "SET NAMES `utf8` COLLATE `utf8_unicode_ci`");
        $query = mysqli_query($this->con, "INSERT into posts values(NULL, '$body', '$added_by', '$user_to', '$date_added', 'no', 'no', '0')");
        $returned_id = mysqli_insert_id($this->con);

        // insert notification

        // update post count for user
        $num_posts = $this->user_obj->getNumPosts();
        $num_posts++;
        $update_query = mysqli_query($this->con, "UPDATE users SET num_posts='$num_posts' WHERE username='$added_by'");
      }
    }

    public function loadPostsFriends($data, $limit) {

      $page = $data['page']; 
      $userLoggedIn = $this->user_obj->getUsername();
  
      if($page == 1) {
        $start = 0;
      } else { 
        $start = ($page - 1) * $limit;
      }

      $str = "";
      $data_query = mysqli_query($this->con, "SELECT * FROM posts WHERE deleted='no' ORDER BY id DESC");
     
      if(mysqli_num_rows($data_query) > 0) {

        $num_iterations = 0;
        $count = 1;

        while($row = mysqli_fetch_array($data_query)):
          
          $id = $row['id'];
          $body = $row['body'];
          $added_by = $row['added_by'];
          $date_added = $row['date_added'];
          
          // Prepare user_to string so it can be included even if not posted to a user
          if ($row['user_to'] == "none") {
            $user_to = "";
          } else {
            $user_to_obj = new User($this->con, $row['user_to']);
            $user_to_name = $user_to_obj->getFirstAndLastName();
            $user_to_name = str_replace(" ", "&nbsp;", $user_to_name);
            $user_to = "to <a href='" . $row['user_to'] . "' class='post__profile-link'>" . $user_to_name . "</a>";
          }

          // Check if user who posted has their account closed
          $added_by_obj = new User($this->con, $added_by);
          if ($added_by_obj->isClosed()) {
            continue;
          }

          $user_logged_obj = new User($this->con, $userLoggedIn);

          if ($user_logged_obj->isFriend($added_by)):

            if ($num_iterations++ < $start) {
              continue;
            }

            //Once 10 posts have been loaded, break
            if($count > $limit) {
              break;
            }
            else {
              $count++;
            }


            // TODO wouldn't it be better to use User object (getFirstAndLastName() exists, getProfilePic() has to be added)
            // it would be new User instance $added_by
            $user_details_query = mysqli_query($this->con, "SELECT first_name, last_name, profile_pic FROM users WHERE username='$added_by'");
            $user_row = mysqli_fetch_array($user_details_query);
            $first_name = $user_row['first_name'];
            $last_name = $user_row['last_name'];
            $profile_pic = $user_row['profile_pic'];

            ?>
            <script>
            function toggle<?php echo $id; ?>() {

              if (event.target.classList.contains("options__comments-nr")) {
                var element = document.getElementById("toggleComment<?php echo $id; ?>");
                if (element.style.display == "block") {
                  element.style.display = "none";
                } else {
                  element.style.display = "block";
                }
              }
            }

            </script>
            <?php
            $time_message = get_timeframe($date_added);

            $comments_check = mysqli_query($this->con, "SELECT * FROM comments WHERE post_id='$id'");
            $comments_check_num = mysqli_num_rows($comments_check);

            if ($userLoggedIn == $added_by) {
              $button = "<button class='button button--delete button--danger' data-id='$id'>x</button>";
            } else {
              $button = "";
            }

            // TODO refactor to BEM
            // 'javascript:toggle$id ??'
            $str .= "<div class='post' onClick='javascript:toggle$id()'>
                      <a href='$added_by' class='post__img-outer'>
                        <img src='$profile_pic' class='post__img'>
                      </a>
                      <div class='post__info'>
                        <a href='$added_by' class='post__profile-link'>$first_name $last_name </a> $user_to <span>$time_message</span>
                      </div>
                      <div id='post_body' class='post__body'>
                        $body
                      </div>

                      <div class='post__options options'>
                        <span class='options__comments-nr'>Comments ($comments_check_num)&nbsp;&nbsp;&nbsp;
                        <iframe src='like_frame.php?post_id=$id' scrolling='no' class='post__frame-likes'></iframe>
                      </div>
                      <div class='post__comment' id='toggleComment$id' style='display:none'>
                        <iframe src='comment_frame.php?post_id=$id' class='post__frame-comments' id='comment_iframe' frameborder='0'></iframe>
                      </div>
                      $button
                    </div>
                    ";
          endif; // End if isFriend
        endwhile; //End while loop

        if($count > $limit)  {
          $str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
          <input type='hidden' class='noMorePosts' value='false'>";
        } else {
          $str .= "<input type='hidden' class='noMorePosts' value='true'><p style='text-align: centre;'> No more posts to show! </p>";
        }
      }

      echo $str;
    }

    public function loadProfilePosts($data, $limit) {

      $page = $data['page']; 
      $profileUser = $data['profileUsername'];
      $userLoggedIn = $this->user_obj->getUsername();
  
      if($page == 1) {
        $start = 0;
      } else { 
        $start = ($page - 1) * $limit;
      }

      $str = "";
      $data_query = mysqli_query($this->con, "SELECT * FROM posts WHERE deleted='no' AND ((added_by='$profileUser' AND user_to='none') OR user_to='$profileUser') ORDER BY id DESC");
      if(mysqli_num_rows($data_query) > 0) {

        $num_iterations = 0;
        $count = 1;

        while($row = mysqli_fetch_array($data_query)):
          
          $id = $row['id'];
          $body = $row['body'];
          $added_by = $row['added_by'];
          $date_added = $row['date_added'];

          $user_logged_obj = new User($this->con, $userLoggedIn);
          
          if ($num_iterations++ < $start) {
            continue;
          }

          //Once 10 posts have been loaded, break
          if($count > $limit) {
            break;
          }
          else {
            $count++;
          }


          // TODO wouldn't it be better to use User object (getFirstAndLastName() exists, getProfilePic() has to be added)
          // it would be new User instance $added_by
          $user_details_query = mysqli_query($this->con, "SELECT first_name, last_name, profile_pic FROM users WHERE username='$added_by'");
          $user_row = mysqli_fetch_array($user_details_query);
          $first_name = $user_row['first_name'];
          $last_name = $user_row['last_name'];
          $profile_pic = $user_row['profile_pic'];

          ?>
          <script>
          function toggle<?php echo $id; ?>() {

            if (event.target.classList.contains("options__comments-nr")) {
              var element = document.getElementById("toggleComment<?php echo $id; ?>");
              if (element.style.display == "block") {
                element.style.display = "none";
              } else {
                element.style.display = "block";
              }
            }
          }

          </script>
          <?php
          $time_message = get_timeframe($date_added);

          $comments_check = mysqli_query($this->con, "SELECT * FROM comments WHERE post_id='$id'");
          $comments_check_num = mysqli_num_rows($comments_check);

          if ($userLoggedIn == $added_by) {
            $button = "<button class='button button--delete button--danger' data-id='$id'>x</button>";
          } else {
            $button = "";
          }

          // TODO refactor to BEM
          // 'javascript:toggle$id ??'
          $str .= "<div class='post' onClick='javascript:toggle$id()'>
                    <a href='$added_by' class='post__img-outer'>
                      <img src='$profile_pic' class='post__img'>
                    </a>
                    <div class='post__info'>
                      <a href='$added_by' class='post__profile-link'>$first_name $last_name </a> <span>$time_message</span>
                    </div>
                    <div id='post_body' class='post__body'>
                      $body
                    </div>

                    <div class='post__options options'>
                      <span class='options__comments-nr'>Comments ($comments_check_num)&nbsp;&nbsp;&nbsp;
                      <iframe src='like_frame.php?post_id=$id' scrolling='no' class='post__frame-likes'></iframe>
                    </div>
                    <div class='post__comment' id='toggleComment$id' style='display:none'>
                      <iframe src='comment_frame.php?post_id=$id' class='post__frame-comments' id='comment_iframe' frameborder='0'></iframe>
                    </div>
                    $button
                  </div>
                  ";
        endwhile; //End while loop

        if($count > $limit)  {
          $str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
          <input type='hidden' class='noMorePosts' value='false'>";
        } else {
          $str .= "<input type='hidden' class='noMorePosts' value='true'><p style='text-align: centre;'> No more posts to show! </p>";
        }
      }

      echo $str;
    }
  }

?>