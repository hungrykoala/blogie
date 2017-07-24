<?php
	session_start();
	require_once 'main.php';
	
	$procc = new MainProcess();
	
	$method = $_POST['method'];
	
	if($method === "register"){
		//default declarations
		$field[] = "name";
		$field[] = "email";
		$field[] = "password";
		$table = "user";
		$values = array(':name', ':email', ':password');
		//post values
		$name  = $_POST['name'];
		$email = $_POST['email'];
		$pass  = $_POST['pass'];
		$hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
		//pdo query
		$params = array(':name'=>$name, ':email'=>$email, ':password'=>$hashed_pass);
		$info = $procc->insert($field, $table, $values, $params);
		echo $info;
	}else if($method === "login"){
		$field[] = "id";
		$field[] = "name";
		$field[] = "password";
		$table = "user";
		$where = "WHERE email=:email";
		
		//post
		$email = $_POST['email'];
		$pass  = $_POST['pass'];
		$params = array(':email'=>$email);
		$result = $procc->select($field, $table, $where, $params);
		if(sizeof($result) > 0){
			$hash = $result[0]['password'];
			$id   = $result[0]['id'];
			$name   = $result[0]['name'];
			if (password_verify($pass, $hash)) {
				$_SESSION['user_id'] = $id;
				$_SESSION['user_name'] = $name;
				echo true;
			} else {
				echo false;
			}
		}
	}else if($method === "blog_post"){
		//default declarations
		$field[] = "title";
		$field[] = "body";
		$field[] = "user_id";
		$table = "blog";
		$values = array(':title', ':body', ':user_id');
		//post values
		$title  = $_POST['title'];
		$body = $_POST['body'];
		$user_id = $_SESSION['user_id'];
		//pdo query
		$params = array(':title'=>$title, ':body'=>$body, ':user_id'=>$user_id);
		$info = $procc->insert($field, $table, $values, $params);
		echo $info;
	}else if($method === "view_blog_post"){
		$field[] = "id";
		$field[] = "date_created";
		$field[] = "title";
		$field[] = "user_id";
		$table = "blog";
		$where = "ORDER BY date_created DESC";
		$params = array();
		$result = $procc->select($field, $table, $where, $params);
		if(sizeof($result) > 0){
			$temp_body = '';
			foreach($result as $row){
				$user_name = $procc->getName($row['user_id']);
				$temp_body .= '<div class="post-preview">
                    <a href="view.php?id='.$row['id'].'">
                        <h2 class="post-title">
                            '.$row['title'].'
                        </h2>
                    </a>
                    <p class="post-meta">Posted by <a href="#">'.$user_name.'</a> on '.$row['date_created'].'</p>
                </div>';
			}
			echo $temp_body;
		}
	}else if($method === "blog_delete"){
		$id = $_POST['blog_id'];
		$table = "blog";
		$where = "WHERE id=:id";
		$params = array(':id'=>$id);
		$result = $procc->delete_record($table, $where, $params);
		echo $result;
	}else if($method === "blog_update"){
		$id = $_POST['id'];
		$title = $_POST['title'];
		$body = $_POST['body'];
		$table = "blog";
		$where = "title=:title, body=:body WHERE id=:id";
		$params = array(':id'=>$id, ':title'=>$title, ':body'=>$body);
		$result = $procc->update_record($table, $where, $params);
		echo $result;
	}else if($method === "blog_view"){
		$id = $_POST['id'];
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
					$temp_body .= '
					<input type="hidden" value="'.$row['id'].'" id="hidden_id"/>
					<input type="button" value="Edit" class="btn btn-default pull-right" id="blog_edit"/>
					<input type="button" value="Delete" class="btn btn-default pull-right" id="blog_delete"/>';
			}
			echo $temp_body;
		}
	}else if($method === "comment_insert"){
		//default declarations
		$field[] = "blog_id";
		$field[] = "body";
		$field[] = "user_id";
		
		$table = "comment";
		$values = array(':blog_id', ':body', ':user_id');
		//post values
		$blog_id  = $_POST['blog_id'];
		$body = $_POST['body'];
		$user_id = $_SESSION['user_id'];
		//pdo query
		$params = array(':blog_id'=>$blog_id, ':body'=>$body, ':user_id'=>$user_id);
		$info = $procc->insert($field, $table, $values, $params);
		echo $info;
	}else if($method === "comment_view"){
		$blog_id = $_POST['blog_id'];
		$field[] = "date_created";
		$field[] = "user_id";
		$field[] = "body";
		$table = "comment";
		$where = "WHERE blog_id=:blog_id ORDER BY date_created DESC";
		$params = array(':blog_id'=>$blog_id);
		$result = $procc->select($field, $table, $where, $params);
		if(sizeof($result) > 0){
			$temp_body = '';
			foreach($result as $row){
				$user_name = $procc->getName($row['user_id']);
				$temp_body .= '<blockquote class="blockquote-reverse">
				  <p>'.$row['body'].'</p>
				  <footer>by '.$user_name.' on '.$row['date_created'].'</footer>
				</blockquote>';
			}
			echo $temp_body;
		}
	}

?>