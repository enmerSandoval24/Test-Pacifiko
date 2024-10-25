<?php require_once('ControlApi.php')?>
<?php 
    $api = new ControlApi();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $firstName = $_POST['firstName'];
    
        $data = [
            'firstName' => $firstName,
        ];
    
        $result = $api->registerUser($data);
    
        if ($result['success']) {
            echo '<div class="alert alert-success">' . $result['message'] . '</div>';
        } else {
            echo '<div class="alert alert-danger">Error al registrar el usuario.</div>';
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Registro de Usuario</h1>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Formulario de Registro</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action=""> 
                            <div class="mb-3">
                                <label for="firstName" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="firstName" name="firstName" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Registrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center">
        <a class="btn btn-info" href="../../index.php">Inicio</a>
    </div>
</body>
</html>
