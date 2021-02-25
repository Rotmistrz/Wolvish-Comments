<?php

namespace WolvishComments\Users;

class User {
	private $id;
	private $email;
	private $nick;

	public function __construct(int $id, string $email, string $nick) {
		$this->id = $id;
		$this->email = $email;
		$this->nick = $nick;
	}

	public function getID() : int {
		return $this->id;
	}

	public function getEmail() : string {
		return $this->email;
	}

	public function getNick() : string {
		return $this->nick;
	}

	public function isAdmin() : bool {
		return false;
	}

	public static function createFromRow(array $row) : ?User {
		if (isset($row['userID'])) {
			if (!is_null($row['adminID'])) {
				return new Admin(intval($row['adminID']), $row['email'], $row['nick']);
			} else {
				return new User(intval($row['userID']), $row['email'], $row['nick']);
			}
		} else {
			return null;
		}
	}
}
