<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Cargar la plantilla del carnet
    $plantilla = imagecreatefrompng('../template/plantilla_carnet.png'); // Ruta a la plantilla

    // Verificar si la plantilla se cargó correctamente
    if (!$plantilla) {
        die('Error: No se pudo cargar la plantilla.');
    }

    // Definir colores
    $negro = imagecolorallocate($plantilla, 0, 0, 0);

    // Definir la fuente (asegúrate de que la ruta es correcta y que el archivo ARLRDBD.ttf existe)
    $fuente = __DIR__ . '../dafonts/ARLRDBD.ttf'; // Ruta a la fuente TTF

    // Verificar si el archivo de fuente existe
    if (!file_exists($fuente)) {
        die('Error: La fuente ARLRDBD no existe en la ruta especificada.');
    }

    $fuente2 = __DIR__ . '../dafonts/ARIAL.ttf'; // Ruta a la fuente TTF

    // Verificar si el archivo de fuente existe
    if (!file_exists($fuente2)) {
        die('Error: La fuente ARIAL no existe en la ruta especificada.');
    }

    // Obtener los datos del formulario
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $tipodocumento = $_POST['tipodocumento'];
    $documento = $_POST['documento'];
    $rh = $_POST['rh'];
    $curso = $_POST['curso'];

    // Cargar la foto del estudiante
    $foto_temp = $_FILES['foto']['tmp_name'];
    $foto = imagecreatefromjpeg($foto_temp);

    // Verificar si la foto se cargó correctamente
    if (!$foto) {
        die('Error: No se pudo cargar la foto.');
    }

    // Redimensionar la foto del estudiante para que se ajuste a la plantilla
    $foto_ancho = 180; // Ajustar a tus dimensiones
    $foto_alto = 210;  // Ajustar a tus dimensiones
    $foto_redimensionada = imagecreatetruecolor($foto_ancho, $foto_alto);
    imagecopyresampled($foto_redimensionada, $foto, 0, 0, 0, 0, $foto_ancho, $foto_alto, imagesx($foto), imagesy($foto));

    // Insertar la foto en la plantilla
    imagecopy($plantilla, $foto_redimensionada, 90, 148, 0, 0, $foto_ancho, $foto_alto); // Coordenadas a ajustar

    // Escribir los textos en la plantilla
    imagettftext($plantilla, 14, 0, 102, 378, $negro, $fuente, $apellidos); // Apellidos
    imagettftext($plantilla, 14, 0, 102, 398, $negro, $fuente, $nombres); // Nombres
    imagettftext($plantilla, 11, 0, 120, 418, $negro, $fuente2, $tipodocumento);
    imagettftext($plantilla, 11, 0, 150, 418, $negro, $fuente2, $documento); // Documento
    imagettftext($plantilla, 11, 0, 160, 438, $negro, $fuente2, 'RH: ' . $rh); // RH

    // Ajustar automáticamente el texto del curso para que tenga saltos de línea si es muy largo
    $curso_envuelto = wordwrap($curso, 40, "\n", false); // Divide el texto en líneas de hasta 40 caracteres

    // Definir la posición inicial de Y para el texto del curso
    $pos_x = 60; // Posición en X
    $pos_y = 460; // Posición inicial en Y

    // Escribir cada línea del texto del curso
    foreach (explode("\n", $curso_envuelto) as $linea) {
        // Elimina cualquier carácter no deseado, en especial los saltos de línea o espacios que pueden generar caracteres extraños
        $linea_limpia = preg_replace('/[\x00-\x1F\x7F]/u', '', $linea); // Remueve caracteres de control y otros indeseables
        imagettftext($plantilla, 11, 0, $pos_x, $pos_y, $negro, $fuente, $linea_limpia);
        $pos_y += 20; // Incrementar la posición Y para la siguiente línea
    }

    // Guardar la imagen generada en formato PNG
    $output_file = 'carnet_generado.png';
    //imagepng($plantilla, $output_file);

    // Limpiar memoria
    imagedestroy($plantilla);
    imagedestroy($foto_redimensionada);
    imagedestroy($foto);

    // Mostrar la imagen generada
    echo "<h1>Carnet Generado</h1>";
    echo "<img src='$output_file' alt='Carnet'>";
    echo "</br></br>";
    echo "<button> <a href = '$output_file' download='carnet.jpg' >Descargar archivo</a></button>";
    echo "<button> <a href = 'javascript: history.go(-1)'>Volver atrás</a></button>";
}
?>
