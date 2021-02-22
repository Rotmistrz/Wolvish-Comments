<?php

namespace WolvishComments/Comments;

use WolvishComments/Users/User;
use DateTime;
use PDO;

class CommentRepository {
	private $pdo;

	public function __construct(PDO $pdo) {
		$this->pdo = $pdo;
	}

	public function insert(Comment $comment) : int {

	}

	public function checkComment(Comment $comment) : int {
		$commentUser = $comment->getUser();

		if (strlen($commentUser->getNick()) == 0
			|| strlen($commentUser->getEmail()) == 0
			|| strlen($comment->getContent()) == 0) {
			return CommentResponse::INCORRECT_VALUE;
		}

		$user = $this->getUser($commentUser->getEmail());

		if ($user->isAdmin()) {
			return CommentResponse::ADMIN;
		} else if ($user->getNick() != $commentUser->getNick()) {
			return CommentResponse::INCONSISTENT_NICK;
		} else {
			return CommentResponse::OK;
		}
	}

	private function getUser(string $email) : ?User {
		$query = "SELECT u.*, a.userID AS adminID FROM " . WOLVISH_TABLE_USERS . " AS u";
		$query .= " LEFT JOIN " . WOLVISH_TABLE_ADMINS . " AS a ON a.userID = u.userID";
		$query .= " WHERE email = :email";

		$loading = $this->pdo->prepare($query);
		$loading->bindValue(':email', $email, PDO::PARAM_STR);
		$loading->execute();

		if ($result = $loading->fetch()) {
			if (!is_null($result['adminID'])) {
				return new Admin($result['email'], $result['nick']);
			} else {
				return new User($result['email'], $result['nick']);
			}
		} else {
			return null;
		}
	}
}