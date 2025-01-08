<?php

class IntranetProfesseur implements IntranetStrategy
{

    public function getDashboard()
    {
        return "account/intranet/dashboard/professeur";
    }
}