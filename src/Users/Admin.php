<?php

namespace WolvishComments/Users;

class Admin extends User {
	public function isAdmin() : bool {
		return true;
	}
}