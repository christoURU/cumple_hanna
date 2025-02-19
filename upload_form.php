<?php
if(!isset($_SESSION)) {
    session_start(); // Asegurar el inicio de sesión
}

// Función para mostrar alertas
function mostrarAlerta() {
    if (!empty($_SESSION['tipo_mensaje']) && !empty($_SESSION['mensaje_exito'])) {
        $tipoMensaje = $_SESSION['tipo_mensaje'];
        $mensaje = $_SESSION['mensaje_exito'];

        echo '<div class="alert alert-' . ($tipoMensaje === 'error' ? 'danger' : 'success') . '" role="alert">';
        echo htmlspecialchars($mensaje);
        echo '<button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>';
        echo '</div>';

        unset($_SESSION['mensaje_exito']);
        unset($_SESSION['tipo_mensaje']);
    }
}

// Función para crear la galería de imágenes
function createGridGallery() {

    $directorio = './uploads';
    $archivos = glob($directorio . '/*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);
    
    if (!empty($archivos)) {
        echo '<div class="container text-center">';
        echo '<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">';

        foreach ($archivos as $archivo) {
            $nombre = basename($archivo);
            echo '<div class="col" data-aos="zoom-in" data-aos-duration="800">';
            echo '  <div class="card p-2 shadow-sm ">';
            echo '      <img class="card-img lazyload img-thumbnail" src="' . htmlspecialchars($archivo) . '" 
                            alt="' . htmlspecialchars($nombre) . '" data-bs-toggle="modal" data-bs-target="#modalImagen" data-image-src="' . htmlspecialchars($archivo) . '" style="cursor: pointer;">';
            echo '  </div>';
            if(isset($_SESSION['user']) && $_SESSION['user'] == 'root'){
                echo '  <a href="delete.php?file=' . urlencode($nombre) . '" class="btn btn-danger mt-2">Eliminar</a>';
            }
            echo '</div>';
        }
        echo '</div>';
        echo '</div>';

        // Modal fuera del bucle foreach
        echo '<div class="modal fade" id="modalImagen" tabindex="-1" aria-labelledby="modalImagenLabel" aria-hidden="true">';
        echo '  <div class="modal-dialog modal-xl">'; // Ajusta el tamaño del modal (modal-xl, modal-lg, etc.)
        echo '    <div class="modal-content">';
        echo '      <div class="modal-header">';
        echo '        <h1 class="modal-title fs-5" id="modalImagenLabel">Imagen</h1>';
        echo '        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
        echo '      </div>';
        echo '      <div class="modal-body">';
        echo '        <img src="" id="imagenModal" class="img-fluid" alt="Imagen ampliada">'; // ID para la imagen en el modal
        echo '      </div>';
        echo '    </div>';
        echo '  </div>';
        echo '</div>';

        // Script para abrir el modal y mostrar la imagen
        echo '<script>';
        echo '  const imagenes = document.querySelectorAll(\'img[data-bs-toggle="modal"]\');';
        echo '  const imagenModal = document.getElementById(\'imagenModal\');';

        echo '  imagenes.forEach(imagen => {';
        echo '    imagen.addEventListener(\'click\', () => {';
        echo '      const imgSrc = imagen.getAttribute(\'data-image-src\');';
        echo '      imagenModal.src = imgSrc;';
        echo '    });';
        echo '  });';
        echo '</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<?php include 'header.html'; ?>

<head>
    <meta charset="UTF-8">
    <title>Subir Archivos</title>
    <link rel="stylesheet" href="./assets/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
    /* Estilos personalizados para el modal */
    .modal-dialog {
        max-width: 80%;
        /* Ancho máximo del modal: 80% */
        margin: 1rem auto;
        /* Centrar vertical y horizontalmente */
    }

    .modal-content {
        max-height: 80vh;
        /* Alto máximo del contenido del modal: 80% de la altura de la pantalla */
    }

    .modal-body img {
        max-width: 100%;
        /* Ancho máximo de la imagen dentro del modal */
        height: auto;
        /* Alto automático para mantener la proporción */
        display: block;
        /* Evitar espacios en blanco alrededor de la imagen */
        margin: 0 auto;
        /* Centrar la imagen horizontalmente */
    }
    </style>
</head>

<body>
    <div class="container-flex overflow-hidden" style="height: 80vh;">
         <img src="./assets/img/hanna.png" class="img-fluid" alt="Hanna" data-aos="fade-in" data-aos-duration="1000" style="z-index: 1; position: absolute; top: 0; left: 0; height: 80vh;">
        <img src="./assets/img/banner.jpg" class="img-fluid" alt="Hanna" data-aos="fade-in" data-aos-duration="1000">
    </div>
    <div class="container">

        <div class="btn-group btn-group-lg d-flex flex-row justify-content-center align-items-center" role="group"
            aria-label="Large button group">
            <button type="button" class="btn btn-outline-primary"><a href="#subir archivos">Subir Fotos</a></button>
            <button type="button" class="btn btn-outline-primary"><a href="#galeria">Ver Fotos</a></button>
            <button type="button" class="btn btn-outline-primary"><a href="#menu">Menu</a></button>
        </div>
        <button class="btn btn-primary float-end mb-3" onclick="window.location.href='acces.php'">
            <i class="bi bi-key-fill"></i> Acceso
        </button>
        <?php if (isset($_SESSION['user']) && $_SESSION['user'] == 'root'): ?>
        <a href="logout.php" class="btn btn-danger float-end mb-3 me-3">
            <i class="bi bi-box-arrow-right"></i> Salir
        </a>
        <?php endif; ?>
        <?php mostrarAlerta(); ?>
        <br>

        <div id="subir archivos" class="mb-4">
            <h1 class="text-center my-4">Subir Archivos de Imagen y Video</h1>
            <p>Selecciona uno o varios archivos de imagen o video para subirlos al servidor.</p>
            <form action="./process_upload.php" method="post" enctype="multipart/form-data">
                <label for="archivo" class="form-label">Selecciona los archivos a subir:</label>
                <input class="form-control" type="file" name="archivo[]" id="archivo" multiple accept="image/*,video/*">
                <br>
                <input class="btn btn-primary" type="submit" value="Subir Archivos">
            </form>
        </div>
        <div id="mensaje">
            <?php
        if (isset($_GET['mensaje']) && isset($_GET['tipo_mensaje'])) {
            echo '<p class="text-' . ($_GET['tipo_mensaje'] == 'error' ? 'danger' : 'success') . '">'
                . htmlspecialchars($_GET['mensaje']) . '</p>';
        }
        ?>
        </div>
        <hr>
        <div id="menu" class="container w-50">
            <h2>Menu</h2>
            <p>Menú del evento</p>
            <p>El menú para el evento incluye:</p>
            <ul>
                <li>
                    <strong>Entradas</strong>
                    <ul>
                        <li>Tablas de fiambres y quesos</li>
                        <li>Sandwiches surtidos</li>
                    </ul>
                <li><strong>Bocaditos calientes</strong></li>
                <ul>
                    <li>Empanaditas de copetín</li>
                    <li>Arrolladitos primavera</li>
                    <li>Ciruelas con panceta</li>
                    <li>Villarois de queso</li>
                </ul>
                </li>
                </li>
                <br>
                <li>
                    <strong>Plato principal</strong>
                    <ul>
                        <li>Vaquillona con cuero a las brasas</li>
                        <li>Mesa buffet con variedad de ensaladas</li>
                    </ul>
                </li>
                <br>
                <li>
                    <strong>Sección Jóvenes</strong>
                    <ul>
                        <li>Nachos con salsa de guacamole</li>
                        <li>Sandwiches de jamón y queso</li>
                        <li>Mini medialunas rellenas</li>
                        <li>Panchos con aderezos y papitas pays</li>
                        <li>Mini pizzetas con mozzarella</li>
                        <li>Nuggets de pollo</li>
                    </ul>
                </li>
                <br>
                <li>
                    <strong>Mesa de postres</strong>
                    <ul>
                        <li>10 postres a elección</li>
                    </ul>
                </li>
                <br>
            </ul>
        </div>
        <hr>
        <h1 id="galeria" class="text-center my-4">Galería de Imágenes</h1>
        <div class="container text-center">
            <?php createGridGallery(); ?>
        </div>
        <div class="modal fade" id="modalImagen" tabindex="-1" aria-labelledby="modalImagenLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalImagenLabel">Imagen</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <img src="" alt="Imagen en modal" id="imagenModal">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    // Inicializar LazyLoad
    document.addEventListener("DOMContentLoaded", function() {
        if (window.LazyLoad) {
            new LazyLoad({
                elements_selector: ".lazyload"
            });
        }
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/lazyload@2.0.0/lazyload.min.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
    AOS.init();
    </script>

</body>

</html>