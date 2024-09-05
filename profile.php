<?php
include 'config.php';

include 'utils.php';
$utils = new Utils();
if (!$utils->checkCookie('token')) {
    header('Location: ./login?error=disconnected');
}

$req = $db->prepare('SELECT * FROM users WHERE token = ?');
$req->execute(array($_COOKIE['token']));
$user = $req->fetch();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Eloquéncia est une association loi 1901 visant à promouvoir l'éloquence et l'art oratoire">
    <meta name="keywords" content="éloquence, oratoire, association, loi 1901, parler en public, discours, formation, cours en ligne">
    <meta name="author" content="Eloquéncia">
    <meta name="robots" content="index, follow">
    <meta name="revisit-after" content="7 days">
    <meta name="language" content="fr">
    <meta property="og:site_name" content="Eloquéncia">
    <meta property="og:site" content="https://eloquencia.org">
    <meta property="og:title" content="Accueil">
    <meta property="og:description" content="Eloquéncia est une association loi 1901 visant à promouvoir l'éloquence et l'art oratoire">
    <title>Accueil - Eloquéncia</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/eloquencia.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.6.0/css/all.css">
    <script src="js/bootstrap.bundle.js"></script>
    <script src="js/eloquencia.js"></script>
</head>
<body>
<div id="sidebar" class="sidebar d-flex flex-column">
    <button id="toggleButton" class="btn btn-sm text-white">
        <i id="itoggleButton" class="fas fa-bars"></i>
    </button>
    <div class="logo d-flex align-items-center p-3">
        <img class="img-fluid" src="assets/eloquencia_round.png" alt="Logo Eloquéncia" width="50"/><h5 class="text-white" style="font-family: 'Berlin Sans FB'">Eloquéncia</h5>
    </div>
    <hr>
    <nav class="nav flex-column">
        <a href="#" class="nav-link"><i class="fas fa-house"></i><span>Accueil</span></a>
    </nav>
    <hr>
    <?php
    $req = $db->prepare('SELECT * FROM lessons_chapters');
    $req->execute();
    $chapters = $req->fetchAll();
    foreach ($chapters as $chapter) {
        echo '<nav class="nav flex-column">';
        echo '<a href="chapter?id='.$chapter['ID'].'" class="nav-link"><i class="fas fa-book"></i><span>' . $chapter['name'] . '</span></a>';
        echo '</nav>';
    }
    ?>
    <hr>
    <div class="dropup mt-auto mb-3">
        <button type="button" class="btn dropdown-toggle w-100 text-start text-truncate show text-white" data-bs-toggle="dropdown" aria-expanded="false">
            Nom Prénom
        </button>
        <ul class="dropdown-menu" style="position: absolute; inset: auto auto 0px 0px; margin: 0px; transform: translate(0px, -40px);" data-popper-placement="top-start">
            <!-- Dropdown menu links -->
            <li><a class="dropdown-item" href="#"><i class="fas fa-user"></i> Profil</a></li>
            <li class="text-danger-emphasis"><a class="dropdown-item" href="#"><i class="fas fa-door-closed"></i> Déconnexion</a></li>
        </ul>
    </div>
</div>
<div class="content pt-5" id="content">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h6 class="display-6">Profil</h6>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <h5 class="card-header">Informations personnelles</h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <p><strong>Nom :</strong> <?php echo $user['lastname']; ?></p>
                                <p><strong>Prénom :</strong> <?php echo $user['firstname']; ?></p>
                                <p><strong>Email :</strong> <?php echo $user['email']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    const toggleButton = document.getElementById('toggleButton');
    const sidebar = document.getElementById('sidebar');
    const iToggleButton = document.getElementById('itoggleButton');

    toggleButton.addEventListener('click', function() {
        sidebar.classList.toggle('collapsed');
        if (sidebar.classList.contains('collapsed')) {
            iToggleButton.classList.remove('fa-times');
            iToggleButton.classList.add('fa-bars');
        } else {
            iToggleButton.classList.remove('fa-bars');
            iToggleButton.classList.add('fa-times');
        }
    });
</script>
</body>
</html>