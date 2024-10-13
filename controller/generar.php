<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Cargar la plantilla del carnet
    $plantilla = imagecreatefrompng('plantilla_carnet.png'); // Ruta a la plantilla
    
    // Verificar si la plantilla se cargó correctamente
    if (!$plantilla) {
        die('Error: No se pudo cargar la plantilla.');
    }

    // Definir colores
    $negro = imagecolorallocate($plantilla, 0, 0, 0);

    // Definir la fuente (asegúrate de que la ruta es correcta y que el archivo ARLRDBD.ttf existe)
    $fuente = __DIR__ . '/fuentes/ARLRDBD.ttf'; // Ruta a la fuente TTF

    // Verificar si el archivo de fuente existe
    if (!file_exists($fuente)) {
        die('Error: La fuente Arial Rounded MT Bold no existe en la ruta especificada.');
    }

    $fuente2 = __DIR__ . '/fuentes/ARIAL.ttf'; // Ruta a la fuente TTF

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
    $foto_alto = 200;  // Ajustar a tus dimensiones
    $foto_redimensionada = imagecreatetruecolor($foto_ancho, $foto_alto);
    imagecopyresampled($foto_redimensionada, $foto, 0, 0, 0, 0, $foto_ancho, $foto_alto, imagesx($foto), imagesy($foto));

    // Insertar la foto en la plantilla
    imagecopy($plantilla, $foto_redimensionada, 90, 158, 0, 0, $foto_ancho, $foto_alto); // Coordenadas a ajustar

    // Escribir los textos en la plantilla
    imagettftext($plantilla, 14, 0, 102, 378, $negro, $fuente, $apellidos); // Apellidos
    imagettftext($plantilla, 14, 0, 102, 398, $negro, $fuente, $nombres); // Nombres
    imagettftext($plantilla, 11, 0, 120, 418,$negro, $fuente2, $tipodocumento);
    imagettftext($plantilla, 11, 0, 150, 418, $negro, $fuente2, $documento); // Documento
    imagettftext($plantilla, 11, 0, 160, 438, $negro, $fuente2, 'RH: ' . $rh); // RH
    imagettftext($plantilla, 12, 0, 60, 460, $negro, $fuente, $curso); // Curso

    


    // Guardar la imagen generada en formato PNG
    $output_file = 'carnet_generado.png';
    imagepng($plantilla, $output_file);

    // Limpiar memoria
    imagedestroy($plantilla);
    imagedestroy($foto_redimensionada);
    imagedestroy($foto);

    // Mostrar la imagen generada
    echo "<h1>Carnet Generado</h1>";
    echo "<img src='$output_file' alt='Carnet'>";
    echo "</br></br>";
    echo "<a href = '$output_file' download='carnet.jpg' >Descargar archivo</a>";
    echo " <a href='javascript: history.go(-1)'>Volver atrás</a>";
}
?>
