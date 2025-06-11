<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Películas - MiFlix</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/custom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Navbar Mejorado para Admin -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.php">
                <i class="fas fa-film"></i> MiFlix Admin
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navContent">
                <!-- Navegación principal -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">
                            <i class="fas fa-home"></i> Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="movies.php">
                            <i class="fas fa-video"></i> Películas
                        </a>
                    </li>
                </ul>

                <!-- Información del admin -->
                <div class="navbar-nav ms-auto d-flex align-items-center">
                    <span class="navbar-text text-white me-3">
                        <i class="fas fa-user-shield"></i> 
                        <span id="adminNameDisplay" class="fw-bold">Admin</span>
                        <span class="badge bg-danger ms-2">Administrador</span>
                    </span>
                    <a class="nav-link" href="../../backend/logout.php" onclick="return confirm('¿Cerrar sesión?')">
                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-5 pt-4">
        <!-- Contenedor de alertas -->
        <div id="alertContainer"></div>
        
        <div class="row">
            <div class="col-12">
                <!-- Header de la página -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="mb-1">
                            <i class="fas fa-cogs"></i> Panel de Administración
                        </h2>
                        <p class="text-muted mb-0">Gestiona el catálogo de películas</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="../index.php" class="btn btn-outline-primary">
                            <i class="fas fa-eye"></i> Ver Sitio Público
                        </a>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#movieModal" onclick="openAddModal()">
                            <i class="fas fa-plus"></i> Nueva Película
                        </button>
                    </div>
                </div>

                <!-- Loading spinner -->
                <div id="loadingSpinner" class="loading" style="display: none;">
                    <div class="spinner"></div>
                </div>

                <!-- Estadísticas rápidas -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 id="totalMovies">0</h4>
                                        <small>Total Películas</small>
                                    </div>
                                    <i class="fas fa-film fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 id="recentMovies">0</h4>
                                        <small>Este Mes</small>
                                    </div>
                                    <i class="fas fa-calendar fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 id="totalGenres">0</h4>
                                        <small>Géneros</small>
                                    </div>
                                    <i class="fas fa-tags fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 id="avgYear">0</h4>
                                        <small>Año Promedio</small>
                                    </div>
                                    <i class="fas fa-clock fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla de películas -->
                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Imagen</th>
                                <th>Título</th>
                                <th>Género</th>
                                <th>Año</th>
                                <th>Fecha Creación</th>
                                <th width="120">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="moviesTableBody">
                            <!-- Las películas se cargarán aquí con JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para agregar/editar película -->
    <div class="modal fade" id="movieModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-dark">
                <div class="modal-header">
                    <h5 class="modal-title text-white" id="modalTitle">
                        <i class="fas fa-plus"></i> Agregar Película
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="movieForm" action="../../backend/crud_movies.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" id="movieId" name="id">
                        <input type="hidden" id="formAction" name="action" value="create">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title" class="form-label text-white">
                                        <i class="fas fa-heading"></i> Título *
                                    </label>
                                    <input type="text" class="form-control" id="title" name="title" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="genre" class="form-label text-white">
                                        <i class="fas fa-tags"></i> Género
                                    </label>
                                    <select class="form-control" id="genre" name="genre">
                                        <option value="">Seleccionar género</option>
                                        <option value="Acción">Acción</option>
                                        <option value="Aventura">Aventura</option>
                                        <option value="Comedia">Comedia</option>
                                        <option value="Drama">Drama</option>
                                        <option value="Terror">Terror</option>
                                        <option value="Ciencia Ficción">Ciencia Ficción</option>
                                        <option value="Romance">Romance</option>
                                        <option value="Thriller">Thriller</option>
                                        <option value="Animación">Animación</option>
                                        <option value="Documental">Documental</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="year" class="form-label text-white">
                                        <i class="fas fa-calendar"></i> Año
                                    </label>
                                    <input type="number" class="form-control" id="year" name="year" min="1900" max="2030">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="image" class="form-label text-white">
                                        <i class="fas fa-image"></i> URL de Imagen
                                    </label>
                                    <input type="url" class="form-control" id="image" name="image" placeholder="https://ejemplo.com/imagen.jpg">
                                    <div class="form-text text-muted">URL de la imagen/poster de la película</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="video_url" class="form-label text-white">
                                        <i class="fas fa-video"></i> URL del Video
                                    </label>
                                    <input type="url" class="form-control" id="video_url" name="video_url" placeholder="https://ejemplo.com/video.mp4">
                                    <div class="form-text text-muted">URL del archivo de video</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label text-white">
                                <i class="fas fa-align-left"></i> Descripción
                            </label>
                            <textarea class="form-control" id="description" name="description" rows="4" placeholder="Descripción de la película..."></textarea>
                        </div>
                        
                        <!-- Preview de imagen -->
                        <div id="imagePreview" class="mb-3" style="display: none;">
                            <label class="form-label text-white">Vista previa:</label>
                            <br>
                            <img id="previewImg" src="" alt="Preview" style="max-width: 200px; max-height: 300px; border-radius: 8px;">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save"></i> Guardar Película
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación para eliminar -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content bg-dark">
                <div class="modal-header">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-exclamation-triangle text-warning"></i> Confirmar Eliminación
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-white">
                    <p>¿Estás seguro de que deseas eliminar esta película?</p>
                    <p><strong id="movieToDelete"></strong></p>
                    <p class="text-warning">
                        <i class="fas fa-warning"></i> Esta acción no se puede deshacer.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let movieToDeleteId = null;

        // Cargar películas al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            loadMoviesAdmin();
            setupImagePreview();
            loadAdminInfo();
        });

        // Cargar información del admin
        async function loadAdminInfo() {
            try {
                const response = await fetch('../../backend/session_status.php');
                const data = await response.json();
                
                if (data.loggedIn && data.role === 'admin') {
                    document.getElementById('adminNameDisplay').textContent = data.username || 'Admin';
                } else {
                    // Si no es admin, redirigir
                    window.location.href = '../login.html';
                }
            } catch (error) {
                console.error('Error loading admin info:', error);
            }
        }

        // Cargar películas para admin
        async function loadMoviesAdmin() {
            const spinner = document.getElementById('loadingSpinner');
            const tableBody = document.getElementById('moviesTableBody');
            
            try {
                spinner.style.display = 'flex';
                const response = await fetch('../../backend/api_movies.php');
                const movies = await response.json();
                displayMoviesTable(movies);
                updateStats(movies);
            } catch (error) {
                console.error('Error loading movies:', error);
                tableBody.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Error al cargar películas</td></tr>';
            } finally {
                spinner.style.display = 'none';
            }
        }

        // Actualizar estadísticas
        function updateStats(movies) {
            document.getElementById('totalMovies').textContent = movies.length;
            
            // Películas recientes (último mes)
            const lastMonth = new Date();
            lastMonth.setMonth(lastMonth.getMonth() - 1);
            const recentCount = movies.filter(movie => {
                return movie.created_at && new Date(movie.created_at) > lastMonth;
            }).length;
            document.getElementById('recentMovies').textContent = recentCount;
            
            // Géneros únicos
            const genres = new Set(movies.map(movie => movie.genre).filter(genre => genre));
            document.getElementById('totalGenres').textContent = genres.size;
            
            // Año promedio
            const years = movies.map(movie => movie.year).filter(year => year);
            const avgYear = years.length > 0 ? Math.round(years.reduce((a, b) => a + b, 0) / years.length) : 0;
            document.getElementById('avgYear').textContent = avgYear;
        }

        // Mostrar películas en tabla
        function displayMoviesTable(movies) {
            const tbody = document.getElementById('moviesTableBody');
            
            if (movies.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No hay películas registradas</td></tr>';
                return;
            }

            tbody.innerHTML = '';

            movies.forEach(movie => {
                const row = document.createElement('tr');
                const createdDate = movie.created_at ? new Date(movie.created_at).toLocaleDateString() : 'N/A';
                
                row.innerHTML = `
                    <td><span class="badge bg-secondary">#${movie.id}</span></td>
                    <td>
                        ${movie.image ? 
                            `<img src="${movie.image}" alt="${movie.title}" style="width: 50px; height: 75px; object-fit: cover; border-radius: 4px;" onerror="this.src='https://via.placeholder.com/50x75'">` : 
                            '<span class="text-muted"><i class="fas fa-image"></i> Sin imagen</span>'
                        }
                    </td>
                    <td><strong>${movie.title}</strong></td>
                    <td><span class="badge bg-info">${movie.genre || 'N/A'}</span></td>
                    <td>${movie.year || 'N/A'}</td>
                    <td><small class="text-light">${createdDate}</small></td>
                    <td>
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-warning" onclick="editMovie(${movie.id})" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="confirmDeleteMovie(${movie.id}, '${movie.title.replace(/'/g, "\\'")}'))" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        // Abrir modal para agregar
        function openAddModal() {
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-plus"></i> Agregar Película';
            document.getElementById('formAction').value = 'create';
            document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save"></i> Agregar Película';
            document.getElementById('movieForm').reset();
            document.getElementById('movieId').value = '';
            document.getElementById('imagePreview').style.display = 'none';
        }

        // Editar película
        async function editMovie(id) {
            try {
                const response = await fetch('../../backend/api_movies.php');
                const movies = await response.json();
                const movie = movies.find(m => m.id == id);
                
                if (movie) {
                    // Llenar el formulario
                    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit"></i> Editar Película';
                    document.getElementById('formAction').value = 'update';
                    document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save"></i> Actualizar Película';
                    document.getElementById('movieId').value = movie.id;
                    document.getElementById('title').value = movie.title || '';
                    document.getElementById('description').value = movie.description || '';
                    document.getElementById('image').value = movie.image || '';
                    document.getElementById('video_url').value = movie.video_url || '';
                    document.getElementById('genre').value = movie.genre || '';
                    document.getElementById('year').value = movie.year || '';
                    
                    // Mostrar preview de imagen si existe
                    if (movie.image) {
                        document.getElementById('previewImg').src = movie.image;
                        document.getElementById('imagePreview').style.display = 'block';
                    }
                    
                    // Mostrar el modal
                    const modal = new bootstrap.Modal(document.getElementById('movieModal'));
                    modal.show();
                } else {
                    showAlert('error', 'No se encontró la película especificada');
                }
            } catch (error) {
                console.error('Error al cargar película:', error);
                showAlert('error', 'Error al cargar los datos de la película');
            }
        }

        // Confirmar eliminación
        function confirmDeleteMovie(id, title) {
            movieToDeleteId = id;
            document.getElementById('movieToDelete').textContent = title;
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }

        // Eliminar película
        document.getElementById('confirmDelete').addEventListener('click', async function() {
            if (movieToDeleteId) {
                try {
                    const formData = new FormData();
                    formData.append('action', 'delete');
                    formData.append('id', movieToDeleteId);
                    
                    const response = await fetch('../../backend/crud_movies.php', {
                        method: 'POST',
                        body: formData
                    });
                    
                    const result = await response.json();
                    if (result.success) {
                        showAlert('success', 'Película eliminada exitosamente');
                        loadMoviesAdmin(); // Recargar la tabla
                        const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
                        deleteModal.hide();
                    } else {
                        showAlert('error', result.message || 'Error al eliminar la película');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showAlert('error', 'Error al eliminar la película');
                }
                
                movieToDeleteId = null;
            }
        });

        // Configurar preview de imagen
        function setupImagePreview() {
            const imageInput = document.getElementById('image');
            const imagePreview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');
            
            imageInput.addEventListener('input', function() {
                const url = this.value;
                if (url) {
                    previewImg.src = url;
                    imagePreview.style.display = 'block';
                    
                    previewImg.onerror = function() {
                        imagePreview.style.display = 'none';
                    };
                } else {
                    imagePreview.style.display = 'none';
                }
            });
        }

        // Función auxiliar para mostrar alertas
        function showAlert(type, message) {
            const alertClass = type === 'error' ? 'alert-danger' : 'alert-success';
            const alertHtml = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    <i class="fas fa-${type === 'error' ? 'exclamation-triangle' : 'check-circle'}"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            const alertContainer = document.getElementById('alertContainer');
            alertContainer.insertAdjacentHTML('afterbegin', alertHtml);
            
            // Auto-remover después de 5 segundos
            setTimeout(() => {
                const alert = alertContainer.querySelector('.alert');
                if (alert) {
                    alert.remove();
                }
            }, 5000);
        }

        // Manejar envío del formulario
        document.getElementById('movieForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            try {
                const response = await fetch('../../backend/crud_movies.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    const action = formData.get('action');
                    const message = action === 'create' ? 'Película agregada exitosamente' : 'Película actualizada exitosamente';
                    showAlert('success', message);
                    loadMoviesAdmin(); // Recargar la tabla
                    
                    // Cerrar modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('movieModal'));
                    modal.hide();
                } else {
                    showAlert('error', result.message || 'Error al procesar la película');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('error', 'Error al procesar la solicitud');
            }
        });
    </script>
</body>
</html>
