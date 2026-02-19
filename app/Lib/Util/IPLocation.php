<?php
class IPLocation {

	public function getLocationByIP($ip){

		if (filter_var($ip, FILTER_VALIDATE_IP)) {

			$location = null;

			if($geoContent = file_get_contents("http://www.geoplugin.net/php.gp?ip={$ip}")){ // 1, geoplugin.net

				$geoContent = unserialize($geoContent);
				$location = array(
					'country' 		=> $geoContent['geoplugin_countryName'],
					'region' 		=> $geoContent['geoplugin_regionName'],
					'city' 			=> $geoContent['geoplugin_city']
				);

			}elseif($geoContent = file_get_contents("http://ipinfo.io/{$ip}/geo")){ // 2, ipinfo.io

				$geoContent = json_decode($geoContent);
				$location = array(
					'country' 		=> $geoContent['country'],
					'region' 		=> $geoContent['region'],
					'city' 			=> $geoContent['city']
				);

			}elseif($geoContent = file_get_contents("http://ip-api.com/php/{$ip}")){ // 3, ip-api.com

				$geoContent = unserialize($geoContent);
				if($geoContent['status'] == 'success'){

					$location = array(
						'country' 		=> $geoContent['country'],
						'region' 		=> $geoContent['regionName'],
						'city' 			=> $geoContent['city']
					);
				}

			}

			// If we cannot get location online, try our own solution
			// Notice: use this command to load csv to mysql.
			// 		mysqlimport --ignore-lines=1 --fields-terminated-by=, (--fields-optionally-enclosed-by=\" --fields-escaped-by=\`) --local -u root -p maxmind_geolite2_city ~/Downloads/GeoLite2-City-CSV_20191029/locations_en.csv

			if(empty($location)){

				$Model = new Model();
				$Model->useTable = false;

				// Remember current data source

				$currentDataSource 		= $Model->getDataSource();
				$currentDataSourceName 	= ConnectionManager::getSourceName($currentDataSource);

				// Switch data source to max mind geo DB

				$Model->setDataSource('maxmindGeoLite2City');

				// Query location (NOTE: currently only support IPV4)

				$geoDataSource = $Model->getDataSource();
				$queryParams = array(
					$ip .'%',
					preg_replace('/^(\d+).(\d+).(\d+).(\d+)$/', '${1}.${2}.${3}.0', $ip) .'%',
					preg_replace('/^(\d+).(\d+).(\d+).(\d+)$/', '${1}.${2}.${3}', $ip) .'%', 	// Add more query params if cannot find a match
				);

				for($i = 0; $i < count($queryParams); $i++){

					$query	= 'SELECT * FROM blocks_ipv4 AS b JOIN locations_en AS l ON b.geoname_id = l.geoname_id WHERE network LIKE :network' .$i .' LIMIT 0, 1;';
					$result	= $geoDataSource->fetchAll($query, array(':network' .$i => $queryParams[$i]));

					if(!empty($result)){
						$extraChars = array('"');
						$location 	= array(
							'country' 		=> str_replace($extraChars, "", $result[0]['l']['country_name']),
							'region' 		=> str_replace($extraChars, "", $result[0]['l']['subdivision_1_name']),
							'city' 			=> str_replace($extraChars, "", $result[0]['l']['city_name'])
						);
						break;
					}
				}

				$Model->setDataSource($currentDataSourceName);

			}

			return $location;

		}else{

			return false;
		}
	}



}