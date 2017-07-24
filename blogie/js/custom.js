$(window).on("load", function() {
	
	$("#name").blur(function(){
		var val = this.value;
		checkName(val);
	});
	
	$("#password").blur(function(){
		var val = this.value;
		checkPassword(val);
	});
	function checkName(val){
		if(/^[^-\s][\w\s]+$/.test(val)){
			return true;
		}else{
			if(val.length != 0){
				return false;
			}
		}
	}
	function checkEmail(val){
		return true;
	}
	function checkPassword(val){
		if(/^[^-\s][\w]+$/.test(val)){
			$(".password").removeClass( "has-error" ).addClass( "has-success" );
			return true;
		}else{
			if(val.length != 0){
				$(".password").removeClass( "has-success" ).addClass( "has-error" );
				return false;
			}
		}
	}
	
	function formVerification(name,email,pass){
		var ch_name  = checkName(name);
		var ch_email = checkEmail(email);
		var ch_pass  = checkPassword(pass);
		
		if(ch_name && ch_email && ch_pass){
			return true;
		}else{
			return false;
		}
	}
	
	$("#registration").click(function(){
		var name = $("#name").val();
		var email = $("#email").val();
		var pass = $("#password").val();

		if(formVerification(name, email, pass) === true){
			$.post( 
				"func/process.php", 
				{ 
					name: name, 
					email: email,
					pass: pass,
					method: "register"
				}
			)
			.done(function( data ) {
				if(data){
					$("#name").val("");
					$("#email").val("");
					$("#password").val("");
					$(".alert").html('');
					$(".alert").html('<p class="text-success">Registration Successfull!</p>');
				}
			});
		}else{
			return false;
		}
	});
	
	
	
	$("#login").click(function(){
		var email = $("#email").val();
		var pass = $("#password").val();
		
		if(/^([\w\.]*).+@(.+\.com)$/.test(email) && /\S/.test(pass)){
			$.post( 
				"func/process.php", 
				{ 
					email: email,
					pass: pass,
					method: "login"
				}
			)
			.done(function( data ) {
				if(data){
					window.location.assign("index.php");
				}
			});
		}else{
			
		}
	});
	
	$("#blog_post").click(function(){
		var title = $("#blog_title").val();
		var body = $("#blog_body").val();
		if(/\S/.test(title) && /\S/.test(body)){
			$.post( 
				"func/process.php", 
				{ 
					title: title,
					body: body,
					method: "blog_post"
				}
			)
			.done(function( data ) {
				$("#blog_title").val("");
				$("#blog_body").val("");
				$(".alert").html('');
				$(".alert").html('<p class="text-success">Blog posted!</p>');
			});
		}else{
			
		}
	});
	
	$.post( 
		"func/process.php", 
		{ 
			method: "view_blog_post"
		}
	)
	.done(function( data ) {
		$(".blog_list").html(data);
	});
	
	$("#blog_edit").click(function(){
		$(".view_body").hide();
		$(".update_body").show();
	});
	
	$("#blog_cancel").click(function(){
		$(".view_body").show();
		$(".update_body").hide();
	});
	
	$("#blog_delete").click(function(){
		var blog_id = $("#hidden_id").val();
		if(confirm("Are you sure you want to delete this post?")){
			$.post( 
				"func/process.php", 
				{ 
					blog_id: blog_id,
					method: "blog_delete"
				}
			)
			.done(function( data ) {
				window.location.assign("index.php");
			});
		}
	});
	
	$("#blog_update").click(function(){
		var id = $("#blog_id").val();
		var title = $("#blog_title").val();
		var body = $("#blog_body").val();
		if(/\S/.test(title) && /\S/.test(body)){
			$.post( 
				"func/process.php", 
				{ 
					title: title,
					id: id,
					body: body,
					method: "blog_update"
				}
			)
			.done(function( data ) {
				$.post( 
					"func/process.php", 
					{ 
						id: id,
						method: "blog_view"
					}
				)
				.done(function( data ) {
					$(".view_body").html(data);
				});
				$(".alert").html('');
				$(".alert").html('<p class="text-success">Blog updated!</p>');
			});
		}
	});
	
	$("#comment_post").click(function(){
		var blog_id = $("#blog_id").val();
		var body = $("#comment_body").val();
		if(/\S/.test(body)){
			$.post( 
				"func/process.php", 
				{ 
					blog_id: blog_id,
					body: body,
					method: "comment_insert"
				}
			)
			.done(function( data ) {
				if(data){
					$.post( 
						"func/process.php", 
						{ 
							blog_id: blog_id,
							method: "comment_view"
						}
					)
					.done(function( data ) {
						$("#comment_body").val("");
						$(".comment_list").html(data);
					});
				}
			});
		}
	});
	
});