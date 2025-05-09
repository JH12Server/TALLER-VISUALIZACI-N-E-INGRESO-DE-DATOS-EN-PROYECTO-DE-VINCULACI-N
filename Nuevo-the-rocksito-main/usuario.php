<?php
// Asegurarse de que session_start() está al inicio del archivo, antes de cualquier salida HTML
session_start();

// Inicializar la sesión de usuarios si no existe
if (!isset($_SESSION['usuarios'])) {
    $_SESSION['usuarios'] = [];
}

// Variable para mensaje
$mensaje = '';
$tituloModal = 'Registro de Usuario';
$usuario_editar = null;

// Función para agregar un usuario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'crear') {
    $usuario = [
        'cedula' => $_POST['cedula'],
        'nombres' => $_POST['nombres'],
        'apellidos' => $_POST['apellidos'],
        'email' => $_POST['email'],
        'telefono' => $_POST['telefono'],
        'estado' => $_POST['estado'],
    ];
    $_SESSION['usuarios'][] = $usuario;
    
    // Redirigir después de crear para evitar reenvío del formulario
    header("Location: usuario.php?mensaje=creado");
    exit;
}

// Función para editar un usuario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'editar') {
    $id = $_POST['id'];
    if (isset($_SESSION['usuarios'][$id])) {
        $_SESSION['usuarios'][$id] = [
            'cedula' => $_POST['cedula'],
            'nombres' => $_POST['nombres'],
            'apellidos' => $_POST['apellidos'],
            'email' => $_POST['email'],
            'telefono' => $_POST['telefono'],
            'estado' => $_POST['estado'],
        ];
        
        // Redirigir después de editar para evitar reenvío del formulario
        header("Location: usuario.php?mensaje=editado");
        exit;
    }
}

// Función para eliminar un usuario
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    // Verificar que el índice existe
    if (isset($_SESSION['usuarios'][$id])) {
        // Eliminar el usuario
        unset($_SESSION['usuarios'][$id]);
        // Reindexar el array para evitar huecos en los índices
        $_SESSION['usuarios'] = array_values($_SESSION['usuarios']);
        
        // Redirigir después de eliminar
        header("Location: usuario.php?mensaje=eliminado");
        exit;
    }
}

// Verificar si estamos editando un usuario
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    if (isset($_SESSION['usuarios'][$id])) {
        $usuario_editar = $_SESSION['usuarios'][$id];
        $usuario_editar['id'] = $id;
        $tituloModal = 'Editar Usuario';
    }
}

// Verificar mensaje
if (isset($_GET['mensaje'])) {
    switch ($_GET['mensaje']) {
        case 'creado':
            $mensaje = 'Usuario creado correctamente';
            break;
        case 'editado':
            $mensaje = 'Usuario actualizado correctamente';
            break;
        case 'eliminado':
            $mensaje = 'Usuario eliminado correctamente';
            break;
    }
}

// Determinar si se debe mostrar el modal
$mostrarModal = isset($_GET['edit']) || isset($_GET['nuevo']);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>THE ROCK</title>
    <link rel="stylesheet" href="./css/styler.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Paginacion -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/v/bs5/dt-2.2.1/datatables.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
  
    </style>
</head>

<body>
    <!-- Topbar -->
    <div class="topbar">
        <div class="logo-container">
            <a href="index.html"><img src="./img/sin fondo 1.png" alt="Logo" class="logo"></a>
            <span class="logo-text">The rock gym center</span>
        </div>
        <div class="user-info">
            <span class="user-name">Anthony Erreyes</span>
            <img src="#" alt="Usuario" class="user-avatar">
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h4>OPCIONES</h4>
        </div>

        <!-- Menú principal -->
        <ul class="sidebar-menu">
            <li>
                <a href="#" class="menu-item">
                    <i class="fas fa-users"></i>
                    <span>Usuarios</span>
                    <i class="fas fa-chevron-right arrow"></i>
                </a>
                <ul class="submenu active">
                    <li><a href="./usuario.php">Usuarios</a></li>
                    <li><a href="./roles.html">Roles</a></li>
                    <li><a href="./permisos.html">Permisos</a></li>
                </ul>
            </li>
            <li>
                <a href="#" class="menu-item">
                    <i class="fas fa-id-card"></i>
                    <span>Membresía</span>
                    <i class="fas fa-chevron-right arrow"></i>
                </a>
                <ul class="submenu">
                    <li><a href="./membresias.html">Membresias</a></li>
                    <li><a href="./membre-usua.html">Membresias-Usuarios</a></li>
                </ul>
            </li>
            <li>
                <a href="#" class="menu-item">
                    <i class="fas fa-box"></i>
                    <span>Inventario</span>
                    <i class="fas fa-chevron-right arrow"></i>
                </a>
                <ul class="submenu">
                    <li><a href="./producto.html">Productos</a></li>
                    <li><a href="./categorias.html">Categorias</a></li>
                </ul>
            </li>
            <li>
                <a href="#" class="menu-item">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Ventas</span>
                    <i class="fas fa-chevron-right arrow"></i>
                </a>
                <ul class="submenu">
                    <li><a href="./ventas.html">Ventas</a></li>
                </ul>
            </li>
            <li>
                <a href="#" class="menu-item">
                    <i class="fas fa-money-bill"></i>
                    <span>Ingresos</span>
                    <i class="fas fa-chevron-right arrow"></i>
                </a>
                <ul class="submenu">
                    <li><a href="./ingresos.html">Ingresos</a></li>
                    <li><a href="./detalle-ingreso.html">Detalle de Ingresos</a></li>
                </ul>
            </li>
        </ul>
    </div>

    <!-- Contenido principal -->
    <div class="main-content">
        <div class="container-fluid">
            <h2 class="mb-4">LISTADO DE USUARIOS</h2>
            
            <?php if (!empty($mensaje)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $mensaje; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="usuario.php?nuevo=1" class="btn btn-primary">Nuevo Usuario</a>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>Cedula</th>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if (isset($_SESSION['usuarios']) && is_array($_SESSION['usuarios']) && count($_SESSION['usuarios']) > 0): 
                            foreach ($_SESSION['usuarios'] as $index => $usuario): 
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($usuario['cedula'] ?? '') ?></td>
                            <td><?= htmlspecialchars($usuario['nombres'] ?? '') ?></td>
                            <td><?= htmlspecialchars($usuario['apellidos'] ?? '') ?></td>
                            <td><?= htmlspecialchars($usuario['email'] ?? '') ?></td>
                            <td><?= htmlspecialchars($usuario['telefono'] ?? '') ?></td>
                            <td><?= htmlspecialchars($usuario['estado'] ?? '') ?></td>
                            <td>
                                <a href="usuario.php?edit=<?= $index ?>" class="btn btn-warning btn-sm">Editar</a>
                                <a href="usuario.php?delete=<?= $index ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">Eliminar</a>
                            </td>
                        </tr>
                        <?php 
                            endforeach;
                        else:
                        ?>
                        <tr>
                            <td colspan="7" class="text-center">No hay usuarios registrados</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Formulario Modal (mostrado con PHP) -->
    <?php if ($mostrarModal): ?>
    <div class="modal" style="display: block; background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><?= $tituloModal ?></h5>
                    <a href="usuario.php" class="btn-close btn-close-white" aria-label="Close"></a>
                </div>
                <form method="POST" action="usuario.php">
                    <div class="modal-body">
                        <input type="hidden" name="id" value="<?= isset($usuario_editar['id']) ? $usuario_editar['id'] : '' ?>">
                        <input type="hidden" name="action" value="<?= isset($usuario_editar) ? 'editar' : 'crear' ?>">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Cédula *</label>
                                <input type="text" class="form-control" name="cedula" value="<?= isset($usuario_editar['cedula']) ? htmlspecialchars($usuario_editar['cedula']) : '' ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nombres *</label>
                                <input type="text" class="form-control" name="nombres" value="<?= isset($usuario_editar['nombres']) ? htmlspecialchars($usuario_editar['nombres']) : '' ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Apellidos *</label>
                                <input type="text" class="form-control" name="apellidos" value="<?= isset($usuario_editar['apellidos']) ? htmlspecialchars($usuario_editar['apellidos']) : '' ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email *</label>
                                <input type="email" class="form-control" name="email" value="<?= isset($usuario_editar['email']) ? htmlspecialchars($usuario_editar['email']) : '' ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Teléfono *</label>
                                <input type="tel" class="form-control" name="telefono" value="<?= isset($usuario_editar['telefono']) ? htmlspecialchars($usuario_editar['telefono']) : '' ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Estado *</label>
                                <select class="form-control" name="estado" required>
                                    <option value="Activo" <?= (isset($usuario_editar['estado']) && $usuario_editar['estado'] == 'Activo') ? 'selected' : '' ?>>Activo</option>
                                    <option value="Inactivo" <?= (isset($usuario_editar['estado']) && $usuario_editar['estado'] == 'Inactivo') ? 'selected' : '' ?>>Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="usuario.php" class="btn btn-secondary">Cerrar</a>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
 
</body>
</html>