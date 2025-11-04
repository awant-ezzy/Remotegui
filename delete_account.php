<?php
require_once('RouterosAPI.php');
require_once('config.php');
require_once('database.php');

$username = $_POST['username'];

$api = new RouterosAPI();
$api->debug = false;

if ($api->connect($mikrotik_ip, 8728, $mikrotik_username, $mikrotik_password)) {

    // 1. Remove User
    $users = $api->comm("/user/print", array("?name" => $username));
    if (count($users) > 0) {
        $api->comm("/user/remove", array(".id" => $users[0]['.id']));
    }

    // 2. Remove Firewall Rule (Filter)
    $firewall_rules = $api->comm("/ip/firewall/filter/print", array("?comment" => "Allow remote for " . $username));
    if (count($firewall_rules) > 0) {
        $api->comm("/ip/firewall/filter/remove", array(".id" => $firewall_rules[0]['.id']));
    }

    // 3. Remove NAT Rule (dstnat)
    $nat_rules = $api->comm("/ip/firewall/nat/print", array("?comment" => "NAT for remote " . $username));
    if (count($nat_rules) > 0) {
        $api->comm("/ip/firewall/nat/remove", array(".id" => $nat_rules[0]['.id']));
    }

    $api->disconnect();

    // 4. Hapus dari database
    $db_result = deleteAccountFromDatabase($username);
    if ($db_result === true) {
        echo "Account " . $username . " deleted successfully!";
    } else {
        echo "Account " . $username . " deleted on Mikrotik, but failed to delete from database: " . $db_result;
    }

} else {
    echo "Connection to Mikrotik failed.";
}

closeDatabaseConnection(); // Tutup koneksi database
?>
