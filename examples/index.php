<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="Description" content="" />
        <meta name="Keywords" content="" />
        <meta name="Robots" content="index,follow" />
        <meta name="format-detection" content="telephone=no" />

        <title>WolvishComments</title>

        <link rel="stylesheet" href="https://use.typekit.net/xka3fqm.css" />
        <link rel="stylesheet" href="/css/master.css" />
    </head>

    <body>
		<h1>Komentarze</h1>

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

            $db = new DatabaseConnection('localhost', 'wolvish_comments', 'root', '');
            $db->connect();

            $pdo = $db->getConnection();

            $userRepository = new UserRepository($pdo);
            $commentRepository = new CommentRepository($pdo, $userRepository);

            $comments = $commentRepository->findByArticle(2);

            foreach ($comments as $comment) {
                var_dump($comment);

                echo "<br /><br />";
            }
		?>

	</body>
</html>