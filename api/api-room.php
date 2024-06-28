    <?php
    $host = "localhost";
    $user = "root";
    $pass = "";
    $database = "pweb_proj";

    $conn = mysqli_connect($host, $user, $pass, $database);
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    } else {
        echo "Connected to MySQL";
    }

    // Query to get room data
    $queryRooms = "SELECT * FROM room";
    $result = $conn->query($queryRooms);

    if (!$result) {
        echo json_encode(["error" => "Error fetching rooms: " . $conn->error]);
        http_response_code(500);
        exit();
    }

    // Fetch all rooms
    $rooms = [];
    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }

    // Return the rooms data as JSON
    echo json_encode(["rooms" => $rooms]);

    // Close the connection
    $conn->close();
    ?>  