<?php

return [
    'smtp' => [
        'host' => 'smtp.gmail.com',
        'port' => 587,
        'username' => 'arthurkatumba788@gmail.com',
        'password' => 'yinl lfpi qhif lluw', // Collez le mot de passe généré ici
        'encryption' => 'tls',
        'from_email' => 'arthurkatumba788@gmail.com',
        'from_name' => 'Blog information et technologie',
        'smtp_options' => [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ]
    ]
];
