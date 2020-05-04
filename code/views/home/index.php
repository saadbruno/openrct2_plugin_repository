<!doctype html>
<html lang="en">

<?php include_once "partials/head.php"; ?>

<body>

    <?php
    include_once "partials/nav.php";
    include_once "partials/header.php";
    ?>

    <main role="main" class="container">

        <div class="row">
            <div class="col-12 list-container">
                <h2>Newest plug-ins</h2>
                <div class="plugin-list">
                    <?php

                    $list = getPluginList();

                    foreach ($list as $key => $plugin) {
                        include "partials/list-item.php";
                    }
                    ?>
                </div>
            </div>
        </div>

    </main>


    <?php
    include_once "partials/footer.php";
    include_once "partials/bottom.php";
    ?>

</html>