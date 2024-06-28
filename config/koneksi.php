    <?php
        $host = "localhost";
        $user = "root";
        $pass = "";
        $database = "pweb_proj";

        $mysqli = mysqli_connect($host, $user, $pass, $database);
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        } else {
            echo "Connected to MySQL";
        }
    ?>  