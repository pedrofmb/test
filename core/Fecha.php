<?php

class Fecha {

    public static function Date2BD($fecha) {
        if (strpos($fecha, '/') !== false) {
            list($d, $m, $a) = explode("/", $fecha);
        }
        if (strpos($fecha, '-') !== false) {
            list($d, $m, $a) = explode("-", $fecha);
        }
        return $a . "-" . $m . "-" . $d;
    }

    public static function BD2date($fecha) {
        list($a, $m, $d) = explode("-", $fecha);
        return $d . "-" . $m . "-" . $a;
    }

}
