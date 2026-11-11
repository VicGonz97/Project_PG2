<?php
date_default_timezone_set('America/Guatemala');

// Convertir la lista de texto en una lista que PHP pueda usar
$dias  = explode(',', t('dias'));
$meses = explode(',', t('meses'));

// date('w') da el día de la semana (0 = domingo), date('n') el mes (1 a 12)
$dia = $dias[date('w')];
$mes = $meses[date('n') - 1];

if ($idioma == 'en') {
    echo "$dia, $mes " . date('j, Y');
} else {
    echo "$dia " . date('j') . " de $mes del " . date('Y');
}