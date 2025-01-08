<?php

class IntranetAdmin implements IntranetStrategy
{

    public function getDashboard()
    {
        return "/modules/Views/account/intranet/dashboard/admin.php";
    }
}