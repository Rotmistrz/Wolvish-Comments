<?php

namespace WolvishComments\Comments;

use WolvishComments\Users\User;
use DateTime;
use PDO;
use WolvishComments\Users\UserRepository;
use WolvishComments\Comments\Enums\CommentResponse;

class CommentRepository {
	private $pdo;
	private $userRepository;

	public function __construct(PDO $pdo, UserRepository $userRepository) {
		$this->pdo = $pdo;
		$this->userRepository = $userRepository;
	}

	public function insert(Comment $comment) : ?Comment {
		$query = "INSERT INTO " . WOLVISH_TABLE_COMMENTS . " (commentID, date, articleID, userID, content, website)";
		$query .= " VALUES (NULL, :date, :articleID, :userID, :content, :website)";

		$user = $comment->getUser();

		$inserting = $this->pdo->prepare($query);
		$inserting->bindValue(':date', date('Y-m-d H:i:s'), PDO::PARAM_STR);
		$inserting->bindValue(':articleID', $comment->getArticleID(), PDO::PARAM_INT);
		$inserting->bindValue(':userID', $user->getID(), PDO::PARAM_INT);
		$inserting->bindValue(':content', $comment->getContent(), PDO::PARAM_STR);
		$inserting->bindValue(':website', $comment->getWebsite(), PDO::PARAM_STR);

		if ($inserting->execute()) {
			$comment->setID($this->pdo->lastInsertId());

			return $comment;
		} else {
			return null;
		}
	}

	public function findByArticle(int $articleID) : array {
		$query = "SELECT c.*, u.email, u.nick, a.userID AS adminID FROM " . WOLVISH_TABLE_COMMENTS . " AS c";
		$query .= " LEFT JOIN " . WOLVISH_TABLE_USERS . " AS u ON u.userID = c.userID";
		$query .= " LEFT JOIN " . WOLVISH_TABLE_ADMINS . " AS a ON a.userID = u.userID";
		$query .= " WHERE articleID = :articleID";

		$loading = $this->pdo->prepare($query);
		$loading->bindValue(':articleID', $articleID, PDO::PARAM_INT);
		$loading->execute();

		$result = [];

		$rawComments = $loading->fetchAll();

		foreach ($rawComments as $commentData) {
			$user = User::createFromRow($commentData);

			if (!is_null($user)) {
				$comment = new Comment(intval($commentData['commentID']), $user, $commentData['articleID'], $commentData['content']);
				$comment->setDate(DateTime::createFromFormat('Y-m-d H:i:s', $commentData['date']));

				$result[] = $comment;
			}
		}

		return $result;
	}

	public function checkComment(Comment $comment) : int {
		$commentUser = $comment->getUser();

		if (strlen($commentUser->getNick()) == 0
			|| strlen($commentUser->getEmail()) == 0
			|| strlen($comment->getContent()) == 0) {
			return CommentResponse::INCORRECT_VALUE;
		}

		$user = $this->userRepository->findByEmail($commentUser->getEmail());

		if (is_null($user)) {
			return CommentResponse::NO_USER;
		} else if ($user->isAdmin()) {
			return CommentResponse::ADMIN;
		} else if ($user->getNick() != $commentUser->getNick()) {
			return CommentResponse::INCONSISTENT_NICK;
		} else {
			return CommentResponse::OK;
		}
	}
}