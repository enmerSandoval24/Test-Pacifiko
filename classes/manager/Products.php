<?php 
  require_once('../db/ProductOperation.php')
?>
<?php 
    session_start();

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = []; 
    }
    $api = new ProductOperations();
    $products = [];
    $products = $api->getProducts();
    $customers = [];
    $customers = $api->getCustomers();
    $editProduct = null;
    $cart = [];
    $total = 0;

    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add_to_cart'])) {
        $productId = $_POST['product_id'];
        $productName = $_POST['product_name'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];
        
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] += $quantity; 
        } else {
            $_SESSION['cart'][$productId] = [
                'product_name' => $productName,
                'price' => $price,
                'quantity' => $quantity,
            ];
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['cartOptions'])) {
        $customerId = $_POST['cartOptions'];
        $orderDate = date('Y-m-d H:i:s'); 
        $orderId = $api->createOrder($customerId, $orderDate); 

        foreach ($_SESSION['cart'] as $productId => $item) {
            $quantity = $item['quantity'];
            $subtotal = $item['price'] * $quantity;

            $api->addOrderItem($orderId, $productId, $quantity, $subtotal); // Agregar item al pedido
            $api->updateStock($productId, $quantity); 
        }

        $_SESSION['cart'] = [];
        echo "<script>alert('Compra realizada con éxito!');</script>"; 
    }


    if($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['nameProduct'])){
        $nameProduct = $_GET['nameProduct'];
        if(!empty($nameProduct)){
            $product = $api->getProductByName($nameProduct);
            if($product){
                $products = $product;
            } else{
                $products = [];
            }
        }
    }
?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="classes/manager/Register.php">Registrar Usuario</a>
                    </li>
                     <li class="nav-item">
                        <a class="nav-link" href="classes/manager/Products.php">Productos</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <h1 class="text-center display-1">Lista de Productos</h1>
        <div class="container text-start">
            <form method="GET" action="">
                <input type="text" class="form-control form-control-sm" name="nameProduct" id="nameProduct" placeholder="Nombre del producto" style="max-width: 500px" >
                <br>
                <button type="submit" class="btn btn-primary">Buscar</button>
            </form>    
        </div>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Precio</th>
                    <th scope="col">Stock</th>
                    <th scope="col">Carrito</th>
                </tr>
            </thead>
            <tbody id="userTableBody">
                <?php if(!empty($products)): ?> 
                    <?php foreach($products as $product) : ?>
                        <tr>
                            <td><?php echo $product['product_id']; ?></td>
                            <td><?php echo $product['product_name']; ?></td>
                            <td><?php echo $product['price']; ?></td>
                            <td><?php echo $product['stock_quantity']; ?></td>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                    <input type="hidden" name="product_name" value="<?php echo $product['product_name']; ?>">
                                    <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
                                    <input type="number" name="quantity" min="1" max="<?php echo $product['stock_quantity']; ?>" value="1" class="form-control form-control-sm" style="width: 80px;">
                                    <button type="submit" name="add_to_cart" class="btn btn-success btn-sm mt-2">Añadir al Carrito</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach;?>
                <?php else: ?>
                    <tr>
                        <td colspan="4"class="text-center">No se encuentran productos</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="container mt-5">
        <h2 class="text-center">Carrito</h2>
        <div class="container mb-3">
            <form method="POST" action="">
                <label for="cartOptions">Opciones del Carrito:</label>
                <select name="cartOptions" id="cartOptions" class="form-select" style="max-width: 300px;">
                    <?php foreach($customers as $customer): ?>
                        <option value="<?php echo $customer['customer_id']; ?>">
                            <?php echo htmlspecialchars($customer['first_name']. ' ' .$customer['last_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php foreach ($_SESSION['cart'] as $productId => $item): ?>
                    <input type="hidden" name="cart[<?php echo $productId; ?>][product_name]" value="<?php echo $item['product_name']; ?>">
                    <input type="hidden" name="cart[<?php echo $productId; ?>][price]" value="<?php echo $item['price']; ?>">
                    <input type="hidden" name="cart[<?php echo $productId; ?>][quantity]" value="<?php echo $item['quantity']; ?>">
                <?php endforeach; ?>
                <button type="submit" class="btn btn-primary mt-2">Comprar</button>
            </form>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Nombre</th>
                    <th scope="col">Precio</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total = 0.00;
                foreach ($_SESSION['cart'] as $item): 
                    $subtotal = $item['price'] * $item['quantity'];
                    $total += $subtotal;
                ?>
                    <tr>
                        <td><?php echo $item['product_name']; ?></td>
                        <td><?php echo number_format($item['price'], 2); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td><?php echo number_format($subtotal, 2); ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                    <td><strong><?php echo number_format($total, 2); ?></strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>