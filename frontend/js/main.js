// main.js
const moviesRow = document.getElementById('moviesRow');

fetch('../backend/api_movies.php')
  .then(r => r.json())
  .then(data => {
    data.forEach(movie => {
      const col = document.createElement('div');
      col.className = 'col-6 col-md-4 col-lg-3 mb-4';
      col.innerHTML = `
        <div class="card h-100 movie-card shadow-sm" data-id="${movie.id}">
          <img src="${movie.image}" class="card-img-top" alt="${movie.title}">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">${movie.title}</h5>
            <p class="card-text small text-muted">${movie.genre} • ${movie.year}</p>
            <button class="btn btn-sm btn-outline-primary mt-auto" onclick="viewTrailer(${movie.id})">Ver Trailer</button>
          </div>
        </div>`;
      moviesRow.appendChild(col);
    });
  });

function viewTrailer(id) {
  alert('Mostrar modal de trailer para movie id ' + id);
}

// Navbar effect
const navbar = document.getElementById('navbar');
window.addEventListener('scroll', () => {
  if (window.scrollY > 50) {
    navbar.classList.add('bg-opacity-75');
  } else {
    navbar.classList.remove('bg-opacity-75');
  }
});
// main.js - Funcionalidad principal del frontend

document.addEventListener('DOMContentLoaded', function() {
  loadMovies();
  checkUserSession();
});

// Cargar películas desde la API
async function loadMovies() {
  try {
      const response = await fetch('../backend/api_movies.php');
      const movies = await response.json();
      displayMovies(movies);
  } catch (error) {
      console.error('Error loading movies:', error);
  }
}

// Mostrar películas en el DOM
function displayMovies(movies) {
  const moviesRow = document.getElementById('moviesRow');
  if (!moviesRow) return;

  moviesRow.innerHTML = '';

  movies.forEach(movie => {
      const movieCard = createMovieCard(movie);
      moviesRow.appendChild(movieCard);
  });
}

// Crear tarjeta de película
function createMovieCard(movie) {
  const col = document.createElement('div');
  col.className = 'col-md-3 mb-4';

  col.innerHTML = `
      <div class="card h-100 movie-card" style="cursor: pointer;" onclick="openMovieModal(${movie.id})">
          <img src="${movie.image || 'https://via.placeholder.com/300x400'}" 
               class="card-img-top" alt="${movie.title}" 
               style="height: 300px; object-fit: cover;">
          <div class="card-body">
              <h5 class="card-title">${movie.title}</h5>
              <p class="card-text text-muted">${movie.genre} • ${movie.year}</p>
              <p class="card-text">${movie.description ? movie.description.substring(0, 100) + '...' : ''}</p>
          </div>
      </div>
  `;

  return col;
}

// Verificar sesión de usuario
function checkUserSession() {
    fetch('../backend/session_status.php')
        .then(response => response.json())
        .then(data => {
            const loginBtn = document.getElementById('loginBtn');
            const registerBtn = document.getElementById('registerBtn');
            const logoutNavItem = document.getElementById('logoutNavItem');
            const homeNavItem = document.getElementById('homeNavItem');
            const userNameDisplay = document.getElementById('userNameDisplay');
            if (data.loggedIn) {
                if (loginBtn) loginBtn.classList.add('d-none');
                if (registerBtn) registerBtn.classList.add('d-none');
                if (logoutNavItem) logoutNavItem.classList.remove('d-none');
                if (homeNavItem) homeNavItem.classList.remove('d-none');
                if (userNameDisplay) {
                    userNameDisplay.textContent = data.username;
                    userNameDisplay.classList.remove('d-none');
                }
            } else {
                if (loginBtn) loginBtn.classList.remove('d-none');
                if (registerBtn) registerBtn.classList.remove('d-none');
                if (logoutNavItem) logoutNavItem.classList.add('d-none');
                if (homeNavItem) homeNavItem.classList.add('d-none');
                if (userNameDisplay) {
                    userNameDisplay.textContent = '';
                    userNameDisplay.classList.add('d-none');
                }
            }
        });

    // Evento para cerrar sesión
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            fetch('../backend/logout.php')
                .then(() => {
                    window.location.href = 'login.html';
                });
        });
    }
}

// Abrir modal de película (se implementará más tarde)
function openMovieModal(movieId) {
  console.log('Opening movie modal for ID:', movieId);
  // Aquí implementarás el modal con el reproductor de video
}

// Función para manejar favoritos (offline con IndexedDB)
function toggleFavorite(movieId) {
  // Implementar IndexedDB para favoritos offline
  console.log('Toggle favorite for movie:', movieId);
}