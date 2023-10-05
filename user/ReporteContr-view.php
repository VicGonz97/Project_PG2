<?php
require_once './lib/config.php'; // Incluye tu archivo de conexión a la base de datos


$num_user = Mysql::consulta("SELECT * FROM contribuyente");
$num_total_user = mysqli_num_rows($num_user);
?>
<div class="container">
    <div class="row">
        <div class="col-md-12 text-center">
            <ul class="nav nav-pills nav-justified">
                <li><a><i class="fa fa-users"></i>&nbsp;&nbsp;Miembros de la Comunidad&nbsp;&nbsp;<span class="badge"><?php echo $num_total_user; ?></span></a></li>
            </ul>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <?php
                $mysqli = mysqli_connect(SERVER, USER, PASS, BD);
                mysqli_set_charset($mysqli, "utf8");

                $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
                $regpagina = 15;
                $inicio = ($pagina > 1) ? (($pagina * $regpagina) - $regpagina) : 0;

                $selusers = mysqli_query($mysqli, "SELECT SQL_CALC_FOUND_ROWS * FROM contribuyente LIMIT $inicio, $regpagina");

                $totalregistros = mysqli_query($mysqli, "SELECT FOUND_ROWS()");
                $totalregistros = mysqli_fetch_array($totalregistros, MYSQLI_ASSOC);

                $numeropaginas = ceil($totalregistros["FOUND_ROWS()"] / $regpagina);
                if (mysqli_num_rows($selusers) > 0) :
                ?>
                    <table class="table table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Nombre</th>
                                <th class="text-center">Apellidos</th>
                                <th class="text-center">Fecha de nacimiento</th>
                                <th class="text-center">Edad</th>
                                <th class="text-center">Telefono</th>
                                <th class="text-center">DPI</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $ct = $inicio + 1;
                            while ($row = mysqli_fetch_array($selusers, MYSQLI_ASSOC)) :
                            ?>
                                <tr>
                                    <td class="text-center"><?php echo $ct; ?></td>
                                    <td class="text-center"><?php echo $row['nombre']; ?></td>
                                    <td class="text-center"><?php echo $row['apellido']; ?></td>
                                    <td class="text-center"><?php echo $row['fecha_nacimiento']; ?></td>
                                    <td class="<?php if ($row['edad'] >= 55) echo 'text-danger bg-danger text-white'; ?> text-center"><?php echo $row['edad']; ?></td>
                                    <td class="text-center"><?php echo $row['telefono']; ?></td>
                                    <td class="text-center"><?php echo $row['dpi']; ?></td>

                                </tr>
                            <?php

                                $ct++;
                            endwhile;
                            ?>
                        </tbody>
                    </table>
                    <a href="./index.php?view=AddReporte" class="btn btn-success">Regresar</a>
                    <a href="./index.php?view=AddReporte" class="btn btn-danger">Generar PDF</a>
                <?php else : ?>
                    <h2 class="text-center">No hay registros</h2>
                <?php endif; ?>
            </div>

            <!-- Aquí colocas el código de paginación -->
            <?php if ($numeropaginas >= 1) : ?>
                <nav aria-label="Page navigation" class="text-center">
                    <ul class="pagination">
                        <?php if ($pagina == 1) : ?>
                            <li class="disabled">
                                <a aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        <?php else : ?>
                            <li>
                                <a href="./index.php?view=ReporteContr&pagina=<?php echo $pagina - 1; ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php
                        for ($i = 1; $i <= $numeropaginas; $i++) {
                            if ($pagina == $i) {
                                echo '<li class="active"><a href="./index.php?view=ReporteContr&pagina=' . $i . '">' . $i . '</a></li>';
                            } else {
                                echo '<li><a href="./index.php?view=ReporteContr&pagina=' . $i . '">' . $i . '</a></li>';
                            }
                        }
                        ?>

                        <?php if ($pagina == $numeropaginas) : ?>
                            <li class="disabled">
                                <a aria-label="Previous">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        <?php else : ?>
                            <li>
                                <a href="./index.php?view=ReporteContr&pagina=<?php echo $pagina + 1; ?>" aria-label="Previous">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>


<br><br><br><br>