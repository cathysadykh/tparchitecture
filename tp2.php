<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mglsi_news";

// Créer la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupérer les catégories
$categories_sql = "SELECT id, libelle FROM categorie";
$categories_result = $conn->query($categories_sql);

// Récupérer les libellés associés
$libelles_sql = "SELECT id ,contenu FROM article";
$libelles_result = $conn->query($libelles_sql);

$libelles = [];
if ($libelles_result->num_rows > 0) {
    while ($row = $libelles_result->fetch_assoc()) {
        $libelles[$row['id']][] = $row['contenu'];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Catégories et Libellés</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f9f9f9;
        color: #333;
        margin: 0;
        padding: 0;
    }

    .navbar {
        background-color: #333;
        overflow: hidden;
        display: flex;
        justify-content: center;
        padding: 14px 0;
    }

    .navbar ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
        display: flex;
    }

    .navbar li {
        margin: 0 15px;
    }

    .navbar a.category {
        text-decoration: none;
        color: #fff;
        font-weight: bold;
        font-size: 18px;
        padding: 14px 20px;
        display: block;
    }

    .navbar a.category:hover {
        background-color: #575757;
    }

    #libelles-container {
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        border: 1px solid #ddd;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 80%;
    }

    .libelles {
        display: none;
    }

    .libelles p {
        margin: 10px 0;
        padding: 10px;
        background-color: #f1f1f1;
        border-left: 4px solid #007BFF;
    }
    </style>
</head>

<body>
    <div class="navbar">
        <ul>
            <?php
            if ($categories_result->num_rows > 0) {
                while ($row = $categories_result->fetch_assoc()) {
                    echo "<li><a href='#' class='category' data-id='" . $row['id'] . "'>" . $row['libelle'] . "</a></li>";
                }
            }
            ?>
        </ul>
    </div>

    <div id="libelles-container">
        <?php
        foreach ($libelles as $category_id => $libelle_array) {
            echo "<div class='libelles' id='libelles-" . $category_id . "'>";
            foreach ($libelle_array as $libelle) {
                echo "<p>" . $libelle . "</p>";
            }
            echo "</div>";
        }
        ?>
    </div>

    <script>
    document.querySelectorAll('.category').forEach(category => {
        category.addEventListener('click', function(event) {
            event.preventDefault();
            document.querySelectorAll('.libelles').forEach(libelle => libelle.style.display = 'none');
            document.getElementById('libelles-' + this.dataset.id).style.display = 'block';
        });
    });
    </script>
</body>

</html>