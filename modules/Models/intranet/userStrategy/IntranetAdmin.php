<?php

class IntranetAdmin implements IntranetStrategy
{

    private Intranet $model;

    public function __construct(Intranet $model) {
        $this->model = $model;
    }
    public function getDashboardData() : array
    {
        return "/modules/Views/account/intranet/dashboard/admin.php";
    }
}