<?php

namespace WolvishComments\Users;

use DateTime;
use PDO;

class UserRepository {
	private $pdo;

	public function __construct(PDO $pdo) {
		$this->pdo = $pdo;
	}

	public function insert(User $user) : ?User {

	}

	public function findByEmail(string $email) : ?User {
		$query = "SELECT u.*, a.userID AS adminID FROM " . WOLVISH_TABLE_USERS . " AS u";
		$query .= " LEFT JOIN " . WOLVISH_TABLE_ADMINS . " AS a ON a.userID = u.userID";
		$query .= " WHERE email = :email";

		$loading = $this->pdo->prepare($query);
		$loading->bindValue(':email', $email, PDO::PARAM_STR);
		$loading->execute();

		if ($result = $loading->fetch()) {
			if (!is_null($result['adminID'])) {
				return new Admin(intval($result['adminID']), $result['email'], $result['nick']);
			} else {
				return new User(intval($result['userID']), $result['email'], $result['nick']);
			}
		} else {
			return null;
		}
	}
}