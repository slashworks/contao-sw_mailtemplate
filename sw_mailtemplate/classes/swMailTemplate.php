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


        $mail = new \Email();

        // Set the admin e-mail as "from" address
        $mail->from = $GLOBALS['TL_ADMIN_EMAIL'];
        $mail->fromName = $GLOBALS['TL_ADMIN_NAME'];


        // check format
        if($this->objForm->format != 'email'){

            $mail->__set('subject',$this->objForm->subject);
            $message = $this->compileTemplateData();

        }

        else{

            if(strlen($this->arrData['data']['subject'])){
                $mail->__set('subject',$this->arrData['data']['subject']);
            }
            else{
                $mail->__set('subject',$this->objForm->subject);
            }

            if(strlen($this->arrData['data']['cc'])){
                $mail->sendCc($this->arrData['data']['cc']);
            }

            if(strlen($this->arrData['data']['bcc'])){
                $mail->sendBcc($this->arrData['data']['bcc']);
            }

            $message = $this->arrData['data']['message'];

        }

        // check Mail Type
        ($this->objForm->sw_mail_type) ? $type = 'html' : $type = 'text';


        $mail->__set($type,$message);
        $mail->sendTo($this->objForm->recipient);

    }



    protected function compileTemplateData(){


        //body template
        $mailTpl = new FrontendTemplate($this->strBodyTemplate);

        //if Type = HTML add style Feature
        if($this->objForm->sw_mail_styleTemplate and $this->objForm->sw_mail_type){
            $styleTpl = new FrontendTemplate($this->strStyleTemplate);
            $mailTpl->style = $styleTpl->parse();
        }

        //get more field infos
        $fieldsObj = \FormFieldModel::findPublishedByPid($this->objForm->id);

        foreach($fieldsObj->fetchAll() as $k=>$v){

            if($v['name']){
                $fields[$v['name']] = array(
                    'type'  => $v['type'],
                    'label' => $v['label'],
                    'class' => $v['class'],
                    'value'  => $this->arrData['data'][$v['name']],
                );
            }

        }

        $mailTpl->data = $fields;
        $message = $mailTpl->parse();

        return $message;
    }


}