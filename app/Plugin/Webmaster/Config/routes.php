<?php

/**
 * Add the `txt` and `xml` extensions.
 */
	Router::setExtensions(array('txt', 'xml'));

/**
 * Route `/robots.txt`.
 */
	Router::connect('/robots', array('plugin' => 'webmaster', 'controller' => 'webmaster_configurations', 'action' => 'robots', 'ext' => 'txt'));
	Router::promote();

/**
 * Route `/sitemap.xml`.
 */
	Router::connect('/sitemap', array('plugin' => 'webmaster', 'controller' => 'webmaster_configurations', 'action' => 'sitemap', 'ext' => 'txt'));
	Router::promote();
