<?php

namespace WolvishComments\Users;

class User {
	private $email;
	private $nick;

	public function __construct(string $email, string $nick) {
		$this->email = $email;
		$this->nick = $nick;
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
}
