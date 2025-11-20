<?php

return [
    'is_production' => envdb('MIDTRANS_IS_PRODUCTION', false),
    'server_key' => envdb('MIDTRANS_SERVER_KEY'),
    'client_key' => envdb('MIDTRANS_CLIENT_KEY'),
];
