<?php

class IntranetStaff implements IntranetStrategy
{

    public function getDashboard()
    {
        return "/modules/Views/account/intranet/dashboard/staff.php";
    }
}