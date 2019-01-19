<?php

include_once 'class/main.php';
require 'rb.php';

function usersToXml($filename) {
    $xmlfile = $filename;
    $fh = fopen($xmlfile, 'w');

    $xml = '<?xml version="1.0" encoding="utf-8"?>';
    $xml .= '<users>'.PHP_EOL;

    $connection = Database::connect();
    $users = $connection->prepare(
             "SELECT id, name, surname, email, creation_date from user"
            );
    $users->execute();
    $users->store_result();
    $users->bind_result($id, $name, $surname, $email, $creation_date);

    while ($users->fetch()) {
        $xml .= '<user>';
        $xml .= '<id>' .$id. '</id>';
        $xml .= '<name>' .$name. '</name>';
        $xml .= '<surname>' .$surname. '</surname>';
        $xml .= '<email>' .$email. '</email>';
        $xml .= '<creation_date>' .$creation_date. '</creation_date>';
        $xml .= '</user>'.PHP_EOL;
    }

    $xml .= '</users>'.PHP_EOL;

    fwrite($fh, $xml);
    fclose($fh);

    $connection->close();
    
    echo "Utworzono plik " .$filename ."<br>";
}

function itemsToXml($filename) {
    $xmlfile = $filename;
    $fh = fopen($xmlfile, 'w');

    $xml = '<?xml version="1.0" encoding="utf-8"?>';
    $xml .= '<items>'.PHP_EOL;

    $connection = Database::connect();
    $items = $connection->prepare(
             "SELECT id, name, price, link from item"
            );
    $items->execute();
    $items->store_result();
    $items->bind_result($id, $name, $price, $link);

    while ($items->fetch()) {
        $xml .= '<item>';
        $xml .= '<id>' .$id. '</id>';
        $xml .= '<name>' .$name. '</name>';
        $xml .= '<price>' .$price. '</price>';
        $xml .= '<link>' .$link. '</link>';
        $xml .= '</item>'.PHP_EOL;
    }

    $xml .= '</items>'.PHP_EOL;

    fwrite($fh, $xml);
    fclose($fh);

    $connection->close();
    
    echo "Utworzono plik " .$filename ."<br>";
}

function wishlistToXml($filename) {
    $xmlfile = $filename;
    $fh = fopen($xmlfile, 'w');

    $xml = '<?xml version="1.0" encoding="utf-8"?>';
    $xml .= '<wishlist>'.PHP_EOL;

    $connection = Database::connect();
    $items = $connection->prepare(
             "SELECT id, user_id, item_id from wishlist"
            );
    $items->execute();
    $items->store_result();
    $items->bind_result($id, $user, $item);

    while ($items->fetch()) {
        $xml .= '<wishlist>';
        $xml .= '<id>' .$id. '</id>';
        $xml .= '<user_id>' .$user. '</user_id>';
        $xml .= '<item_id>' .$item. '</item_id>';
        $xml .= '</wishlist>'.PHP_EOL;
    }

    $xml .= '</wishlist>'.PHP_EOL;

    fwrite($fh, $xml);
    fclose($fh);

    $connection->close();
    
    echo "Utworzono plik " .$filename ."<br>";
}

function claimsToXml($filename) {
    $xmlfile = $filename;
    $fh = fopen($xmlfile, 'w');

    $xml = '<?xml version="1.0" encoding="utf-8"?>';
    $xml .= '<claims>'.PHP_EOL;

    $connection = Database::connect();
    $items = $connection->prepare(
             "SELECT id, item_id, who_id, forwho_id from claims"
            );
    $items->execute();
    $items->store_result();
    $items->bind_result($id, $item, $who, $forwho);

    while ($items->fetch()) {
        $xml .= '<claims>';
        $xml .= '<id>' .$id. '</id>';
        $xml .= '<item_id>' .$item. '</item_id>';
        $xml .= '<who_id>' .$who. '</who_id>';
        $xml .= '<forwho_id>' .$forwho. '</forwho_id>';
        $xml .= '</claims>'.PHP_EOL;
    }

    $xml .= '</claims>'.PHP_EOL;

    fwrite($fh, $xml);
    fclose($fh);

    $connection->close();
    
    echo "Utworzono plik " .$filename ."<br>";
}

function benefactorsToXml($filename) {
    $xmlfile = $filename;
    $fh = fopen($xmlfile, 'w');

    $xml = '<?xml version="1.0" encoding="utf-8"?>';
    $xml .= '<benefactors>'.PHP_EOL;

    $connection = Database::connect();
    $items = $connection->prepare(
             "SELECT id, user_id, benefactor_id from benefactor"
            );
    $items->execute();
    $items->store_result();
    $items->bind_result($id, $user, $benefactor);

    while ($items->fetch()) {
        $xml .= '<benefactor>';
        $xml .= '<id>' .$id. '</id>';
        $xml .= '<user_id>' .$user. '</user_id>';
        $xml .= '<benefactor_id>' .$benefactor. '</benefactor_id>';
        $xml .= '</benefactor>'.PHP_EOL;
    }

    $xml .= '</benefactors>'.PHP_EOL;

    fwrite($fh, $xml);
    fclose($fh);

    $connection->close();
    
    echo "Utworzono plik " .$filename ."<br>";
}

usersToXml('users.xml');
itemsToXml('items.xml');
wishlistToXml('wishlist.xml');
claimsToXml('claims.xml');
benefactorsToXml('benefactors.xml');
