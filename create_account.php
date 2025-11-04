<?php
require_once('RouterosAPI.php');
require_once('config.php');
require_once('database.php');

$username = $_POST['username'];
$password = $_POST['password'];
$port = $_POST['port'];

$api = new RouterosAPI();
$api->debug = false;

if ($api->connect($mikrotik_ip, 8728, $mikrotik_username, $mikrotik_password)) {

    // Validasi port (opsional)
    if ($port < 1 || $port > 65535) {
        echo "Error: Port harus antara 1 dan 65535.";
        exit;
    }

    // 1. Add User
    $api->comm("/user/add", array(
        "name" => $username,
        "password" => $password,
        "group" => $default_group
    ));

    // 2. Add Firewall Rule (Filter)
    $api->comm("/ip/firewall/filter/add", array(
        "chain" => "input",
        "protocol" => "tcp",
        "dst-port" => $port,
        "src-address" => $allowed_src_address,
        "action" => "accept",
        "comment" => "Allow remote for " . $username
    ));

    // 3. Add NAT Rule (dstnat)
    $api->comm("/ip/firewall/nat/add", array(
        "chain" => "dstnat",
        "protocol" => "tcp",
        "dst-port" => $port,
        "action" => "dst-nat",
        "to-addresses" => $mikrotik_ip, // IP Mikrotik
        "to-ports" => $port,
        "comment" => "NAT for remote " . $username
    ));

    $api->disconnect();

    // 4. Tambahkan ke database
    $db_result = addAccountToDatabase($username, $password, $port);
    if ($db_result === true) {
        echo "Account " . $username . " created successfully!";
    } else {
        echo "Account " . $username . " created on Mikrotik, but failed to add to database: " . $db_result;
    }

} else {
    echo "Connection to Mikrotik failed.";
}

closeDatabaseConnection(); // Tutup koneksi database
?>
