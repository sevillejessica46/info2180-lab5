<?php

$host = 'localhost';
$username = 'lab5_user';
$password = 'password123';
$dbname = 'world';

try {

    // Connect to the worlddatabase
    $conn = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get the country name from theURL
    $country = $_GET['country'] ?? '';

    // Get the lookup type
    $lookup = $_GET['lookup'] ?? 'countries';

    // Add wildcards for partial country searches
    $search = '%' . $country . '%';


    // Exercise 5: Cities  Lookup
    if ($lookup === 'cities') {

        $sql = "
            SELECT cities.name, cities.district, cities.population
            FROM cities
            JOIN countries
            ON cities.country_code = countries.code
            WHERE countries.name LIKE :country
            ORDER BY cities.name
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute(['country' => $search]);

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "<h2>Cities</h2>";

        if (count($results) > 0) {

            echo "<table>";
            echo "<tr>
                    <th>Name</th>
                    <th>District</th>
                    <th>Population</th>
                  </tr>";

            foreach ($results as $row) {

                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['district']) . "</td>";
                echo "<td>" . htmlspecialchars($row['population']) . "</td>";
                echo "</tr>";

            }

            echo "</table>";

        } else {

            echo "<p>No cities found for that country.</p>";

        }


    // Exercise 3 & 4: Look up countries
    } else {

        $sql = "
            SELECT name, continent, independence_year, head_of_state
            FROM countries
            WHERE name LIKE :country
            ORDER BY name
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute(['country' => $search]);

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "<h2>Countries</h2>";

        if (count($results) > 0) {

            echo "<table>";
            echo "<tr>
                    <th>Country Name</th>
                    <th>Continent</th>
                    <th>Independence Year</th>
                    <th>Head of State</th>
                  </tr>";

            foreach ($results as $row) {

                echo "<tr>";

                echo "<td>" . htmlspecialchars($row['name'] ?? '') . "</td>";

                echo "<td>" . htmlspecialchars($row['continent'] ?? '') . "</td>";

                echo "<td>" . htmlspecialchars((string)($row['independence_year'] ?? '')) . "</td>";

                echo "<td>" . htmlspecialchars($row['head_of_state'] ?? '') . "</td>";

                echo "</tr>";

            }

            echo "</table>";

        } else {

            echo "<p>No countries found.</p>";

        }

    }

} catch (PDOException $e) {

    http_response_code(500);
    echo "Database error: " . htmlspecialchars($e->getMessage());

}

?>
