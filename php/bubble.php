<?php echo '
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width"/>
<link rel="icon" href="../medias/icon.png"/>
<link rel="stylesheet" href="../css/style.css?'. $version . '"/>
<link rel="stylesheet" href="../css/blog.css?'. $version . '"/>
<title>Terminale H Blog</title>
</head>
<body>';

//*script


// TODO : edit button
// TODO : delete button


$sql = 'SELECT * FROM `blog` WHERE `id`="' . $id . '"';
$result = CONN->query($sql);
$row = $result->fetch_assoc();

$date = explode(" ", $row['published'])[0];
$time = explode(" ", $row['published'])[1];

echo '<div class="bubbles">
        <div class="bubble static" id="actions">
            <div class="container" id="return">
                <img src="../medias/left_arrow.png" alt="Left Arrow" /><h2>Retour</h2>
            </div>
        </div>
        <div class="bubble static noform" id="article">
            <h1>' . $row['title'] . '</h1>
            <p><i>Publié par ' . $row['author'] . ' le ' .  $date . ' à ' . $time . '</i>
            <br/>
            <br/>
            ' . $row['content'] . '
            </p>
        </div>
    </div>
    <form method="post" action="" class="invisible" id="killform">
    <input type="hidden" name="state" value=""/>
    <input type="hidden" id="token" name="token" value="' . $token . '"/>
    </form>';


echo '<script src="https://code.jquery.com/jquery-3.6.0.js?'. $version . '"></script>
<script type="text/javascript" src="../js/main.js?'. $version . '"></script>
<script type="text/javascript" src="../js/blog.js?'. $version . '"></script>
</body></html>'; ?>
