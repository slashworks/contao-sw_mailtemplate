<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
 *
 * @package Sw_mailtemplate
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'sw',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'sw\swMailTemplate' => 'system/modules/sw_mailtemplate/classes/swMailTemplate.php',
));


/**
 * Register the template
 */
TemplateLoader::addFiles(array
(
    // Classes
    'sw_MailBody' => 'system/modules/sw_mailtemplate/templates',
    'sw_MailStyle' => 'system/modules/sw_mailtemplate/templates',
));
