$(function() {


	//  Post Validation
	$(".post form").submit(function() {
		var title, content, excerpt;

		title = $(".post form input[name='title']").val(); 
		content = $(".post form textarea").val(); 
		excerpt = $(".post form input[name='excerpt']").val(); 

		if(title.length < 30 || title.length > 100) {
			$(".post form p.title-error").fadeIn(500);
			return false;
		}else {
			$(".post form p.title-error").fadeOut(500);
		}

		if(content.length < 500 || title.length > 10000) {
			$(".post form p.content-error").fadeIn(500);
			return false;
		}else {
			$(".post form p.content-error").fadeOut(500);
		}

		if(excerpt.length !== 0){
			if(excerpt.length < 50 || excerpt.length > 200) {
				$(".post form p.excerpt-error").fadeIn(500);
				return false;
			}else {
				$(".post form p.excerpt-error").fadeOut(500);
			}
		}
		return true;
	});


	//  Admin Validation
	$(".admin form").submit(function() {
		var username, email, excerpt;

		username = $(".admin form input[name='username']").val(); 
		email = $(".admin form input[name='email']").val();

		if(username.length < 5 || username.length > 30) {
			$(".admin form p.username-error").fadeIn(500);
			return false;
		}else {
			$(".admin form p.username-error").fadeOut(500);
		}

		if(email.length < 10 || email.length > 100) {
			$(".admin form p.email-error").fadeIn(500);
			return false;
		}else {
			$(".admin form p.email-error").fadeOut(500);
		}
		return true;
	});



	//  Comments Validation
	$(".comment form").submit(function() {
		var comment;

		comment = $(".comment form textarea").val();

		if(comment.length < 20 || comment.length > 1000) {
			$(".comment form p.comment-error").fadeIn(500);
			return false;
		}else {
			$(".comment form p.comment-error").fadeOut(500);
		}
		return true;
	});

	
});