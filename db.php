<?php
$conn = new mysqli("localhost", "root", "", "cardekho");
if ($conn->connect_error) {
    die("DB Connection Failed");
}
