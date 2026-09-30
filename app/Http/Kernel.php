protected $routeMiddleware = [
    // ... middleware lainnya ...
    
    // Tambahkan ini
    'role' => \App\Http\Middleware\RoleMiddleware::class,
];