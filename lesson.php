<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'config.php';

include 'utils.php';
$utils = new Utils();
if (!$utils->checkCookie('token')) {
    header('Location: ./login?error=disconnected');
}

if(isset($_GET['id'])) {
    $req = $db->prepare('SELECT * FROM lessons WHERE ID = :id');
    $req->execute(array('id' => htmlspecialchars($_GET['id'])));
    $lesson = $req->fetch();
    $req = $db->prepare('SELECT name FROM lessons_chapters WHERE ID = :id');
    $req->execute(array('id' => $lesson['chapter']));
    $chapter = $req->fetch();
} else {
    header('Location: ./?error=lessonundefined');
}

if (isset($_GET['read'])) {
    $utils->setLessonHistory($_COOKIE['token'], htmlspecialchars($_GET['read']));
}
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
<div id="sidebar" class="sidebar d-flex flex-column <?php if (isset($_COOKIE['sidebar']) && $_COOKIE['sidebar'] == 'collapsed') { echo 'collapsed'; } ?>">
    <button id="toggleButton" class="btn btn-sm text-white">
        <i id="itoggleButton" class="fas fa-bars"></i>
    </button>
    <div class="logo d-flex align-items-center p-3">
        <img class="img-fluid" src="assets/eloquencia_round.png" alt="Logo Eloquéncia" width="50"/><h5 class="text-white" style="font-family: 'Berlin Sans FB'">Eloquéncia</h5>
    </div>
    <hr>
    <nav class="nav flex-column">
        <a href="./" class="nav-link"><i class="fas fa-house"></i><span>Accueil</span></a>
    </nav>
    <hr>
    <?php
    $req = $db->prepare('SELECT * FROM lessons_chapters');
    $req->execute();
    $chapters = $req->fetchAll();
    foreach ($chapters as $chapterlist) {
        $activeClass = $chapterlist['ID'] == $lesson['chapter'] ? 'active' : '';
        echo '<nav class="nav flex-column">';
        echo '<a href="chapter?id=' . $chapterlist['ID'] . '" class="nav-link ' . $activeClass . '"><i class="fas fa-book"></i><span>' . $chapterlist['name'] . '</span></a>';
        echo '</nav>';
    }
    ?>
    <hr>
    <div class="dropup mt-auto mb-3">
        <button type="button" class="btn dropdown-toggle w-100 text-start text-truncate show text-white" data-bs-toggle="dropdown" aria-expanded="false">
            <?php echo $utils->getNameFirstname($_COOKIE['token']); ?>
        </button>
        <ul class="dropdown-menu" style="position: absolute; inset: auto auto 0px 0px; margin: 0px; transform: translate(0px, -40px);" data-popper-placement="top-start">
            <!-- Dropdown menu links -->
            <li><a class="dropdown-item" href="profile"><i class="fas fa-user"></i> Profil</a></li>
            <li class="text-danger-emphasis"><a class="dropdown-item" href="logout"><i class="fas fa-door-closed"></i> Déconnexion</a></li>
        </ul>
    </div>
</div>
<div class="content pt-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h6 class="display-5"><?= $chapter['name']; ?></h6>
                <h5 class="display-6"><?= $lesson['title']; ?></h5>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <h5 class="card-header">Introduction</h5>
                    <div class="card-body">
                        <p class="card-text"><?= html_entity_decode($lesson['content']); ?></p>
                    </div>
                    <div class="card-footer text-end">
                        <?php
                        $req = $db->prepare('SELECT ID FROM lessons WHERE ID > :id LIMIT 1');
                        $req->execute(array('id' => $lesson['ID']));
                        $nextLesson = $req->fetch();
                        if ($nextLesson) {
                            ?>
                            <a href="lesson?id=<?= $nextLesson['ID'] ?>&read=<?= $lesson['ID']?>" class="btn btn-primary">Suivant</a>
                            <?php
                        } else {
                            ?>
                            <a href="chapter?id=<?= $lesson['chapter'] ?>&read=<?= $lesson['ID']?>" class="btn btn-primary">Retour</a>
                            <?php
                        }
                        ?>
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