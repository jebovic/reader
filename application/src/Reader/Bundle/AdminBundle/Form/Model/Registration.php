<?php

namespace Reader\Bundle\AdminBundle\Form\Model;

use Reader\Bundle\UserBundle\Document\User;

class Registration
{
    protected $user;

    protected $termsAccepted;

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getTermsAccepted()
    {
        return $this->termsAccepted;
    }

    public function setTermsAccepted($termsAccepted)
    {
        $this->termsAccepted = (boolean)$termsAccepted;
    }
}