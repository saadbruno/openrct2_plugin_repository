<?php
function getUserInfo($id = 0)
{
    global $pdo;

    // get plugins
    $stmt_user = $pdo->prepare("SELECT `id`,`username`,`avatarUrl`,`url`
                                    FROM `users`
                                    WHERE id = ?
                                    LIMIT 1"
                                   );
    $stmt_user->execute([$id]);
    $row_user = $stmt_user->fetch();

    return $row_user;
}
