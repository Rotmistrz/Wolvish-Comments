<?php
	
	require '../config/wolvish-config.php';

	require 'external/DatabaseConnection.php';

	require '../src/Comments/Comment.php';
	require '../src/Comments/CommentRepository.php';
	//require '../src/Comments/Enums/CommentResponse.php';
	require '../src/Users/User.php';
	require '../src/Users/Admin.php';
	require '../src/Users/UserRepository.php';

	use WolvishComments\Users\User;
	use WolvishComments\Users\Admin;
	use WolvishComments\Users\UserRepository;
	use WolvishComments\Comments\Comment;
	use WolvishComments\Comments\CommentRepository;
	use WolvishComments\Comments\Enums\CommentResponse;

	$user = new User("rotmistrz129@gmail.com", "Rotmistrz2");

	$comment = new Comment(0, $user, 1, "Pięknie napisane!");

	$db = new DatabaseConnection('localhost', 'wolvish_comments', 'root', '');
	$db->connect();

	$pdo = $db->getConnection();

	$userRepository = new UserRepository($pdo);
	$commentRepository = new CommentRepository($pdo, $userRepository);

	echo $commentRepository->checkComment($comment);
?>