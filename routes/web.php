    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\MesaController;

    // Rutas públicas
    Route::controller(AuthController::class)->group(function() {
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'login');
        Route::post('/logout', 'logout')->name('logout');
    });

    // Rutas protegidas
    Route::middleware(['auth', 'dependencia'])->group(function() {
        Route::get('/', function() {
            return view('redidgp');
        })->name('redidgp');
        
        Route::get('/redi-externo', function() {
            return view('rediexterno');
        })->name('redi.externo');
    });

    // Rutas para manejo de documentos
    Route::middleware('auth')->group(function() {
        Route::post('/mesa/recibir/{id}', [MesaController::class, 'recibir'])->name('mesa.recibir');
        Route::post('/mesa/reenviar/{id}', [MesaController::class, 'reenviar'])->name('mesa.reenviar');
    });