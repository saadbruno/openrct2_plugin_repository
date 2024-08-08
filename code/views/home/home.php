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
            <?php
                drawList($list_new, 'Newest plug-ins', false, 'new', 1, false);
                drawList($list_rating, 'Most starred plug-ins', false, 'rating', 1, false);
            ?>
        </div>

    </main>


    <?php
    include_once "partials/footer.php";
    include_once "partials/bottom.php";
    ?>

</html>