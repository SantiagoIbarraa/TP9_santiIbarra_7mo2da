<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Plataforma de Películas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/custom.css" />
</head>
<body>
  <!-- Navbar Mejorado -->
  <nav id="navbar" class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">MiFlix</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navContent">
        <!-- Navegación principal -->
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link active" href="index.php">Inicio</a>
          </li>
          <!-- Solo visible para admin -->
          <li class="nav-item d-none" id="adminNavItem">
            <a class="nav-link" href="admin/movies.php">
              <i class="fas fa-cog"></i> Administrar Películas
            </a>
          </li>
        </ul>

        <!-- Información del usuario y acciones -->
        <div class="navbar-nav ms-auto">
          <!-- Enlaces para usuarios no logueados -->
          <div id="guestNav" class="d-flex">
            <a class="nav-link" id="loginBtn" href="login.html">Iniciar Sesión</a>
            <a class="nav-link" id="registerBtn" href="register.html">Registrarse</a>
          </div>
          
          <!-- Info para usuarios logueados -->
          <div id="userNav" class="d-none d-flex align-items-center">
            <span class="navbar-text text-white me-3">
              <i class="fas fa-user"></i> 
              Bienvenido, <span id="userNameDisplay" class="fw-bold"></span>
              <span id="userRoleDisplay" class="badge bg-primary ms-2"></span>
            </span>
            <a class="nav-link" href="#" id="logoutBtn">
              <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
            </a>
          </div>
        </div>
      </div>
    </div>
  </nav>

  <!-- Carrusel Destacados -->
  <div id="carouselExample" class="carousel slide mt-5 pt-4" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="https://images.unsplash.com/photo-1536440136628-849c177e76a1?w=1920&h=600&fit=crop" class="d-block w-100" alt="Banner 1" style="height: 400px; object-fit: cover;" />
        <div class="carousel-caption d-none d-md-block">
          <h5>Películas Destacadas</h5>
          <p>Disfruta del mejor contenido cinematográfico</p>
        </div>
      </div>
      <div class="carousel-item">
        <img src="https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?w=1920&h=600&fit=crop" class="d-block w-100" alt="Banner 2" style="height: 400px; object-fit: cover;" />
        <div class="carousel-caption d-none d-md-block">
          <h5>Nuevo Contenido</h5>
          <p>Descubre los últimos estrenos</p>
        </div>
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>
  </div>

  <!-- Contenedor de Películas -->
  <div class="container my-5" id="moviesContainer">
    <h2 class="mb-4">Películas Populares</h2>
    <div id="loadingSpinner" class="loading" style="display: none;">
      <div class="spinner"></div>
    </div>
    <div class="row" id="moviesRow"></div>
  </div>

  <!-- Modal para detalles de la película -->
  <div class="modal fade" id="movieDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content bg-dark">
        <div class="modal-header">
          <h5 class="modal-title text-white" id="movieModalTitle"></h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-4">
              <img id="movieModalImage" src="" alt="" class="img-fluid rounded mb-3" style="width: 100%; height: auto;">
            </div>
            <div class="col-md-8">
              <div class="mb-3">
                <h6 class="text-white-50">Género</h6>
                <p class="text-white" id="movieModalGenre"></p>
              </div>
              <div class="mb-3">
                <h6 class="text-white-50">Año</h6>
                <p class="text-white" id="movieModalYear"></p>
              </div>
              <div class="mb-3">
                <h6 class="text-white-50">Descripción</h6>
                <p class="text-white" id="movieModalDescription"></p>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <a id="movieModalPlayButton" href="#" class="btn btn-primary" target="_blank">
            <i class="fas fa-play"></i> Ver Película
          </a>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Funcionalidad principal
    document.addEventListener('DOMContentLoaded', function() {
      loadMovies();
      checkUserSession();
    });

    // Cargar películas desde la API
    async function loadMovies() {
      const spinner = document.getElementById('loadingSpinner');
      const moviesRow = document.getElementById('moviesRow');
      
      try {
        spinner.style.display = 'flex';
        const response = await fetch('../backend/api_movies.php');
        const data = await response.json();
        
        if (data.error) {
          throw new Error(data.message);
        }
        
        if (!Array.isArray(data)) {
          throw new Error('Formato de respuesta inválido');
        }
        
        displayMovies(data);
      } catch (error) {
        console.error('Error loading movies:', error);
        moviesRow.innerHTML = '<div class="col-12 text-center text-danger">Error al cargar películas: ' + error.message + '</div>';
      } finally {
        spinner.style.display = 'none';
      }
    }

    // Mostrar películas en el DOM
    function displayMovies(movies) {
      const moviesRow = document.getElementById('moviesRow');
      if (!moviesRow) return;

      moviesRow.innerHTML = '';

      if (movies.length === 0) {
        moviesRow.innerHTML = '<div class="col-12 text-center text-muted">No hay películas disponibles</div>';
        return;
      }

      movies.forEach(movie => {
        const movieCard = createMovieCard(movie);
        moviesRow.appendChild(movieCard);
      });
    }

    // Crear tarjeta de película
    function createMovieCard(movie) {
      const col = document.createElement('div');
      col.className = 'col-6 col-md-4 col-lg-3 mb-4';

      col.innerHTML = `
        <div class="card h-100 movie-card" style="cursor: pointer;" onclick="openMovieModal(${movie.id}, '${movie.video_url || ''}', '${movie.title}', '${movie.description || ''}', '${movie.genre || ''}', ${movie.year || 'null'}, '${movie.image || ''}')">
          <img src="${movie.image || 'https://via.placeholder.com/300x400?text=Sin+Imagen'}" 
               class="card-img-top" alt="${movie.title}" 
               style="height: 300px; object-fit: cover;"
               onerror="this.src='https://via.placeholder.com/300x400?text=Sin+Imagen'">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">${movie.title}</h5>
            <p class="card-text text-muted small">${movie.genre || 'Sin género'} • ${movie.year || 'N/A'}</p>
            <p class="card-text flex-grow-1">${movie.description ? (movie.description.length > 80 ? movie.description.substring(0, 80) + '...' : movie.description) : 'Sin descripción'}</p>
            <button class="btn btn-sm btn-primary mt-auto" onclick="event.stopPropagation(); openMovieModal(${movie.id}, '${movie.video_url || ''}', '${movie.title}', '${movie.description || ''}', '${movie.genre || ''}', ${movie.year || 'null'}, '${movie.image || ''}')">
              <i class="fas fa-info-circle"></i> Ver Detalles
            </button>
          </div>
        </div>
      `;

      return col;
    }

    // Verificar sesión de usuario
    async function checkUserSession() {
      try {
        const response = await fetch('../backend/session_status.php');
        const data = await response.json();
        
        const guestNav = document.getElementById('guestNav');
        const userNav = document.getElementById('userNav');
        const adminNavItem = document.getElementById('adminNavItem');
        const userNameDisplay = document.getElementById('userNameDisplay');
        const userRoleDisplay = document.getElementById('userRoleDisplay');

        if (data.loggedIn) {
          // Usuario logueado
          guestNav.classList.add('d-none');
          userNav.classList.remove('d-none');
          userNameDisplay.textContent = data.username || 'Usuario';
          
          // Mostrar rol
          if (data.role === 'admin') {
            userRoleDisplay.textContent = 'Administrador';
            userRoleDisplay.className = 'badge bg-danger ms-2';
            adminNavItem.classList.remove('d-none');
          } else {
            userRoleDisplay.textContent = 'Usuario';
            userRoleDisplay.className = 'badge bg-success ms-2';
            adminNavItem.classList.add('d-none');
          }
        } else {
          // Usuario no logueado
          guestNav.classList.remove('d-none');
          userNav.classList.add('d-none');
          adminNavItem.classList.add('d-none');
        }
      } catch (error) {
        console.error('Error checking session:', error);
      }
    }

    // Evento para cerrar sesión
    document.getElementById('logoutBtn').addEventListener('click', function(e) {
      e.preventDefault();
      if (confirm('¿Estás seguro de que deseas cerrar sesión?')) {
        window.location.href = '../backend/logout.php';
      }
    });

    // Abrir modal de detalles de la película
    function openMovieModal(movieId, videoUrl, movieTitle, description, genre, year, imageUrl) {
      const modal = new bootstrap.Modal(document.getElementById('movieDetailsModal'));
      
      // Actualizar contenido del modal
      document.getElementById('movieModalTitle').textContent = movieTitle;
      document.getElementById('movieModalImage').src = imageUrl || 'https://via.placeholder.com/300x400?text=Sin+Imagen';
      document.getElementById('movieModalGenre').textContent = genre || 'No especificado';
      document.getElementById('movieModalYear').textContent = year || 'No especificado';
      document.getElementById('movieModalDescription').textContent = description || 'Sin descripción disponible';
      
      // Configurar botón de reproducción
      const playButton = document.getElementById('movieModalPlayButton');
      if (videoUrl) {
        playButton.href = videoUrl;
        playButton.classList.remove('d-none');
      } else {
        playButton.classList.add('d-none');
      }
      
      modal.show();
    }

    // Limpiar el iframe cuando se cierra el modal
    document.getElementById('moviePlayModal').addEventListener('hidden.bs.modal', function () {
      document.getElementById('moviePlayer').src = '';
    });

    // Navbar effect on scroll
    window.addEventListener('scroll', () => {
      const navbar = document.getElementById('navbar');
      if (window.scrollY > 50) {
        navbar.classList.add('bg-opacity-95');
      } else {
        navbar.classList.remove('bg-opacity-95');
      }
    });
  </script>
  
  <!-- Font Awesome para iconos -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</body>
</html>