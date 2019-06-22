<?php

if (!empty($_GET['r'])) {
    require __DIR__.'/server/run.php';
    exit(0);
}
