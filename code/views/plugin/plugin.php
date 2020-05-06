<!doctype html>
<html lang="en">

<?php include_once "views/home/partials/head.php"; ?>

<body>

    <?php
    include_once "views/home/partials/nav.php";
    ?>

    <main role="main" class="container plugin-details">

        <div class="row">

            <div class="col-12 title">
                <h1 class="display-1"><?= $plugin['name'] ?></h1>
                <p class="lead">
                    <?= $plugin['description'] ?>
                </p>
            </div>

            <?php include "partials/sidebar.php"; ?>

            <div class="col-12 col-md-9 content">
                <div class="description">
                    <?php
                    if (empty($plugin['readme'])) {
                        echo '<i class="no-description">No description provided</i>';
                    } else {
                        $Parsedown->text(stripslashes( $plugin['readme'] ));
                    }
                    ?>
                </div>
            </div>

        </div>

    </main>


    <?php
    include_once "views/home/partials/footer.php";
    include_once "views/home/partials/bottom.php";
    ?>

</html>