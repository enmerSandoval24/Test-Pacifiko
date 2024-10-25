<?php 
    class Alghoritms{

        #Esta es una funcion para ver los productos de acuerdo el nombre se le pasa una lista
        public function getProduct($products, $nameProduct){
            $low = 0;
            $high = count($products) -1;
            while($low < $high){
                $mid = floor(($low + $high) /2);

                if($products[$mid]['name'] === $nameProduct){
                    return $products[$mid];
                } elseif($products[$mid]['name'] < $nameProduct){
                    $low = $mid + 1;
                } else {
                    $high = $mid - 1;
                }
            }
            return null;
        }

        #Esta es una funcion en la que se le ve el total del carrito de acuerdo de cada producto
        #Simulando lo que es una query
        public function getCartTotal($cart){
            #Result variable solo para especificar la funcion
            $result = [];
            $total = 0.00;
            foreach($cart as $product){
                #Realizamos la query

                #$query = $db->prepare("SELECT price FROM product WHERE id = :productId");
                #$query->bindParam(':productId', $product['product_id'], PDO::PARAM_INT);
                #$query->execute();
                #$result = $query->fetch(PDO::FETCH_ASSOC);
                
                $total += $result['price'] * $product['quantity'];
            }
        }

        public function getCartPriceDiscount($cart){
            #Variable que no es utilizable pero para agrado de vista
            $result = [];
            $total = 0.00;
            foreach($cart as $product){
                #Realizamos la query
                #$query = $db->prepare("SELECT price FROM product WHERE id = :productId");
                #$query->bindParam(':productId', $product['product_id'], PDO::PARAM_INT);
                #$query->execute();
                #$result = $query->fetch(PDO::FETCH_ASSOC);
                $subTotal = $product['price'] * $product['quantity'];
                $total += $subTotal * $product['discount'];
            }
        }
        
        #Utilizamos la funcion arsorts() para poder ordernar las ordenes de venta
        public function getProductSell($orders, $topN) {
            $productSales = [];
        
            foreach ($orders as $order) {
                $productId = $order['product_id'];
                $quantity = $order['quantity'];
        
                if (!isset($productSales[$productId])) {
                    $productSales[$productId] = 0;
                }
                $productSales[$productId] += $quantity;
            }
        
            arsort($productSales);
        
            $topProducts = array_slice($productSales, 0, $topN, true);
        
            return $topProducts;
        }
    }
?>