<?php

function showNotif(array $options = []): string
{
    $title = $options['title'] ?? '';
    $message = $options['message'] ?? '';
    $type = $options['type'] ?? 'primary';
    return "notify({title:'{$title}',message:'{$message}'}, {type:'{$type}'});";
}



if ($notif = session()->getFlashdata('notif')) {
    echo showNotif($notif);
}
