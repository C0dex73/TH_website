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


$username = getUsername($token);

$sql = 'SELECT `title`,`author`,`id` FROM blog ORDER BY `published` DESC';
$result = CONN->query($sql);
if($result->num_rows > 0){

    $redirect = $bText = "";

    if(toUser($token) == ""){
        $redirect = '2';
        $bText = "Se connecter";
    }else{
        $redirect = '-4';
        $bText = "Poster un article";
    }

    echo '
        <div class="bubbles">
            <div class="bubble" id="action">
                <form class="invisible" method="post" id="action" action="">
                    <button id="actionbutton" class="styled" type="button" onclick="">
                    ' . $bText . '
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    </button>
                    <input type="hidden" id="token" name="token" value="' . $token . '"/>
                    <input type="hidden" id="state" name="state" value="' . $redirect . '"/>
                </form>
            </div>';
    while($row = $result->fetch_assoc()) {
        echo '  <div class="bubble">
                    <form class="invisible article" method="post" action="">
                        <p><i> - ' . $row['author'] . '</i></p>
                        <h2>'. $row['title'] . '</h2>
                        <input type="hidden" id="id" name="id" value="' . $row['id'] . '"/>
                        <input type="hidden" id="state" name="state" value="3"/>
                        <input type="hidden" id="token" name="token" value="' . $token . '"/>
                    </form>
                </div>';
    }
    echo '</div>';
}else{
    echo '<p><i>Tout est vide, pour l\'instant...</i></p>';
}



echo '<script src="https://code.jquery.com/jquery-3.6.0.js?'. $version . '"></script>
<script type="text/javascript" src="../js/main.js?'. $version . '"></script>
<script type="text/javascript" src="../js/blog.js?'. $version . '"></script>
</body></html>'; ?>
