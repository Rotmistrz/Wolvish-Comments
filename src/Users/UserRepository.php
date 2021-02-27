<?php

namespace Rotmistrz\WolvishComments\Users;

use DateTime;
use PDO;

class UserRepository {
	private $pdo;

	public function __construct(PDO $pdo) {
		$this->pdo = $pdo;
	}

	public static function getTable() : string {
	    return WOLVISH_TABLE_USERS;
    }

	public function insert(User $user) : ?User {
        $query = "INSERT INTO " . static::getTable() . " (email, nick)";
        $query .= " VALUES (:email, :nick)";

        $inserting = $this->pdo->prepare($query);
        $inserting->bindValue(':email', $user->getEmail(), PDO::PARAM_STR);
        $inserting->bindValue(':nick', $user->getNick(), PDO::PARAM_STR);

	    if ($inserting->execute()) {
	        $user->setID($this->pdo->lastInsertId());

	        return $user;
        } else {
	        return null;
        }
	}

	public function isNickAvailable(string $nick) : bool {
	    $query = "SELECT COUNT(*) AS amount FROM " . static::getTable() . " WHERE nick = :nick";

	    $loading = $this->pdo->prepare($query);
	    $loading->bindValue(':nick', $nick, PDO::PARAM_STR);
	    $loading->execute();

	    $result = $loading->fetch();

	    return $result['amount'] == 0;
    }

	public function findByEmail(string $email) : ?User {
		$query = "SELECT u.*, a.userID AS adminID FROM " . static::getTable() . " AS u";
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