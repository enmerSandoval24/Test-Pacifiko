<?php require_once('../Test_Pacifiko/classes/manager/ControlApi.php')?>
<?php 
    $api = new ControlApi();
    $users = [];

    $users = $api->getAllUsers();

    if($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['idUser'])){
        $userId = $_GET['idUser'];
        if(!empty($userId)){
            $user = $api->getUserById($userId);
            if($user){
                $users = $user;
            } else{
                $users = [];
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Integración API - Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="text-center display-1">Lista de Usuarios</h1>
        <div class="container text-start">
            <form method="GET" action="">
                <input type="number" class="form-control form-control-sm" name="idUser" id="idUser" placeholder="id" style="max-width: 100px" >
                <br>
                <button type="submit" class="btn btn-primary">Buscar</button>
                <a  class="btn btn-info text-end" href="classes/manager/Register.php">Registrarse</a>
            </form>    
        </div>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Apellido</th>
                    <th scope="col">Email</th>
                </tr>
            </thead>
            <tbody id="userTableBody">
                <?php if(!empty($users)): ?> 
                    <?php foreach($users as $user) : ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo $user['firstName']; ?></td>
                            <td><?php echo $user['lastName']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                        </tr>
                    <?php endforeach;?>
                <?php else: ?>
                    <tr>
                        <td colspan="4"class="text-center">No se encuentran usuarios</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
