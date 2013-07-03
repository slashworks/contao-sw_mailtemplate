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

//add template choices
$GLOBALS['TL_DCA']['tl_form']['fields']['sw_mail_bodyTemplate'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['sw_mailtemplate']['sw_mail_bodyTemplate'],
    'default'                 => 'raw',
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => \Contao\Controller::getTemplateGroup('sw_MailBody'),
    'eval'                    => array('tl_class'=>'w50','includeBlankOption'=>true),
    'sql'                     => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_form']['fields']['sw_mail_styleTemplate'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['sw_mailtemplate']['sw_mail_styleTemplate'],
    'default'                 => 'raw',
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => \Contao\Controller::getTemplateGroup('sw_MailStyle'),
    'eval'                    => array('tl_class'=>'w50','includeBlankOption'=>true),
    'sql'                     => "varchar(255) NOT NULL default ''"
);


//add to palletes
$GLOBALS['TL_DCA']['tl_form']['subpalettes']['sendViaEmail'] = str_replace(
    'skipEmpty',
    'skipEmpty,sw_mail_bodyTemplate,sw_mail_styleTemplate',
    $GLOBALS['TL_DCA']['tl_form']['subpalettes']['sendViaEmail']
);