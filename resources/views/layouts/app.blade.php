<!DOCTYPE html>
<html lang="es">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

</head>
<body>
<!--  
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
      <a class="navbar-brand" href="{{ route('menu') }}">Retenciones</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="mainNav">
        <ul class="navbar-nav mr-auto">
           Tus enlaces de navegación principales 
          <li class="nav-item"><a class="nav-link" href="{{ route('retention.create') }}">Generar Retención</a></li>
          
        </ul>

         Aquí van los enlaces de autenticación 
        <ul class="navbar-nav ml-auto">
          @guest
            <li class="nav-item">
              <a class="nav-link" href="{{ route('login') }}">Login</a>
            </li>
          @else
            <li class="nav-item">
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="nav-link btn btn-link" style="display:inline; padding:0;">Logout</button>
              </form>
            </li>
          @endguest
        </ul>
      </div>
    </div>
  </nav> -->
  <script>
  // Al disparar unload, muchos navegadores invalidan la instantánea en bfcache
    window.addEventListener('unload', function() {});
  </script>

  <main class="py-4">
    @yield('content')
  </main>
</body>
</html>
