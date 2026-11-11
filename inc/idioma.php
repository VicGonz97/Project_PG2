<?php
// 1. Si la persona eligió un idioma (?lang=es o ?lang=en), guardarlo en la sesión
if (isset($_GET['lang']) && in_array($_GET['lang'], ['es', 'en'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

// 2. Usar el idioma guardado; si no hay ninguno, español
$idioma = $_SESSION['lang'] ?? 'es';

// 3. Cargar los textos de ese idioma
$textos = include __DIR__ . '/../lang/' . $idioma . '.php';

// 4. Función para pedir un texto por su clave
function t($clave) {
    global $textos;
    return $textos[$clave] ?? $clave;
}