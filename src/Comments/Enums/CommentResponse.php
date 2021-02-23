<?php

namespace WolvishComments\Comments\Enums;


class CommentResponse {
	const OK = 0;
	const ADMIN = 1;
	const INCONSISTENT_NICK = 2;
	const INCORRECT_VALUE = 3;
	const NO_USER = 4;
}