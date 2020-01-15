<?php

/**
 * Routes for comments module
 */
return array(
	'/feedback'=>'/comments/default/index',
	'/admin/comments'=>'/comments/admin/comments',
	'/admin/comments/<action>'=>'/comments/admin/comments/<action>',
);