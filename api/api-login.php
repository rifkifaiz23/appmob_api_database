        <?php
        $server = "localhost";
        $user = "root";
        $pass = "";
        $database = "pweb_proj";
        $koneksi = mysqli_connect($server, $user, $pass, $database);

        if (mysqli_connect_errno()) {
            die("Koneksi gagal: " . mysqli_connect_error());
        }

        $email = $_POST ["post_email"];
        $password = $_POST ["post_password"];

        $query = "SELECT * FROM user WHERE email = '$email' AND password = '$password'";
        $obj_query = mysqli_query($koneksi, $query);
        $data = mysqli_fetch_assoc($obj_query);

        if ($data) {
            echo json_encode(
                array(
                    'response' => true,
                    'payload' => array(
                        "email" => $data['email'],
                        "password" => $data['password']
                    )
                )
            );
        } else {
            echo json_encode(
                array(
                    'response' => false,
                    'payload' => null
                )
            );
        }

        header('Content-Type: application/json');
        ?>