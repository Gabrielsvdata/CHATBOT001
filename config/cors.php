<?php

return [

    'paths' => [
        'api/*', // Qualquer rota com prefixo /api
        'sanctum/csrf-cookie', // Rota para o CSRF token
    ],

    'allowed_methods' => ['*'], // Permite todos os métodos HTTP (GET, POST, etc.)

    'allowed_origins' => [
        'http://localhost:5173',  // Permite a origem do seu front-end
        'http://localhost:3000',  // Adicione outras origens se necessário
        'http://127.0.0.1:5173', // Se você estiver utilizando outros domínios ou portas
    ],

    'allowed_origins_patterns' => [], // Deixe vazio para não aplicar padrões extras

    'allowed_headers' => ['*'], // Permite todos os cabeçalhos HTTP

    'exposed_headers' => [], // Exponha cabeçalhos se necessário (exemplo: Authorization)

    'max_age' => 0, // Defina um tempo de cache de resposta (0 significa sem cache)

    'supports_credentials' => true, // Permite enviar cookies e cabeçalhos de autenticação (importante para autenticação via Sanctum)

];
