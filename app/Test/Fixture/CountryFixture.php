<?php
/**
 * CountryFixture
 *
 */
class CountryFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'unique', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'code' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 2, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'name' => array('column' => 'name', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '1',
			'name' => 'Afghanistan',
			'code' => ''
		),
		array(
			'id' => '2',
			'name' => 'Albania',
			'code' => ''
		),
		array(
			'id' => '3',
			'name' => 'Algeria',
			'code' => ''
		),
		array(
			'id' => '4',
			'name' => 'American Samoa',
			'code' => ''
		),
		array(
			'id' => '5',
			'name' => 'Andorra',
			'code' => ''
		),
		array(
			'id' => '6',
			'name' => 'Angola',
			'code' => ''
		),
		array(
			'id' => '7',
			'name' => 'Anguilla',
			'code' => ''
		),
		array(
			'id' => '8',
			'name' => 'Antarctica',
			'code' => ''
		),
		array(
			'id' => '9',
			'name' => 'Antigua and Barbuda',
			'code' => ''
		),
		array(
			'id' => '10',
			'name' => 'Argentina',
			'code' => ''
		),
		array(
			'id' => '11',
			'name' => 'Armenia',
			'code' => ''
		),
		array(
			'id' => '12',
			'name' => 'Aruba',
			'code' => ''
		),
		array(
			'id' => '13',
			'name' => 'Australia',
			'code' => 'AU'
		),
		array(
			'id' => '14',
			'name' => 'Austria',
			'code' => ''
		),
		array(
			'id' => '15',
			'name' => 'Azerbaidjan',
			'code' => ''
		),
		array(
			'id' => '16',
			'name' => 'Bahamas',
			'code' => ''
		),
		array(
			'id' => '17',
			'name' => 'Bahrain',
			'code' => ''
		),
		array(
			'id' => '18',
			'name' => 'Bangladesh',
			'code' => ''
		),
		array(
			'id' => '19',
			'name' => 'Barbados',
			'code' => ''
		),
		array(
			'id' => '20',
			'name' => 'Belarus',
			'code' => ''
		),
		array(
			'id' => '21',
			'name' => 'Belgium',
			'code' => ''
		),
		array(
			'id' => '22',
			'name' => 'Belize',
			'code' => ''
		),
		array(
			'id' => '23',
			'name' => 'Benin',
			'code' => ''
		),
		array(
			'id' => '24',
			'name' => 'Bermuda',
			'code' => ''
		),
		array(
			'id' => '25',
			'name' => 'Bhutan',
			'code' => ''
		),
		array(
			'id' => '26',
			'name' => 'Bolivia',
			'code' => ''
		),
		array(
			'id' => '27',
			'name' => 'Bosnia-Herzegovina',
			'code' => ''
		),
		array(
			'id' => '28',
			'name' => 'Botswana',
			'code' => ''
		),
		array(
			'id' => '29',
			'name' => 'Bouvet Island',
			'code' => ''
		),
		array(
			'id' => '30',
			'name' => 'Brazil',
			'code' => ''
		),
		array(
			'id' => '31',
			'name' => 'British Indian Ocean Territory',
			'code' => ''
		),
		array(
			'id' => '32',
			'name' => 'Brunei Darussalam',
			'code' => ''
		),
		array(
			'id' => '33',
			'name' => 'Bulgaria',
			'code' => ''
		),
		array(
			'id' => '34',
			'name' => 'Burkina Faso',
			'code' => ''
		),
		array(
			'id' => '35',
			'name' => 'Burundi',
			'code' => ''
		),
		array(
			'id' => '36',
			'name' => 'Cambodia',
			'code' => ''
		),
		array(
			'id' => '37',
			'name' => 'Cameroon',
			'code' => ''
		),
		array(
			'id' => '38',
			'name' => 'Canada',
			'code' => ''
		),
		array(
			'id' => '39',
			'name' => 'Cape Verde',
			'code' => ''
		),
		array(
			'id' => '40',
			'name' => 'Cayman Islands',
			'code' => ''
		),
		array(
			'id' => '41',
			'name' => 'Central African Republic',
			'code' => ''
		),
		array(
			'id' => '42',
			'name' => 'Chad',
			'code' => ''
		),
		array(
			'id' => '43',
			'name' => 'Chile',
			'code' => ''
		),
		array(
			'id' => '44',
			'name' => 'China',
			'code' => ''
		),
		array(
			'id' => '45',
			'name' => 'Christmas Island',
			'code' => ''
		),
		array(
			'id' => '46',
			'name' => 'Cocos (Keeling) Islands',
			'code' => ''
		),
		array(
			'id' => '47',
			'name' => 'Colombia',
			'code' => ''
		),
		array(
			'id' => '48',
			'name' => 'Comoros',
			'code' => ''
		),
		array(
			'id' => '49',
			'name' => 'Congo',
			'code' => ''
		),
		array(
			'id' => '50',
			'name' => 'Cook Islands',
			'code' => ''
		),
		array(
			'id' => '51',
			'name' => 'Costa Rica',
			'code' => ''
		),
		array(
			'id' => '52',
			'name' => 'Croatia',
			'code' => ''
		),
		array(
			'id' => '53',
			'name' => 'Cuba',
			'code' => ''
		),
		array(
			'id' => '54',
			'name' => 'Cyprus',
			'code' => ''
		),
		array(
			'id' => '55',
			'name' => 'Czech Republic',
			'code' => ''
		),
		array(
			'id' => '56',
			'name' => 'Denmark',
			'code' => ''
		),
		array(
			'id' => '57',
			'name' => 'Djibouti',
			'code' => ''
		),
		array(
			'id' => '58',
			'name' => 'Dominica',
			'code' => ''
		),
		array(
			'id' => '59',
			'name' => 'Dominican Republic',
			'code' => ''
		),
		array(
			'id' => '60',
			'name' => 'East Timor',
			'code' => ''
		),
		array(
			'id' => '61',
			'name' => 'Ecuador',
			'code' => ''
		),
		array(
			'id' => '62',
			'name' => 'Egypt',
			'code' => ''
		),
		array(
			'id' => '63',
			'name' => 'El Salvador',
			'code' => ''
		),
		array(
			'id' => '64',
			'name' => 'Equatorial Guinea',
			'code' => ''
		),
		array(
			'id' => '65',
			'name' => 'Eritrea',
			'code' => ''
		),
		array(
			'id' => '66',
			'name' => 'Estonia',
			'code' => ''
		),
		array(
			'id' => '67',
			'name' => 'Ethiopia',
			'code' => ''
		),
		array(
			'id' => '68',
			'name' => 'Falkland Islands',
			'code' => ''
		),
		array(
			'id' => '69',
			'name' => 'Faroe Islands',
			'code' => ''
		),
		array(
			'id' => '70',
			'name' => 'Fiji',
			'code' => ''
		),
		array(
			'id' => '71',
			'name' => 'Finland',
			'code' => ''
		),
		array(
			'id' => '72',
			'name' => 'France',
			'code' => ''
		),
		array(
			'id' => '73',
			'name' => 'France (European Territory)',
			'code' => ''
		),
		array(
			'id' => '74',
			'name' => 'French Guyana',
			'code' => ''
		),
		array(
			'id' => '75',
			'name' => 'French Southern Territories',
			'code' => ''
		),
		array(
			'id' => '76',
			'name' => 'Gabon',
			'code' => ''
		),
		array(
			'id' => '77',
			'name' => 'Gambia',
			'code' => ''
		),
		array(
			'id' => '78',
			'name' => 'Georgia',
			'code' => ''
		),
		array(
			'id' => '79',
			'name' => 'Germany',
			'code' => ''
		),
		array(
			'id' => '80',
			'name' => 'Ghana',
			'code' => ''
		),
		array(
			'id' => '81',
			'name' => 'Gibraltar',
			'code' => ''
		),
		array(
			'id' => '82',
			'name' => 'Great Britain',
			'code' => ''
		),
		array(
			'id' => '83',
			'name' => 'Greece',
			'code' => ''
		),
		array(
			'id' => '84',
			'name' => 'Greenland',
			'code' => ''
		),
		array(
			'id' => '85',
			'name' => 'Grenada',
			'code' => ''
		),
		array(
			'id' => '86',
			'name' => 'Guadeloupe (French)',
			'code' => ''
		),
		array(
			'id' => '87',
			'name' => 'Guam (USA)',
			'code' => ''
		),
		array(
			'id' => '88',
			'name' => 'Guatemala',
			'code' => ''
		),
		array(
			'id' => '89',
			'name' => 'Guinea',
			'code' => ''
		),
		array(
			'id' => '90',
			'name' => 'Guinea Bissau',
			'code' => ''
		),
		array(
			'id' => '91',
			'name' => 'Guyana',
			'code' => ''
		),
		array(
			'id' => '92',
			'name' => 'Haiti',
			'code' => ''
		),
		array(
			'id' => '93',
			'name' => 'Heard and McDonald Islands',
			'code' => ''
		),
		array(
			'id' => '94',
			'name' => 'Honduras',
			'code' => ''
		),
		array(
			'id' => '95',
			'name' => 'Hong Kong',
			'code' => ''
		),
		array(
			'id' => '96',
			'name' => 'Hungary',
			'code' => ''
		),
		array(
			'id' => '97',
			'name' => 'Iceland',
			'code' => ''
		),
		array(
			'id' => '98',
			'name' => 'India',
			'code' => ''
		),
		array(
			'id' => '99',
			'name' => 'Indonesia',
			'code' => ''
		),
		array(
			'id' => '100',
			'name' => 'Iran',
			'code' => ''
		),
		array(
			'id' => '101',
			'name' => 'Iraq',
			'code' => ''
		),
		array(
			'id' => '102',
			'name' => 'Ireland',
			'code' => ''
		),
		array(
			'id' => '103',
			'name' => 'Israel',
			'code' => ''
		),
		array(
			'id' => '104',
			'name' => 'Italy',
			'code' => ''
		),
		array(
			'id' => '105',
			'name' => 'Ivory Coast (Cote D`Ivoire)',
			'code' => ''
		),
		array(
			'id' => '106',
			'name' => 'Jamaica',
			'code' => ''
		),
		array(
			'id' => '107',
			'name' => 'Japan',
			'code' => ''
		),
		array(
			'id' => '108',
			'name' => 'Jordan',
			'code' => ''
		),
		array(
			'id' => '109',
			'name' => 'Kazakhstan',
			'code' => ''
		),
		array(
			'id' => '110',
			'name' => 'Kenya',
			'code' => ''
		),
		array(
			'id' => '111',
			'name' => 'Kiribati',
			'code' => ''
		),
		array(
			'id' => '112',
			'name' => 'Kuwait',
			'code' => ''
		),
		array(
			'id' => '113',
			'name' => 'Kyrgyzstan',
			'code' => ''
		),
		array(
			'id' => '114',
			'name' => 'Laos',
			'code' => ''
		),
		array(
			'id' => '115',
			'name' => 'Latvia',
			'code' => ''
		),
		array(
			'id' => '116',
			'name' => 'Lebanon',
			'code' => ''
		),
		array(
			'id' => '117',
			'name' => 'Lesotho',
			'code' => ''
		),
		array(
			'id' => '118',
			'name' => 'Liberia',
			'code' => ''
		),
		array(
			'id' => '119',
			'name' => 'Libya',
			'code' => ''
		),
		array(
			'id' => '120',
			'name' => 'Liechtenstein',
			'code' => ''
		),
		array(
			'id' => '121',
			'name' => 'Lithuania',
			'code' => ''
		),
		array(
			'id' => '122',
			'name' => 'Luxembourg',
			'code' => ''
		),
		array(
			'id' => '123',
			'name' => 'Macau',
			'code' => ''
		),
		array(
			'id' => '124',
			'name' => 'Macedonia',
			'code' => ''
		),
		array(
			'id' => '125',
			'name' => 'Madagascar',
			'code' => ''
		),
		array(
			'id' => '126',
			'name' => 'Malawi',
			'code' => ''
		),
		array(
			'id' => '127',
			'name' => 'Malaysia',
			'code' => ''
		),
		array(
			'id' => '128',
			'name' => 'Maldives',
			'code' => ''
		),
		array(
			'id' => '129',
			'name' => 'Mali',
			'code' => ''
		),
		array(
			'id' => '130',
			'name' => 'Malta',
			'code' => ''
		),
		array(
			'id' => '131',
			'name' => 'Marshall Islands',
			'code' => ''
		),
		array(
			'id' => '132',
			'name' => 'Martinique (French)',
			'code' => ''
		),
		array(
			'id' => '133',
			'name' => 'Mauritania',
			'code' => ''
		),
		array(
			'id' => '134',
			'name' => 'Mauritius',
			'code' => ''
		),
		array(
			'id' => '135',
			'name' => 'Mayotte',
			'code' => ''
		),
		array(
			'id' => '136',
			'name' => 'Mexico',
			'code' => ''
		),
		array(
			'id' => '137',
			'name' => 'Micronesia',
			'code' => ''
		),
		array(
			'id' => '138',
			'name' => 'Moldavia',
			'code' => ''
		),
		array(
			'id' => '139',
			'name' => 'Monaco',
			'code' => ''
		),
		array(
			'id' => '140',
			'name' => 'Mongolia',
			'code' => ''
		),
		array(
			'id' => '141',
			'name' => 'Montserrat',
			'code' => ''
		),
		array(
			'id' => '142',
			'name' => 'Morocco',
			'code' => ''
		),
		array(
			'id' => '143',
			'name' => 'Mozambique',
			'code' => ''
		),
		array(
			'id' => '144',
			'name' => 'Myanmar',
			'code' => ''
		),
		array(
			'id' => '145',
			'name' => 'Namibia',
			'code' => ''
		),
		array(
			'id' => '146',
			'name' => 'Nauru',
			'code' => ''
		),
		array(
			'id' => '147',
			'name' => 'Nepal',
			'code' => ''
		),
		array(
			'id' => '148',
			'name' => 'Netherlands',
			'code' => ''
		),
		array(
			'id' => '149',
			'name' => 'Netherlands Antilles',
			'code' => ''
		),
		array(
			'id' => '150',
			'name' => 'Neutral Zone',
			'code' => ''
		),
		array(
			'id' => '151',
			'name' => 'New Caledonia (French)',
			'code' => ''
		),
		array(
			'id' => '152',
			'name' => 'New Zealand',
			'code' => ''
		),
		array(
			'id' => '153',
			'name' => 'Nicaragua',
			'code' => ''
		),
		array(
			'id' => '154',
			'name' => 'Niger',
			'code' => ''
		),
		array(
			'id' => '155',
			'name' => 'Nigeria',
			'code' => ''
		),
		array(
			'id' => '156',
			'name' => 'Niue',
			'code' => ''
		),
		array(
			'id' => '157',
			'name' => 'Norfolk Island',
			'code' => ''
		),
		array(
			'id' => '158',
			'name' => 'North Korea',
			'code' => ''
		),
		array(
			'id' => '159',
			'name' => 'Northern Mariana Islands',
			'code' => ''
		),
		array(
			'id' => '160',
			'name' => 'Norway',
			'code' => ''
		),
		array(
			'id' => '161',
			'name' => 'Oman',
			'code' => ''
		),
		array(
			'id' => '162',
			'name' => 'Pakistan',
			'code' => ''
		),
		array(
			'id' => '163',
			'name' => 'Palau',
			'code' => ''
		),
		array(
			'id' => '164',
			'name' => 'Panama',
			'code' => ''
		),
		array(
			'id' => '165',
			'name' => 'Papua New Guinea',
			'code' => ''
		),
		array(
			'id' => '166',
			'name' => 'Paraguay',
			'code' => ''
		),
		array(
			'id' => '167',
			'name' => 'Peru',
			'code' => ''
		),
		array(
			'id' => '168',
			'name' => 'Philippines',
			'code' => ''
		),
		array(
			'id' => '169',
			'name' => 'Pitcairn Island',
			'code' => ''
		),
		array(
			'id' => '170',
			'name' => 'Poland',
			'code' => ''
		),
		array(
			'id' => '171',
			'name' => 'Polynesia (French)',
			'code' => ''
		),
		array(
			'id' => '172',
			'name' => 'Portugal',
			'code' => ''
		),
		array(
			'id' => '173',
			'name' => 'Puerto Rico',
			'code' => ''
		),
		array(
			'id' => '174',
			'name' => 'Qatar',
			'code' => ''
		),
		array(
			'id' => '175',
			'name' => 'Reunion (French)',
			'code' => ''
		),
		array(
			'id' => '176',
			'name' => 'Romania',
			'code' => ''
		),
		array(
			'id' => '177',
			'name' => 'Russian Federation',
			'code' => ''
		),
		array(
			'id' => '178',
			'name' => 'Rwanda',
			'code' => ''
		),
		array(
			'id' => '179',
			'name' => 'S. Georgia & S. Sandwich Isls.',
			'code' => ''
		),
		array(
			'id' => '180',
			'name' => 'Saint Helena',
			'code' => ''
		),
		array(
			'id' => '181',
			'name' => 'Saint Kitts & Nevis Anguilla',
			'code' => ''
		),
		array(
			'id' => '182',
			'name' => 'Saint Lucia',
			'code' => ''
		),
		array(
			'id' => '183',
			'name' => 'Saint Pierre and Miquelon',
			'code' => ''
		),
		array(
			'id' => '184',
			'name' => 'Saint Tome and Principe',
			'code' => ''
		),
		array(
			'id' => '185',
			'name' => 'Saint Vincent & Grenadines',
			'code' => ''
		),
		array(
			'id' => '186',
			'name' => 'Samoa',
			'code' => ''
		),
		array(
			'id' => '187',
			'name' => 'San Marino',
			'code' => ''
		),
		array(
			'id' => '188',
			'name' => 'Saudi Arabia',
			'code' => ''
		),
		array(
			'id' => '189',
			'name' => 'Senegal',
			'code' => ''
		),
		array(
			'id' => '190',
			'name' => 'Seychelles',
			'code' => ''
		),
		array(
			'id' => '191',
			'name' => 'Sierra Leone',
			'code' => ''
		),
		array(
			'id' => '192',
			'name' => 'Singapore',
			'code' => ''
		),
		array(
			'id' => '193',
			'name' => 'Slovak Republic',
			'code' => ''
		),
		array(
			'id' => '194',
			'name' => 'Slovenia',
			'code' => ''
		),
		array(
			'id' => '195',
			'name' => 'Solomon Islands',
			'code' => ''
		),
		array(
			'id' => '196',
			'name' => 'Somalia',
			'code' => ''
		),
		array(
			'id' => '197',
			'name' => 'South Africa',
			'code' => ''
		),
		array(
			'id' => '198',
			'name' => 'South Korea',
			'code' => ''
		),
		array(
			'id' => '199',
			'name' => 'Spain',
			'code' => ''
		),
		array(
			'id' => '200',
			'name' => 'Sri Lanka',
			'code' => ''
		),
		array(
			'id' => '201',
			'name' => 'Sudan',
			'code' => ''
		),
		array(
			'id' => '202',
			'name' => 'Suriname',
			'code' => ''
		),
		array(
			'id' => '203',
			'name' => 'Svalbard and Jan Mayen Islands',
			'code' => ''
		),
		array(
			'id' => '204',
			'name' => 'Swaziland',
			'code' => ''
		),
		array(
			'id' => '205',
			'name' => 'Sweden',
			'code' => ''
		),
		array(
			'id' => '206',
			'name' => 'Switzerland',
			'code' => ''
		),
		array(
			'id' => '207',
			'name' => 'Syria',
			'code' => ''
		),
		array(
			'id' => '208',
			'name' => 'Tadjikistan',
			'code' => ''
		),
		array(
			'id' => '209',
			'name' => 'Taiwan',
			'code' => ''
		),
		array(
			'id' => '210',
			'name' => 'Tanzania',
			'code' => ''
		),
		array(
			'id' => '211',
			'name' => 'Thailand',
			'code' => ''
		),
		array(
			'id' => '212',
			'name' => 'Togo',
			'code' => ''
		),
		array(
			'id' => '213',
			'name' => 'Tokelau',
			'code' => ''
		),
		array(
			'id' => '214',
			'name' => 'Tonga',
			'code' => ''
		),
		array(
			'id' => '215',
			'name' => 'Trinidad and Tobago',
			'code' => ''
		),
		array(
			'id' => '216',
			'name' => 'Tunisia',
			'code' => ''
		),
		array(
			'id' => '217',
			'name' => 'Turkey',
			'code' => ''
		),
		array(
			'id' => '218',
			'name' => 'Turkmenistan',
			'code' => ''
		),
		array(
			'id' => '219',
			'name' => 'Turks and Caicos Islands',
			'code' => ''
		),
		array(
			'id' => '220',
			'name' => 'Tuvalu',
			'code' => ''
		),
		array(
			'id' => '221',
			'name' => 'Uganda',
			'code' => ''
		),
		array(
			'id' => '222',
			'name' => 'Ukraine',
			'code' => ''
		),
		array(
			'id' => '223',
			'name' => 'United Arab Emirates',
			'code' => ''
		),
		array(
			'id' => '224',
			'name' => 'United Kingdom',
			'code' => ''
		),
		array(
			'id' => '225',
			'name' => 'United States',
			'code' => ''
		),
		array(
			'id' => '226',
			'name' => 'USA Minor Outlying Islands',
			'code' => ''
		),
		array(
			'id' => '227',
			'name' => 'Uruguay',
			'code' => ''
		),
		array(
			'id' => '228',
			'name' => 'Uzbekistan',
			'code' => ''
		),
		array(
			'id' => '229',
			'name' => 'Vanuatu',
			'code' => ''
		),
		array(
			'id' => '230',
			'name' => 'Vatican City State',
			'code' => ''
		),
		array(
			'id' => '231',
			'name' => 'Venezuela',
			'code' => ''
		),
		array(
			'id' => '232',
			'name' => 'Vietnam',
			'code' => ''
		),
		array(
			'id' => '233',
			'name' => 'Virgin Islands (British)',
			'code' => ''
		),
		array(
			'id' => '234',
			'name' => 'Virgin Islands (USA)',
			'code' => ''
		),
		array(
			'id' => '235',
			'name' => 'Wallis and Futuna Islands',
			'code' => ''
		),
		array(
			'id' => '236',
			'name' => 'Western Sahara',
			'code' => ''
		),
		array(
			'id' => '237',
			'name' => 'Yemen',
			'code' => ''
		),
		array(
			'id' => '238',
			'name' => 'Zaire',
			'code' => ''
		),
		array(
			'id' => '239',
			'name' => 'Zambia',
			'code' => ''
		),
		array(
			'id' => '240',
			'name' => 'Zimbabwe',
			'code' => ''
		),
	);

}
