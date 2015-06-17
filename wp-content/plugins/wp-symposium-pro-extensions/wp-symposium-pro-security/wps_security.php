<?php
// Hooks and Filters
require_once('wps_security_hooks_and_filters.php');

if (is_admin())
	require_once('wps_security_admin.php');
?>