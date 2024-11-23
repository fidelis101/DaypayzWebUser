<?php
class ActivationFees
{
    private $regFees = ["daypayzite"=>2500.00,"builder"=>5500.00,"manager"=>10500.00,"senior_manager"=>20000.00,"team_leader"=>50000.00];

    static function packageFees($package)
    {
        return self::$regFees[$package];
    }
}

    