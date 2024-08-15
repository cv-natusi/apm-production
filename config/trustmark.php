<?php

return [
	'auth' => [
		'dev' => [
			'cons_id' => env('CONS_ID_TM_DEV', '21095'),
			'secret_key' => env('SECRET_KEY_TM_DEV', 'rsud6778ws122mjkrt'),
			'user_key' => [
				'vclaim' => env('USER_KEY_VCLAIM_DEV', ''),
				'jkn' => env('USER_KEY_JKN_DEV', 'dd6817bcc763343bde6eafb760f0c596'),
			],
		],
		'prod' => [
			'cons_id' => env('CONS_ID_TM_PROD', '21095'),
			'secret_key' => env('SECRET_KEY_TM_PROD', 'rsud6778ws122mjkrt'),
			'user_key' => [
				'vclaim' => env('USER_KEY_VCLAIM_PROD', '2079632035f01e757d81a8565b074768'),
				'jkn' => env('USER_KEY_JKN_PROD', '364e21ef098e7d6e69889eac7cadb3c3'),
			],
		],
	],
	'host' => [
		'jkn' => [ # Host JKN & VClaim
			'dev' => env('HOST_JKN_DEV', 'https://apijkn-dev.bpjs-kesehatan.go.id'),
			'prod' => env('HOST_JKN_PROD', 'https://apijkn.bpjs-kesehatan.go.id')
		]
	],
	'service_name' => [
		'antrean_rs' => [
			'dev' => env('SN_ANTREAN_RS_DEV','/antreanrs_dev/'),
			'prod' => env('SN_ANTREAN_RS_PROD','/antreanrs/'),
		],
		'vclaim' => [
			'dev' => env('SN_VCLAIM_DEV','/vclaim-rest-dev/'),
			'prod' => env('SN_VCLAIM_PROD','/vclaim-rest/'),
		],
	],
];