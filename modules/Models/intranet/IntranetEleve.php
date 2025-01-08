<?php

class IntranetEleve implements IntranetStrategy
{

    public function getDashboard()
    {
        return 'account/intranet/dashboard/eleve';
    }
}