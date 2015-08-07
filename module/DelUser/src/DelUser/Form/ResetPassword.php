<?php
/**
 * User: delboy1978uk
 * Date: 07/08/15
 * Time: 05:37
 */

namespace DelUser\Form;

use Zend\Form\Form;
use ZfcBase\Form\ProvidesEventsForm;
use DelUser\Form\ResetPasswordFilter;

class ResetPassword extends ProvidesEventsForm
{
    public function __construct($name)
    {
        parent::__construct($name);

        $this->setAttribute('class','form-inline');

        $this->add(array(
            'name' => 'newCredential',
            'options' => array(
                'label' => 'New Password',
            ),
            'attributes' => array(
                'type' => 'password',
                'class' => 'form-control',
            ),
        ));

        $this->add(array(
            'name' => 'newCredentialVerify',
            'options' => array(
                'label' => 'Verify New Password',
            ),
            'attributes' => array(
                'type' => 'password',
                'class' => 'form-control',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'value' => 'Submit',
                'type'  => 'submit',
                'class' => 'btn btn-primary'
            ),
        ));

        $this->setInputFilter(new ResetPasswordFilter());

        $this->getEventManager()->trigger('init', $this);
    }
}