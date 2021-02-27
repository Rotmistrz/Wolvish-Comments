<?php

namespace Rotmistrz\WolvishComments\Users;

class Admin extends User {
	public function isAdmin() : bool {
		return true;
	}
}