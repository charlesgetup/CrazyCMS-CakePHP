<?php
class AppSchema extends CakeSchema {

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {

		if (isset($event['create'])) {

			App::uses('ClassRegistry', 'Utility');

	        switch ($event['create']) {
	            case 'countries':
	                $Country = ClassRegistry::init('Country');
	                $Country->create();
	                $Country->save(
	                    array('Country' =>
	                        array('name' => 'Afghanistan', 'code' => 'AF'),
							array('name' => 'land', 'code' => 'AX'),
							array('name' => 'Albania', 'code' => 'AL'),
							array('name' => 'Algeria', 'code' => 'DZ'),
							array('name' => 'American Samoa', 'code' => 'AS'),
							array('name' => 'Andorra', 'code' => 'AD'),
							array('name' => 'Angola', 'code' => 'AO'),
							array('name' => 'Anguilla', 'code' => 'AI'),
							array('name' => 'Antarctica', 'code' => 'AQ'),
							array('name' => 'Antigua and Barbuda', 'code' => 'AG'),
							array('name' => 'Argentina', 'code' => 'AR'),
							array('name' => 'Armenia', 'code' => 'AM'),
							array('name' => 'Aruba', 'code' => 'AW'),
							array('name' => 'Australia', 'code' => 'AU'),
							array('name' => 'Austria', 'code' => 'AT'),
							array('name' => 'Azerbaidjan', 'code' => 'AZ'),
							array('name' => 'Bahamas', 'code' => 'BS'),
							array('name' => 'Bahrain', 'code' => 'BH'),
							array('name' => 'Bangladesh', 'code' => 'BD'),
							array('name' => 'Barbados', 'code' => 'BB'),
							array('name' => 'Belarus', 'code' => 'BY'),
							array('name' => 'Belgium', 'code' => 'BE'),
							array('name' => 'Belize', 'code' => 'BZ'),
							array('name' => 'Benin', 'code' => 'BJ'),
							array('name' => 'Bermuda', 'code' => 'BM'),
							array('name' => 'Bhutan', 'code' => 'BT'),
							array('name' => 'Bolivia', 'code' => 'BO'),
							array('name' => 'Bonaire', 'code' => 'BQ'),
							array('name' => 'Bosnia and Herzegovina', 'code' => 'BA'),
							array('name' => 'Botswana', 'code' => 'BW'),
							array('name' => 'Bouvet Island', 'code' => 'BV'),
							array('name' => 'Brazil', 'code' => 'BR'),
							array('name' => 'British Indian Ocean Territory', 'code' => 'IO'),
							array('name' => 'British Virgin Islands', 'code' => 'VG'),
							array('name' => 'Brunei Darussalam', 'code' => 'BN'),
							array('name' => 'Bulgaria', 'code' => 'BG'),
							array('name' => 'Burkina Faso', 'code' => 'BF'),
							array('name' => 'Burundi', 'code' => 'BI'),
							array('name' => 'Cabo Verde', 'code' => 'CV'),
							array('name' => 'Cambodia', 'code' => 'KH'),
							array('name' => 'Cameroon', 'code' => 'CM'),
							array('name' => 'Canada', 'code' => 'CA'),
							array('name' => 'Cape Verde', 'code' => 'CV'),
							array('name' => 'Cayman Islands', 'code' => 'KY'),
							array('name' => 'Central African Republic', 'code' => 'CF'),
							array('name' => 'Chad', 'code' => 'TD'),
							array('name' => 'Chile', 'code' => 'CL'),
							array('name' => 'China', 'code' => 'CN'),
							array('name' => 'Christmas Island', 'code' => 'CX'),
							array('name' => 'Cocos [Keeling] Islands', 'code' => 'CC'),
							array('name' => 'Colombia', 'code' => 'CO'),
							array('name' => 'Comoros', 'code' => 'KM'),
							array('name' => 'Congo', 'code' => 'CD'),
							array('name' => 'Cook Islands', 'code' => 'CK'),
							array('name' => 'Costa Rica', 'code' => 'CR'),
							array('name' => 'Croatia', 'code' => 'HR'),
							array('name' => 'Cuba', 'code' => 'CU'),
							array('name' => 'Cyprus', 'code' => 'CY'),
							array('name' => 'Czech Republic', 'code' => 'CZ'),
							array('name' => 'Democratic Republic of Timor-Leste', 'code' => 'TL'),
							array('name' => 'Denmark', 'code' => 'DK'),
							array('name' => 'Djibouti', 'code' => 'DJ'),
							array('name' => 'Dominica', 'code' => 'DM'),
							array('name' => 'Dominican Republic', 'code' => 'DO'),
							array('name' => 'East Timor', 'code' => 'TL'),
							array('name' => 'Ecuador', 'code' => 'EC'),
							array('name' => 'Egypt', 'code' => 'EG'),
							array('name' => 'El Salvador', 'code' => 'SV'),
							array('name' => 'Equatorial Guinea', 'code' => 'GQ'),
							array('name' => 'Eritrea', 'code' => 'ER'),
							array('name' => 'Estonia', 'code' => 'EE'),
							array('name' => 'Ethiopia', 'code' => 'ET'),
							array('name' => 'Falkland Islands', 'code' => 'FK'),
							array('name' => 'Faroe Islands', 'code' => 'FO'),
							array('name' => 'Fiji', 'code' => 'FJ'),
							array('name' => 'Finland', 'code' => 'FI'),
							array('name' => 'France', 'code' => 'FR'),
							array('name' => 'France (European Territory)', 'code' => 'FR'),
							array('name' => 'French Guyana', 'code' => 'GF'),
							array('name' => 'French Southern Territories', 'code' => 'TF'),
							array('name' => 'Gabon', 'code' => 'GA'),
							array('name' => 'Gambia', 'code' => 'GM'),
							array('name' => 'Georgia', 'code' => 'GE'),
							array('name' => 'Germany', 'code' => 'DE'),
							array('name' => 'Ghana', 'code' => 'GH'),
							array('name' => 'Gibraltar', 'code' => 'GI'),
							array('name' => 'Great Britain', 'code' => 'GB'),
							array('name' => 'Greece', 'code' => 'GR'),
							array('name' => 'Greenland', 'code' => 'GL'),
							array('name' => 'Grenada', 'code' => 'GD'),
							array('name' => 'Guadeloupe (French)', 'code' => 'GP'),
							array('name' => 'Guam (USA)', 'code' => 'GU'),
							array('name' => 'Guatemala', 'code' => 'GT'),
							array('name' => 'Guinea', 'code' => 'GN'),
							array('name' => 'Guinea Bissau', 'code' => 'GW'),
							array('name' => 'Guyana', 'code' => 'GY'),
							array('name' => 'Haiti', 'code' => 'HT'),
							array('name' => 'Heard and McDonald Islands', 'code' => 'HM'),
							array('name' => 'Honduras', 'code' => 'HN'),
							array('name' => 'Hong Kong', 'code' => 'HK'),
							array('name' => 'Hungary', 'code' => 'HU'),
							array('name' => 'Iceland', 'code' => 'IS'),
							array('name' => 'India', 'code' => 'IN'),
							array('name' => 'Indonesia', 'code' => 'ID'),
							array('name' => 'Iran', 'code' => 'IR'),
							array('name' => 'Iraq', 'code' => 'IQ'),
							array('name' => 'Ireland', 'code' => 'IE'),
							array('name' => 'Isle of Man', 'code' => 'IM'),
							array('name' => 'Israel', 'code' => 'IL'),
							array('name' => 'Italy', 'code' => 'IT'),
							array('name' => 'Ivory Coast', 'code' => 'CI'),
							array('name' => 'Jamaica', 'code' => 'JM'),
							array('name' => 'Japan', 'code' => 'JP'),
							array('name' => 'Jordan', 'code' => 'JO'),
							array('name' => 'Kazakhstan', 'code' => 'KZ'),
							array('name' => 'Kenya', 'code' => 'KE'),
							array('name' => 'Kiribati', 'code' => 'KI'),
							array('name' => 'Kuwait', 'code' => 'KW'),
							array('name' => 'Kyrgyzstan', 'code' => 'KG'),
							array('name' => 'Laos', 'code' => 'LA'),
							array('name' => 'Latvia', 'code' => 'LV'),
							array('name' => 'Lebanon', 'code' => 'LB'),
							array('name' => 'Lesotho', 'code' => 'LS'),
							array('name' => 'Liberia', 'code' => 'LR'),
							array('name' => 'Libya', 'code' => 'LY'),
							array('name' => 'Liechtenstein', 'code' => 'LI'),
							array('name' => 'Lithuania', 'code' => 'LT'),
							array('name' => 'Luxembourg', 'code' => 'LU'),
							array('name' => 'Macau', 'code' => 'MO'),
							array('name' => 'Macedonia', 'code' => 'MK'),
							array('name' => 'Madagascar', 'code' => 'MG'),
							array('name' => 'Malawi', 'code' => 'MW'),
							array('name' => 'Malaysia', 'code' => 'MY'),
							array('name' => 'Maldives', 'code' => 'MV'),
							array('name' => 'Mali', 'code' => 'ML'),
							array('name' => 'Malta', 'code' => 'MT'),
							array('name' => 'Marshall Islands', 'code' => 'MH'),
							array('name' => 'Martinique (French)', 'code' => 'MQ'),
							array('name' => 'Mauritania', 'code' => 'MR'),
							array('name' => 'Mauritius', 'code' => 'MU'),
							array('name' => 'Mayotte', 'code' => 'YT'),
							array('name' => 'Mexico', 'code' => 'MX'),
							array('name' => 'Micronesia', 'code' => 'FM'),
							array('name' => 'Moldavia', 'code' => 'MD'),
							array('name' => 'Monaco', 'code' => 'MC'),
							array('name' => 'Mongolia', 'code' => 'MN'),
							array('name' => 'Montenegro', 'code' => 'ME'),
							array('name' => 'Montserrat', 'code' => 'MS'),
							array('name' => 'Morocco', 'code' => 'MA'),
							array('name' => 'Mozambique', 'code' => 'MZ'),
							array('name' => 'Myanmar', 'code' => 'MM'),
							array('name' => 'Namibia', 'code' => 'NA'),
							array('name' => 'Nauru', 'code' => 'NR'),
							array('name' => 'Nepal', 'code' => 'NP'),
							array('name' => 'Netherlands', 'code' => 'NL'),
							array('name' => 'Netherlands Antilles', 'code' => ''),
							array('name' => 'Neutral Zone', 'code' => ''),
							array('name' => 'New Caledonia', 'code' => 'NC'),
							array('name' => 'New Zealand', 'code' => 'NZ'),
							array('name' => 'Nicaragua', 'code' => 'NI'),
							array('name' => 'Niger', 'code' => 'NE'),
							array('name' => 'Nigeria', 'code' => 'NG'),
							array('name' => 'Niue', 'code' => 'NU'),
							array('name' => 'Norfolk Island', 'code' => 'NF'),
							array('name' => 'North Korea', 'code' => 'KP'),
							array('name' => 'Northern Mariana Islands', 'code' => 'MP'),
							array('name' => 'Norway', 'code' => 'NO'),
							array('name' => 'Oman', 'code' => 'OM'),
							array('name' => 'Pakistan', 'code' => 'PK'),
							array('name' => 'Palau', 'code' => 'PW'),
							array('name' => 'Panama', 'code' => 'PA'),
							array('name' => 'Papua New Guinea', 'code' => 'PG'),
							array('name' => 'Paraguay', 'code' => 'PY'),
							array('name' => 'Peru', 'code' => 'PE'),
							array('name' => 'Philippines', 'code' => 'PH'),
							array('name' => 'Pitcairn Islands', 'code' => 'PN'),
							array('name' => 'Poland', 'code' => 'PL'),
							array('name' => 'Polynesia', 'code' => 'PF'),
							array('name' => 'Portugal', 'code' => 'PT'),
							array('name' => 'Puerto Rico', 'code' => 'PR'),
							array('name' => 'Qatar', 'code' => 'QA'),
							array('name' => 'Reunion', 'code' => 'RE'),
							array('name' => 'Romania', 'code' => 'RO'),
							array('name' => 'Russian Federation', 'code' => 'RU'),
							array('name' => 'Rwanda', 'code' => 'RW'),
							array('name' => 'Saint BarthŽlemy', 'code' => 'BL'),
							array('name' => 'Saint Helena', 'code' => 'SH'),
							array('name' => 'Saint Lucia', 'code' => 'LC'),
							array('name' => 'Saint Martin', 'code' => 'MF'),
							array('name' => 'Saint Pierre and Miquelon', 'code' => 'PM'),
							array('name' => 'Saint Vincent and the Grenadines', 'code' => 'VC'),
							array('name' => 'Samoa', 'code' => 'WS'),
							array('name' => 'San Marino', 'code' => 'SM'),
							array('name' => 'S‹o TomŽ and Pr’ncipe', 'code' => 'ST'),
							array('name' => 'Saudi Arabia', 'code' => 'SA'),
							array('name' => 'Senegal', 'code' => 'SN'),
							array('name' => 'Seychelles', 'code' => 'SC'),
							array('name' => 'Sierra Leone', 'code' => 'SL'),
							array('name' => 'Singapore', 'code' => 'SG'),
							array('name' => 'Sint Maarten', 'code' => 'SX'),
							array('name' => 'Slovak Republic', 'code' => 'SK'),
							array('name' => 'Slovenia', 'code' => 'SI'),
							array('name' => 'Solomon Islands', 'code' => 'SB'),
							array('name' => 'Somalia', 'code' => 'SO'),
							array('name' => 'South Africa', 'code' => 'ZA'),
							array('name' => 'South Georgia and the South Sandwich Islands', 'code' => 'GS'),
							array('name' => 'South Korea', 'code' => 'KR'),
							array('name' => 'South Sudan', 'code' => 'SS'),
							array('name' => 'Spain', 'code' => 'ES'),
							array('name' => 'Sri Lanka', 'code' => 'LK'),
							array('name' => 'St Kitts and Nevis', 'code' => 'KN'),
							array('name' => 'Sudan', 'code' => 'SD'),
							array('name' => 'Suriname', 'code' => 'SR'),
							array('name' => 'Svalbard and Jan Mayen', 'code' => 'SJ'),
							array('name' => 'Swaziland', 'code' => ''),
							array('name' => 'Sweden', 'code' => 'SE'),
							array('name' => 'Switzerland', 'code' => 'CH'),
							array('name' => 'Syria', 'code' => 'SY'),
							array('name' => 'Tadjikistan', 'code' => 'TJ'),
							array('name' => 'Taiwan', 'code' => 'TW'),
							array('name' => 'Tanzania', 'code' => 'TZ'),
							array('name' => 'Thailand', 'code' => 'TH'),
							array('name' => 'Togo', 'code' => 'TG'),
							array('name' => 'Tokelau', 'code' => 'TK'),
							array('name' => 'Tonga', 'code' => 'TO'),
							array('name' => 'Trinidad and Tobago', 'code' => 'TT'),
							array('name' => 'Tunisia', 'code' => 'TN'),
							array('name' => 'Turkey', 'code' => 'TR'),
							array('name' => 'Turkmenistan', 'code' => 'TM'),
							array('name' => 'Turks and Caicos Islands', 'code' => 'TC'),
							array('name' => 'Tuvalu', 'code' => 'TV'),
							array('name' => 'U.S. Minor Outlying Islands', 'code' => 'UM'),
							array('name' => 'U.S. Virgin Islands', 'code' => 'VI'),
							array('name' => 'Uganda', 'code' => 'UG'),
							array('name' => 'Ukraine', 'code' => 'UA'),
							array('name' => 'United Arab Emirates', 'code' => 'AE'),
							array('name' => 'United Kingdom', 'code' => 'GB'),
							array('name' => 'United States', 'code' => 'US'),
							array('name' => 'Uruguay', 'code' => 'UY'),
							array('name' => 'Uzbekistan', 'code' => 'UZ'),
							array('name' => 'Vanuatu', 'code' => 'VU'),
							array('name' => 'Vatican City', 'code' => 'VA'),
							array('name' => 'Venezuela', 'code' => 'VE'),
							array('name' => 'Vietnam', 'code' => 'VN'),
							array('name' => 'Wallis and Futuna', 'code' => 'WF'),
							array('name' => 'Western Sahara', 'code' => 'EH'),
							array('name' => 'Yemen', 'code' => 'YE'),
							array('name' => 'Zaire', 'code' => ''),
							array('name' => 'Zambia', 'code' => 'ZM'),
							array('name' => 'Zimbabwe', 'code' => 'ZW'),
	                    )
	                );
	                break;
                case 'configurations':
                	$Configuration = ClassRegistry::init('Configuration');
                	$Configuration->create();
                	$Configuration->save(
	                	array('Configuration' =>
	                		array('user_id' => 1, 'type' => 'SYSTEM', 'name' => 'CompanyName', 'value' => 'CrazySoft Pty Ltd', 'comment' => ''),
	                		array('user_id' => 1, 'type' => 'SYSTEM', 'name' => 'CompanyNameShort', 'value' => 'CrazySoft', 'comment' => ''),
							array('user_id' => 1, 'type' => 'SYSTEM', 'name' => 'CompanyAddress', 'value' => '19 Wharf Road, Melrose Park, NSW, 2114, Australia.', 'comment' => ''),
							array('user_id' => 1, 'type' => 'SYSTEM', 'name' => 'CompanyEmail', 'value' => 'contact@crazysoft.com.au', 'comment' => ''),
							array('user_id' => 1, 'type' => 'SYSTEM', 'name' => 'CompanyPhone', 'value' => '', 'comment' => ''),
							array('user_id' => 1, 'type' => 'SYSTEM', 'name' => 'CompanyDomain', 'value' => 'crazysoft.loc', 'comment' => ''),
							array('user_id' => 1, 'type' => 'SYSTEM', 'name' => 'CompanyAddressStreet', 'value' => '19 Wharf Rd', 'comment' => ''),
							array('user_id' => 1, 'type' => 'SYSTEM', 'name' => 'CompanyAddressState', 'value' => 'New South Wales', 'comment' => ''),
							array('user_id' => 1, 'type' => 'SYSTEM', 'name' => 'CompanyAddressPostcode', 'value' => '2114', 'comment' => ''),
							array('user_id' => 1, 'type' => 'SYSTEM', 'name' => 'CompanyAddressCountry', 'value' => 'Australia', 'comment' => ''),
							array('user_id' => 1, 'type' => 'SYSTEM', 'name' => 'MetaDescription', 'value' => 'CrazySoft is an Australian professional online CMS company. We develop first-class websites, online apps for our clients and we also provide enterprise level marketing solutions.', 'comment' => ''),
							array('user_id' => 1, 'type' => 'SYSTEM', 'name' => 'MetaKeywords', 'value' => 'crazy cms crazysoft web design development website seo email marketing e-commerce', 'comment' => ''),
							array('user_id' => 1, 'type' => 'SYSTEM', 'name' => 'UploadfileSizeLimit', 'value' => '5242880', 'comment' => ''),
							array('user_id' => 1, 'type' => 'SYSTEM', 'name' => 'ZMQRunning', 'value' => '0', 'comment' => ''),
							array('user_id' => 1, 'type' => 'SYSTEM', 'name' => 'ZMQMaxParallelThread', 'value' => '10', 'comment' => ''),
							array('user_id' => 1, 'type' => 'SYSTEM', 'name' => 'ZMQJobFetchInterval', 'value' => '1', 'comment' => ''),
							array('user_id' => 1, 'type' => 'SYSTEM', 'name' => 'ZMQPollFetchInterval', 'value' => '1', 'comment' => ''),
							array('user_id' => 1, 'type' => 'SYSTEM', 'name' => 'ZMQMaxFetchAmount', 'value' => '1', 'comment' => ''),
							array('user_id' => 1, 'type' => 'SYSTEM', 'name' => 'ZMQMaxIdelTime', 'value' => '30', 'comment' => ''),
							array('user_id' => 1, 'type' => 'SYSTEM', 'name' => 'ZMQMaxWorkerAmount', 'value' => '5', 'comment' => ''),
							array('user_id' => 1, 'type' => 'SYSTEM', 'name' => 'ZMQDebug', 'value' => '1', 'comment' => ''),
							array('user_id' => 1, 'type' => 'SYSTEM', 'name' => 'ZMQDebugOutputMethod', 'value' => 'CakeLog::error', 'comment' => ''),
							array('user_id' => 1, 'type' => 'SYSTEM', 'name' => 'CompanyLogo', 'value' => '/logo.png', 'comment' => ''),
							array('user_id' => 1, 'type' => 'SYSTEM', 'name' => 'CompanyLogoWidth', 'value' => '590', 'comment' => ''),
							array('user_id' => 1, 'type' => 'SYSTEM', 'name' => 'CompanyLogoHeight', 'value' => '208', 'comment' => ''),
							array('user_id' => 1, 'type' => 'PAYMENT', 'name' => 'PaymentNoticePeriod', 'value' => '7', 'comment' => 'Generate invoice X days ahead of next pay day & warning over due payment X days after the pay day'),
							array('user_id' => 1, 'type' => 'PAYMENT', 'name' => 'PaymentExpiredPeriod', 'value' => '14', 'comment' => 'Clear over due payment after X days.Downgrade monthly plan to prepaid plan.'),
							array('user_id' => 1, 'type' => 'PAYMENT', 'name' => 'Currency', 'value' => 'AUD', 'comment' => ''),
							array('user_id' => 1, 'type' => 'PAYMENT', 'name' => 'ManualDiscount', 'value' => '0', 'comment' => 'Manual payment (one time payment) discount'),
							array('user_id' => 1, 'type' => 'PAYMENT', 'name' => 'MonthlyDiscount', 'value' => '0', 'comment' => 'Monthly payment discount'),
							array('user_id' => 1, 'type' => 'PAYMENT', 'name' => 'QuarterlyDiscount', 'value' => '0.05', 'comment' => 'Quarterly payment discount'),
							array('user_id' => 1, 'type' => 'PAYMENT', 'name' => 'HalfYearDiscount', 'value' => '0.1', 'comment' => 'Half year payment discount'),
							array('user_id' => 1, 'type' => 'PAYMENT', 'name' => 'AnnuallyDiscount', 'value' => '0.15', 'comment' => 'Annually payment discount'),
							array('user_id' => 1, 'type' => 'PAYMENT', 'name' => 'RecurringPaymentFailAttempts', 'value' => '10', 'comment' => 'Try to collect failed recurring payment'),
							array('user_id' => 1, 'type' => 'PAYMENT', 'name' => 'TaxGSTRate', 'value' => '0', 'comment' => 'GST tax rate'),
	                		array('user_id' => 1, 'type' => 'PAYMENT', 'name' => 'PayPalIPNWebhokId', 'value' => '239603825A392724C', 'comment' => 'Webhook ID must be a currently valid Webhook that you created with your client ID/secret'),
							array('user_id' => 1, 'type' => 'LIVE_CHAT', 'name' => 'Unknown', 'value' => 'Unknown', 'comment' => ''),
							array('user_id' => 1, 'type' => 'EMAIL_MARKETING', 'name' => 'FreeEmails', 'value' => '100', 'comment' => ''),
							array('user_id' => 1, 'type' => 'EMAIL_MARKETING', 'name' => 'XORMask', 'value' => '76859309656749683645', 'comment' => ''),
							array('user_id' => 1, 'type' => 'EMAIL_MARKETING', 'name' => 'ParallelTaskType', 'value' => 'EmailMarketing', 'comment' => ''),
							array('user_id' => 1, 'type' => 'EMAIL_MARKETING', 'name' => 'DKIMSelector', 'value' => 'default._domainkey', 'comment' => ''),
							array('user_id' => 1, 'type' => 'EMAIL_MARKETING', 'name' => 'SMTPHostName', 'value' => 'CrazySoftMail', 'comment' => ''),
							array('user_id' => 1, 'type' => 'EMAIL_MARKETING', 'name' => 'SMTPHost', 'value' => 'mail.crazysoft.com.au', 'comment' => ''),
							array('user_id' => 1, 'type' => 'EMAIL_MARKETING', 'name' => 'SMTPHostPort', 'value' => '465', 'comment' => ''),
							array('user_id' => 1, 'type' => 'EMAIL_MARKETING', 'name' => 'SMTPHostUsername', 'value' => 'email_marketing@crazysoft.com.au', 'comment' => ''),
							array('user_id' => 1, 'type' => 'EMAIL_MARKETING', 'name' => 'SMTPHostPassword', 'value' => 'PPvR6v@r]mB2@P', 'comment' => ''),
							array('user_id' => 1, 'type' => 'EMAIL_MARKETING', 'name' => 'DefaultEmailFrom', 'value' => 'email_marketing@crazysoft.com.au', 'comment' => ''),
							array('user_id' => 1, 'type' => 'EMAIL_MARKETING', 'name' => 'CharSet', 'value' => 'UTF-8', 'comment' => ''),
							array('user_id' => 1, 'type' => 'EMAIL_MARKETING', 'name' => 'Encoding', 'value' => '7bit', 'comment' => ''),
							array('user_id' => 1, 'type' => 'EMAIL_MARKETING', 'name' => 'ContentType', 'value' => 'text/html', 'comment' => ''),
							array('user_id' => 1, 'type' => 'EMAIL_MARKETING', 'name' => 'SMTPServerTimeout', 'value' => '5', 'comment' => ''),
							array('user_id' => 1, 'type' => 'EMAIL_MARKETING', 'name' => 'WordWrap', 'value' => '0', 'comment' => ''),
							array('user_id' => 1, 'type' => 'EMAIL_MARKETING', 'name' => 'SendFormat', 'value' => 'HTML', 'comment' => ''),
							array('user_id' => 1, 'type' => 'EMAIL_MARKETING', 'name' => 'BounceToMailBoxPassword', 'value' => 'r#7^*P1VaHT6ss', 'comment' => ''),
							array('user_id' => 1, 'type' => 'EMAIL_MARKETING', 'name' => 'BounceToMailBox', 'value' => 'email_marketing_bounce@crazysoft.com.au', 'comment' => ''),
							array('user_id' => 1, 'type' => 'EMAIL_MARKETING', 'name' => 'BounceToMailBoxUsername', 'value' => 'email_marketing_bounce@crazysoft.com.au', 'comment' => ''),
							array('user_id' => 1, 'type' => 'EMAIL_MARKETING', 'name' => 'BounceToMailBoxHost', 'value' => 'mail.crazysoft.com.au', 'comment' => ''),
							array('user_id' => 1, 'type' => 'EMAIL_MARKETING', 'name' => 'BounceToMailBoxPort', 'value' => '993', 'comment' => 'IMAP PORT'),
							array('user_id' => 1, 'type' => 'EMAIL_MARKETING', 'name' => 'SubscriberUnitPrice', 'value' => '0.0012', 'comment' => ''),
							array('user_id' => 1, 'type' => 'EMAIL_MARKETING', 'name' => 'EmailUnitPrice', 'value' => '0.00026', 'comment' => ''),
							array('user_id' => 1, 'type' => 'EMAIL_MARKETING', 'name' => 'ExtraAttributeUnitPrice', 'value' => '5', 'comment' => ''),
							array('user_id' => 1, 'type' => 'EMAIL_MARKETING', 'name' => 'EmailSenderUnitPrice', 'value' => '10', 'comment' => ''),
	                	)
                	);
                	break;
                case 'addresses':
                	$Address = ClassRegistry::init('Address');
                	$Address->create();
                	$Address->save(
                			array('Address' =>
                				array('user_id' => 1, 'country_id' => 13, 'type' => 'CONTACT', 'same_as' => '0', 'street_address' => '19 Wharf Rd', 'suburb' => 'Melrose Park', 'postcode' => '2114', 'state' => 'NSW', 'created' => date('Y-m-d H:i:s'), 'modified' => date('Y-m-d H:i:s')),
                				array('user_id' => 1, 'country_id' => 13, 'type' => 'BILLING', 'same_as' => '1', 'street_address' => '19 Wharf Rd', 'suburb' => 'Melrose Park', 'postcode' => '2114', 'state' => 'NSW', 'created' => date('Y-m-d H:i:s'), 'modified' => date('Y-m-d H:i:s')),
                			)
                	);
                	break;
                case 'groups':
                	$Group = ClassRegistry::init('Group');
                	$Group->create();
                	$Group->save(
                			array('Group' =>
                				array('id' => 1, 'name' => 'Admin', 'model' => '', 'plugin' => ''),
								array('id' => 19, 'name' => 'Client', 'model' => '', 'plugin' => ''),
								array('id' => 4, 'name' => 'Email Marketing Client', 'model' => 'EmailMarketingUser', 'plugin' => 'EmailMarketing'),
								array('id' => 14, 'name' => 'Email Marketing Manager', 'model' => 'EmailMarketingUser', 'plugin' => 'EmailMarketing'),
								array('id' => 9, 'name' => 'Email Marketing Staff', 'model' => 'EmailMarketingUser', 'plugin' => 'EmailMarketing'),
								array('id' => 18, 'name' => 'Finance Manager', 'model' => '', 'plugin' => ''),
								array('id' => 17, 'name' => 'Finance Staff', 'model' => '', 'plugin' => ''),
								array('id' => 3, 'name' => 'IT Support Clients', 'model' => '', 'plugin' => ''),
								array('id' => 5, 'name' => 'IT Support Contractor', 'model' => '', 'plugin' => ''),
								array('id' => 13, 'name' => 'IT Support Manager', 'model' => '', 'plugin' => ''),
								array('id' => 8, 'name' => 'IT Support Staff', 'model' => '', 'plugin' => ''),
								array('id' => 6, 'name' => 'Live Chat Client', 'model' => 'LiveChatUser', 'plugin' => 'LiveChat'),
								array('id' => 16, 'name' => 'Live Chat Manager', 'model' => 'LiveChatUser', 'plugin' => 'LiveChat'),
								array('id' => 11, 'name' => 'Live Chat Staff', 'model' => 'LiveChatUser', 'plugin' => 'LiveChat'),
								array('id' => 15, 'name' => 'Marketing Manager', 'model' => 'CMSUser', 'plugin' => ''),
								array('id' => 10, 'name' => 'Marketing Staff', 'model' => 'CMSUser', 'plugin' => ''),
								array('id' => 2, 'name' => 'Web Development Client', 'model' => 'WebDevelopmentUser', 'plugin' => 'WebDevelopment'),
								array('id' => 12, 'name' => 'Web Development Manager', 'model' => 'WebDevelopmentUser', 'plugin' => 'WebDevelopment'),
								array('id' => 7, 'name' => 'Web Development Staff', 'model' => 'WebDevelopmentUser', 'plugin' => 'WebDevelopment'),
                			)
                	);
                	break;
                case 'users':
                	$User = ClassRegistry::init('User');
                	$User->create();
                	$User->save(
                			array('User' =>
                				array('id' => 1, 'first_name' => 'Charles', 'last_name' => 'Li', 'email' => 'charles@crazysoft.com.au', 'password' => '99100ccb19a6377234ad6b64ec11b59c20b02c2b', 'phone' => '0413156097', 'group_id' => 1, 'active' => 1, 'debug_log' => 0)
                			)
                	);
                	break;
	        }
	    }

	}

	public $acos = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'key' => 'index'),
		'model' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'foreign_key' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'alias' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'lft' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'rght' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'parent_id' => array('column' => 'parent_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	public $addresses = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'country_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'key' => 'index'),
		'type' => array('type' => 'string', 'null' => false, 'default' => 'CONTACT', 'length' => 20, 'collate' => 'utf8_general_ci', 'comment' => 'BILLING,CONTACT', 'charset' => 'utf8'),
		'same_as' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 20, 'collate' => 'utf8_general_ci', 'comment' => 'BILLING,CONTACT', 'charset' => 'utf8'),
		'street_address' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'suburb' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'postcode' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 20, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'state' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'user_id' => array('column' => 'user_id', 'unique' => 0),
			'country_id' => array('column' => 'country_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	public $aros = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'key' => 'index'),
		'model' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'foreign_key' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'alias' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'lft' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'rght' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'parent_id' => array('column' => 'parent_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	public $aros_acos = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'aro_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'index'),
		'aco_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'index'),
		'_create' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'_read' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'_update' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'_delete' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'aro_id' => array('column' => 'aro_id', 'unique' => 0),
			'aco_id' => array('column' => 'aco_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	public $configurations = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => '1', 'key' => 'index', 'comment' => 'User ID'),
		'type' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'value' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'comment' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 1000, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'key' => 'index'),
		'modified_by' => array('type' => 'integer', 'null' => true, 'default' => null, 'key' => 'index', 'comment' => 'User ID'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'type' => array('column' => 'type', 'unique' => 0),
			'name' => array('column' => 'name', 'unique' => 0),
			'modified' => array('column' => 'modified', 'unique' => 0),
			'modified_by' => array('column' => 'modified_by', 'unique' => 0),
			'user_id' => array('column' => 'user_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $countries = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'unique', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'code' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 2, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'name' => array('column' => 'name', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	public $groups = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'model' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'CakePHP Model name', 'charset' => 'utf8'),
		'plugin' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'CakePHP Plugin name', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	public $job_queue = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index', 'comment' => 'User ID'),
		'type' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'function' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'function_params' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'status' => array('type' => 'string', 'null' => false, 'default' => 'PENDING', 'length' => 20, 'key' => 'index', 'collate' => 'utf8_general_ci', 'comment' => 'PENDING, PROCESSING, DONE, FAILED', 'charset' => 'utf8'),
		'excution_time' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'key' => 'index', 'collate' => 'utf8_general_ci', 'comment' => 'Now or 2014-02-05 20:00:00', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'finished' => array('type' => 'datetime', 'null' => true, 'default' => null, 'key' => 'index'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'user_id' => array('column' => 'user_id', 'unique' => 0),
			'type' => array('column' => 'type', 'unique' => 0),
			'excution_time' => array('column' => 'excution_time', 'unique' => 0),
			'done' => array('column' => 'status', 'unique' => 0),
			'finished' => array('column' => 'finished', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	public $logs = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'type' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'message' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'timestamp' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'user_id' => array('column' => 'user_id', 'unique' => 0),
			'type' => array('column' => 'type', 'unique' => 0),
			'level' => array('column' => 'level', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	public $users = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'key' => 'index'),
		'first_name' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'last_name' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'email' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'unique', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'password' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 40, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'token' => array('type' => 'string', 'null' => true, 'default' => null, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'phone' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 30, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'company' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'abn_acn' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'group_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
		'debug_log' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'comment' => 'Whether or not record debug logs'),
		'session_id' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => 'PHP Session ID', 'charset' => 'latin1'),
		'last_login' => array('type' => 'datetime', 'null' => true, 'default' => null, 'key' => 'index'),
		'last_login_ip' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 15, 'collate' => 'utf8_general_ci', 'comment' => 'User last login IP (v4)', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'key' => 'index'),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'username' => array('column' => 'email', 'unique' => 1),
			'last_login' => array('column' => 'last_login', 'unique' => 0),
			'parent_id' => array('column' => 'parent_id', 'unique' => 0),
			'active' => array('column' => 'active', 'unique' => 0),
			'token' => array('column' => 'token', 'unique' => 0),
			'created' => array('column' => 'created', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

}
