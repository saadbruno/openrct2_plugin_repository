<?php
function getPluginList()
{

    global $pdo;

    $plugins = [];

    // get plugins

    $stmt_plugins = $pdo->query("SELECT `plugins`.`id`,`plugins`.`name`,`plugins`.`description`,`plugins`.`submittedAt`,`plugins`.`updatedAt`,`plugins`.`usesCustomOpenGraphImage`,`plugins`.`thumbnail`,`plugins`.`stargazers`,`plugins`.`owner`, `users`.`username`, `users`.`avatarUrl`
                                FROM `plugins`
                                LEFT JOIN `users`
                                ON `plugins`.`owner` = `users`.`id`
                                ORDER BY `plugins`.`submittedAt` DESC");
    while ($row_plugins = $stmt_plugins->fetch()) {

        // get plugin tags
        $stmt_tags = $pdo->prepare("SELECT `tag` FROM `tags` WHERE `plugin_id` = ? ORDER BY `tags`.`tag` ASC");
        $stmt_tags->execute([$row_plugins['id']]);
        $tags = $stmt_tags->fetchAll();

        // sets relative dates for updated and submitted
        $row_plugins['submittedAtRel'] = findTimeAgo(date("Y-m-d H:i:s", $row_plugins['submittedAt']), 'now');
        $row_plugins['submittedAtRelShort'] = findTimeAgo(date("Y-m-d H:i:s", $row_plugins['submittedAt']), 'now', 'short');
        $row_plugins['updatedAtRel'] = findTimeAgo(date("Y-m-d H:i:s", $row_plugins['updatedAt']), 'now');
        $row_plugins['updatedAtRelShort'] = findTimeAgo(date("Y-m-d H:i:s", $row_plugins['updatedAt']), 'now', 'short');


        $plugins[$row_plugins['id']] = $row_plugins;
        $plugins[$row_plugins['id']]['tags'] = $tags;

    }

    return $plugins;
}
