<?php
require 'vendor/autoload.php';

$client = new MongoDB\Client("mongodb://localhost:27017");
$db = $client->clients;
$account_id = iterator_to_array($db->accounts->findOne(array(login => 'amadeus')))['_id']->__toString();
$criteria = array(
    'accaunt_id' => $account_id
    );
$resultDev = iterator_to_array($db->device->find($criteria));
$resultSta = iterator_to_array($db->station->find($criteria));
echo 'Зарегистрированные устройства управления: </br>';
foreach ($resultDev as $dev) {
print_r($dev['name']);
echo '</br>';
}
echo 'Зарегистрированные устройства "Дворецкий": </br>';
foreach ($resultSta as $sta) {
print_r($sta['name']);
echo '</br>';
}
?>