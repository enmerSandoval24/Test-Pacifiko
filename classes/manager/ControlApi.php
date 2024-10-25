<?php 
    class ControlApi{
        #Constante de la Url de la api que manejamos
        const URL = 'https://dummyjson.com/users';
        #Lista estatica de los usuarios que manejamos, esto por agrado ya que es una simulacion
        private static $userList = [];

        #Esto es una funcion en la que con la api obtenemos todos los usuarios de acuerdo al json
        public function getUsers(){
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, self::URL);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

            $response = curl_exec($ch);

            if(curl_errno($ch)){
                echo 'Error in extraction de information: ' . curl_error($ch);
                curl_close($ch);
                return [];
            }

            curl_close($ch);

            $data = json_decode($response, true);

            return isset($data['users']) ? $data['users'] : [];
        }

        #Le pasamos como parametro un id de acuerdo al index.php 
        public function getUserById($id){
            $urlById = self::URL .'/'. $id;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $urlById);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            $response = curl_exec($ch);
            if(curl_errno($ch)){
                echo 'Error in search user: '. curl_error($ch);
                curl_close($ch);
                return [];
            } 

            curl_close($ch);
            $data = json_decode($response, true);
            return isset($data) ? [$data] : [];
        }
        
        #Funcion para registrar al usuario esta viene del formulario Register.php
        public function registerUser($data){
            $genericData = $this->getDataGeneric();
            $data = array_merge($genericData, $data);
            $data['id'] = $this->returnNewId();
            self::$userList[] = $data;
                return [
                    'success' => true,
                    'message' => 'Usuario registrado correctamente su id es: ' . $data['id'],
                    'user' => $data
                ];
        }

        #Union de una lista de usuarios extraida de la api y tambien de la lista de usuarios estatica
        public function getAllUsers(){
            $users = $this->getUsers();
            return array_merge($users, self::$userList);
        }

        #Retorna un nuevo id esto con la union de las listas de usuarios
        private function returnNewId(){
            $newId = 0;
            foreach($this->getAllUsers() as $user){
                if($user['id'] > $newId){
                    $newId = $user['id'];
                }
            }
            return $newId + 1;
        }

        #Datos genericos para registrar a los usuarios.
        public function getDataGeneric() {
            $data = [
                'lastName' => "Sandoval",
                'maidenName' => "Smith",
                'age' => 22,
                'gender' => "male",
                'email' => "sandoval@gmail.com",
                'phone' => "+502 3423 9458",
                'username' => "user",
                'password' => "password",
                'birthDate' => "2001-11-24",
                'image' => "https://dummyjson.com/icon/emilys/128",
                'bloodGroup' => "O-",
                'height' => 192.24,
                'weight' => 43.23,
                'eyeColor' => "Brown",
                'hair' => [
                    'color' => "Brown",
                    'type' => "Curly"
                ],
                'ip' => "42.48.100.32",
                'address' => [
                    'address' => "626 Main Street",
                    'city' => "Phoenix",
                    'state' => "Mississippi",
                    'stateCode' => "MS",
                    'postalCode' => "29112",
                    'coordinates' => [
                        'lat' => -77.16213,
                        'lng' => -92.084824
                    ],
                    'country' => "United States"
                ],
                'macAddress' => "47:fa:41:18:ec:eb",
                'university' => "University of Wisconsin--Madison",
                'bank' => [
                    'cardExpire' => "03/26",
                    'cardNumber' => "9289760655481815",
                    'cardType' => "Elo",
                    'currency' => "CNY",
                    'iban' => "YPUXISOBI7TTHPK2BR3HAIXL"
                ],
                'company' => [
                    'department' => "Engineering",
                    'name' => "Dooley, Kozey and Cronin",
                    'title' => "Sales Manager",
                    'address' => [
                        'address' => "263 Tenth Street",
                        'city' => "San Francisco",
                        'state' => "Wisconsin",
                        'stateCode' => "WI",
                        'postalCode' => "37657",
                        'coordinates' => [
                            'lat' => 71.814525,
                            'lng' => -161.150263
                        ],
                        'country' => "United States"
                    ]
                ],
                'ein' => "977-175",
                'ssn' => "900-590-289",
                'userAgent' => "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.93 Safari/537.36",
                'crypto' => [
                    'coin' => "Bitcoin",
                    'wallet' => "0xb9fc2fe63b2a6c003f1c324c3bfa53259162181a",
                    'network' => "Ethereum (ERC20)"
                ],
                'role' => "admin"
            ];
            
            return $data; 
        }
        
    }
?>