function BD2Fecha(fecha, separador) {
    separador = typeof separador !== 'undefined' ? separador : '-';
    var $f = fecha.split('-');
    return $f[2] + separador + $f[1] + separador + $f[0];
}
