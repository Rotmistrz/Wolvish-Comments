<?php

namespace WolvishComments\Comments;

use WolvishComments\Users\User;
use DateTime;

class Comment {
	private $id;
	private $date;
	private $content;
	private $website;
	private $articleID;
	private $user;

	public function __construct(int $id, User $user, int $articleID, string $content) {
		$this->id = $id;
		$this->user = $user;
		$this->articleID = $articleID;
		$this->content = $content;

		$this->date = new DateTime();
		$this->website = "";
	}

	public function setID(int $id) : Comment {
		$this->id = $id;

		return $this;
	}

	public function getID() : int {
		return $this->id;
	}

	public function getUser() : User {
		return $this->user;
	}

	public function getArticleID() : int {
		return $this->articleID;
	}

	public function getContent() : string {
		return $this->content;
	}

	public function getDate() : DateTime {
		return $this->date;
	}

	public function setDate(DateTime $date) : Comment {
		$this->date = $date;

		return $this;
	}

	public function getWebsite() : string {
		return $this->website;
	}

	public function setWebsite(string $website) : Comment {
		$this->website = $website;

		return $this;
	}
}