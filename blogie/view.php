<?php
	require_once 'func/header.php';
	require_once 'func/main.php';
	$logged_id = $_SESSION['user_id'];
	$loggedin = (!empty($_SESSION['user_id']))?true:false;
	$procc = new MainProcess();
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Clean Blog - Start Bootstrap Theme</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

    <!-- Custom styles for this template -->
    <link href="css/clean-blog.min.css" rel="stylesheet">

    <!-- Temporary navbar container fix -->
    <style>
    .navbar-toggler {
        z-index: 1;
    }
    
    @media (max-width: 576px) {
        nav > .container {
            width: 100%;
        }
    }
    </style>

</head>

<body>

    <!-- Navigation -->
    <nav class="navbar fixed-top navbar-toggleable-md navbar-light" id="mainNav">
        <div class="container">
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                Menu <i class="fa fa-bars"></i>
            </button>
            <a class="navbar-brand" href="index.html">Start Bootstrap</a>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
					<?php
						if($loggedin){
					?>
                    <li class="nav-item">
                        <a class="nav-link" href="blog.php">Post</a>
                    </li>
					<li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
					<?php
						}else{
					?>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Register</a>
                    </li>
					<li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
						<?php } ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <header class="masthead" style="background-image: url('img/post-bg.jpg')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1">
                    <div class="post-heading">
                        <h1>Man must explore, and this is exploration at its greatest</h1>
                        <h2 class="subheading">Problems look mighty small from 150 miles up</h2>
                        <span class="meta">Posted by <a href="#">Start Bootstrap</a> on August 24, 2017</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Post Content -->
    <article>
        <div class="container">
            <div class="row">
				<div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1 update_body" style="display: none;">
				<?php
					$id = $_GET['id'];
					$field[] = "id";
					$field[] = "date_created";
					$field[] = "title";
					$field[] = "user_id";
					$field[] = "body";
					$table = "blog";
					$where = "WHERE id=:id";
					$params = array(':id'=>$id);
					$result = $procc->select($field, $table, $where, $params);
					if(sizeof($result) > 0){
						$temp_body = '';
						foreach($result as $row){
							?>
								<form>
									<input type="hidden" id="blog_id" value="<?= $id ?>"/>
									<div class="alert"></div>
									<div class="form-group">
										<label for="blog_title">Title</label>
										<input type="text" class="form-control" id="blog_title" value="<?= $row['title'] ?>" placeholder="Blog Title">
									</div>
									<div class="form-group">
										<label>Content</label>
										<textarea id="blog_body" class="form-control" rows="3"><?= $row['body'] ?></textarea>
									</div>
									<button type="button" id="blog_update" class="btn btn-default pull-right">Update It</button>
									<button type="button" id="blog_cancel" class="btn btn-default pull-right">Cancel</button>
								</form>
				<?php	
						}
					}
				?>
				</div>
                <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1 view_body">
					<?php
						$id = $_GET['id'];
						$field[] = "id";
						$field[] = "date_created";
						$field[] = "title";
						$field[] = "user_id";
						$field[] = "body";
						$table = "blog";
						$where = "WHERE id=:id";
						$params = array(':id'=>$id);
						$result = $procc->select($field, $table, $where, $params);
						if(sizeof($result) > 0){
							$temp_body = '';
							foreach($result as $row){
								$user_name = $procc->getName($row['user_id']);
								$temp_body .= '<div class="post-heading">
									<h1>'.$row['title'].'</h1>
									<h4 class="subheading">'.$row['body'].'</h2>
									<span class="meta">Posted by <a href="#">'.$user_name.'</a> on '.$row['date_created'].'</span>
								</div>';
								if($logged_id === $row['user_id']){
									$temp_body .= '
									<input type="hidden" value="'.$row['id'].'" id="hidden_id"/>
									<input type="button" value="Edit" class="btn btn-default pull-right" id="blog_edit"/>
									<input type="button" value="Delete" class="btn btn-default pull-right" id="blog_delete"/>';
								}
								
							}
							echo $temp_body;
						}
					?>
                </div>
				<div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1">
					<h1>Post a comment</h1>
                    <form>
						<div class="form-group">
							<label>Comment</label>
							<textarea id="comment_body" class="form-control" rows="3"></textarea>
						</div>
						<button type="button" id="comment_post" class="btn btn-default pull-right">Post</button>
					</form>
				</div>
				<div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1 comment_list">
				<?php
					$blog_id_ = $_GET['id'];
					$field_1[] = "date_created";
					$field_1[] = "user_id";
					$field_1[] = "body";
					$table_1 = "comment";
					$where_1 = "WHERE blog_id=:blog_id ORDER BY date_created DESC";
					$params_1 = array(':blog_id'=>$blog_id_);
					$result_1 = $procc->select($field_1, $table_1, $where_1, $params_1);
					if(sizeof($result_1) > 0){
						$temp_body_ = '';
						foreach($result_1 as $row_1){
							$user_name = $procc->getName($row_1['user_id']);
							$temp_body_ .= '<blockquote class="blockquote-reverse">
							  <p>'.$row_1['body'].'</p>
							  <footer>by '.$user_name.' on '.$row_1['date_created'].'</footer>
							</blockquote>';
						}
						echo $temp_body_;
					}
				?>
				</div>
            </div>
        </div>
    </article>

    <hr>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1">
                    <ul class="list-inline text-center">
                        <li class="list-inline-item">
                            <a href="#">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-twitter fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-facebook fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-github fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </li>
                    </ul>
                    <p class="copyright text-muted">Copyright &copy; Your Website 2017</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/tether/tether.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Custom scripts for this template -->
    <script src="js/clean-blog.min.js"></script>
    <script src="js/custom.js"></script>

</body>

</html>
