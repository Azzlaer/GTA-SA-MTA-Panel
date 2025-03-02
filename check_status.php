<?php
function isProcessRunning($processName) {
    $output = shell_exec('tasklist /FI "IMAGENAME eq ' . $processName . '"');
    return strpos($output, $processName) !== false;
}

echo isProcessRunning("MTA Server.exe") ? "Online" : "Offline";
?>