<?php
require_once('RouterosAPI.php');

$mikrotik_ip = '192.168.11.248';
$mikrotik_username = 'admin';
$mikrotik_password = 'password'; // Ganti dengan password yang aman

$username = $_POST['username'];
$password = $_POST['password'];
$port = $_POST['port'];

$api = new RouterosAPI();
$api->debug = false;

if ($api->connect($mikrotik_ip, 8728, $mikrotik_username, $mikrotik_password)) {

    // 1. Add User
    $api->comm("/user/add", array(
        "name" => $username,
        "password" => $password,
        "group" => "read" // Sesuaikan dengan kebutuhan
    ));

    // 2. Add Firewall Rule (Filter)
    $api->comm("/ip/firewall/filter/add", array(
        "chain" => "input",
        "protocol" => "tcp",
        "dst-port" => $port,
        "src-address" => "0.0.0.0/0", // Atau batasi ke IP tertentu
        "action" => "accept",
        "comment" => "Allow remote for " . $username
    ));

    // 3. Add NAT Rule (dstnat)
    $api->comm("/ip/firewall/nat/add", array(
        "chain" => "dstnat",
        "protocol" => "tcp",
        "dst-port" => $port,
        "action" => "dst-nat",
        "to-addresses" => "192.168.11.248", // IP Mikrotik
        "to-ports" => $port,
        "comment" => "NAT for remote " . $username
    ));

    $api->disconnect();
    echo "Account " . $username . " created successfully!";

} else {
    echo "Connection to Mikrotik failed.";
}
?>
