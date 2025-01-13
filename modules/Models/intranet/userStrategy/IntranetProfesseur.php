<?php

class IntranetProfesseur implements IntranetStrategy
{

    private Intranet $model;

    public function __construct(Intranet $model) {
        $this->model = $model;
    }
    public function getDashboard()
    {
        return "account/intranet/dashboard/professeur";
    }
}