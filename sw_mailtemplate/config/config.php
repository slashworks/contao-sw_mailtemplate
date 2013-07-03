<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
 *
 * @package   sw_mailtemplate
 * @author    Stefan Melz @ slashworks stefan@slash-works.de
 * @license   LGPL
 * @copyright slashworks 2013
 */


$GLOBALS['TL_HOOKS']['prepareFormData'][] = array('swMailTemplate','prepareSwMail');
