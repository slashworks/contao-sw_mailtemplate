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

namespace sw;
use Contao\FrontendTemplate;


/**
 * Class swMailTemplate
 * @package sw
 */
class swMailTemplate{

    /** Body Template
     * @var string
     */
    protected $strBodyTemplate = 'sw_MailBody';


    /** Style Template
     * @var string
     */
    protected $strStyleTemplate = 'sw_MailStyle';


    /** Mail Data array
     * @var array
     */
    protected $arrData = array();


    /** Mail Form Object
     * @var object
     */
    protected $objForm;


    /** Mail Label array
     * @var array
     */
    protected $arrLabel = array();


    /** Prepare sw mailer
     *
     * check and set mail transfer settings
     *
     * @param $arrData
     * @param $arrLabel
     * @param $objForm
     */
    public function prepareSwMail($arrData,$arrLabel,$objForm){

        //set data
        $this->set('data',$arrData);
        $this->set('label',$arrLabel);
        $this->objForm = $objForm;

        //check mail transfer settings
        if($this->startSwMailer()){

            //set Templates
            $this->strBodyTemplate = $this->objForm->sw_mail_bodyTemplate;

            if($this->objForm->sw_mail_styleTemplate){
                $this->strStyleTemplate = $this->objForm->sw_mail_styleTemplate;
            }

            //reset contao mail transfer
            $this->objForm->sendViaEmail = '';

            //generate sw mail
            $this->generateMail();
        }

        return;
    }


    /**
     * Set an object property
     * @param string
     * @param mixed
     */
    protected function set($strKey, $varValue){
        $this->arrData[$strKey] = $varValue;
    }


    /** Check mail transfer and decide between cto or sw mailer
     * @return bool
     */
    protected function startSwMailer(){

        //sendViaMail and MailBody Template is set
        if($this->objForm->sendViaEmail == '1' and $this->objForm->sw_mail_bodyTemplate){

            return true;
        }
        else{
            return false;
        }
    }


    /** generate Mail from Object Data
     *
     */
    protected function generateMail(){

        //body template
        $mailTpl = new FrontendTemplate($this->strBodyTemplate);

        //stylesheet template
        $styleTpl = new FrontendTemplate($this->strStyleTemplate);

        //template vars
        $mailTpl->style = $styleTpl->parse();
        $mailTpl->data = $this->arrData['data'];
        $mailTpl->label = $this->arrData['label'];

        $body = $mailTpl->parse();

        $mail = new \Email();

        // Set the admin e-mail as "from" address
        $mail->from = $GLOBALS['TL_ADMIN_EMAIL'];
        $mail->fromName = $GLOBALS['TL_ADMIN_NAME'];

        $mail->__set('subject',$this->objForm->subject);
        $mail->__set('html',$body);

        $mail->sendTo($this->objForm->recipient);

    }

}