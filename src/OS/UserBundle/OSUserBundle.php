<?php

namespace OS\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class OSUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
