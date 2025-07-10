<?php

declare(strict_types=1);

return array(
	// Covered by PHPCS.
	'backward_compatibility_checks'             => false,
	'constant_variable_detection'               => true,
	'directory_list'                            => [
		'.',
	],
	'exclude_analysis_directory_list'           => [
		'legacy/',
		'storage/framework/views/',
		'tests/api-config.php',
		'tests/api-secrets.php',
		'tests/Unit/',
		'vendor/',
	],
	'minimum_target_php_version'                => '8.3',
	'plugins'                                   => [
		'AlwaysReturnPlugin',
		'DollarDollarPlugin',
		'DuplicateArrayKeyPlugin',
		'DuplicateExpressionPlugin',
		'EmptyStatementListPlugin',
		'InvalidVariableIssetPlugin',
		'NoAssertPlugin',
		'NonBoolBranchPlugin',
		'NonBoolInLogicalArithPlugin',
		'PossiblyStaticMethodPlugin',
		'PreferNamespaceUsePlugin',
		'PregRegexCheckerPlugin',
		'StrictComparisonPlugin',
		'SuspiciousParamOrderPlugin',
		'UnreachableCodePlugin',
		'UnusedSuppressionPlugin',
		'UseReturnValuePlugin',
	],
	'redundant_condition_detection'             => true,
	'strict_method_checking'                    => true,
	'strict_object_checking'                    => true,
	'strict_property_checking'                  => true,
	'strict_return_checking'                    => true,
	'suppress_issue_types'                      => [
		'PhanTypeInvalidCallableArraySize',
	],
	'target_php_version'                        => '8.3',
	'unused_variable_detection'                 => true,
	'warn_about_redundant_use_namespaced_class' => true,
	'warn_about_undocumented_throw_statements'  => true,
);
