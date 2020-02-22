<?php
    include_once("connectdb.php");

    $SQL = "DROP TABLE IF EXISTS `web`;
    CREATE TABLE IF NOT EXISTS `web` (
    `webID` int(11) NOT NULL,
    `webURI` varchar(2083) NOT NULL,
    `webContext` longtext NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ALTER TABLE `web` ADD PRIMARY KEY (`webID`);
    ALTER TABLE `web` MODIFY `webID` int(11) NOT NULL AUTO_INCREMENT;";
    $dbh->exec($SQL);
    echo "Success";
?>
