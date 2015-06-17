<?php
// Hooks and Filters
require_once('wps_default_groups_hooks_and_filters.php');

// Admin
if (is_admin())
	require_once('wps_default_groups_admin.php');



?>