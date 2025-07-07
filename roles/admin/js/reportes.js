/**
 * ReportsManager Class
 * Comprehensive report management system for vehicle fleet administration
 * Handles report generation, filtering, exporting, and user interface interactions
 */
class ReportsManager {
  constructor() {
    // Initialize class properties for state management
    this.currentReport = null;        // Currently active report type
    this.currentFilters = {};         // Applied filters for current report
    this.isLoading = false;          // Loading state flag
    this.filterTimeout = null;       // Debounce timeout for filter changes
    
    // Initialize the report system
    this.init();
  }

  /**
   * Initialize the ReportsManager
   * Sets up event listeners and default configurations
   */
  init() {
    this.setupEventListeners();
    this.setupDefaultFilters();
    this.setupAnimations();
  }

  /**
   * Set up event listeners for report interactions
   * Handles button clicks, filter changes, and modal events
   */
  setupEventListeners() {
    // Report opening buttons - attach click handlers to all report buttons
    document.querySelectorAll('[data-report-type]').forEach(button => {
      button.addEventListener('click', (e) => {
        const reportType = e.target.getAttribute('data-report-type');
        this.openReport(reportType);
      });
    });

    // Export buttons - handle different export format requests
    document.querySelectorAll('[data-export-format]').forEach(button => {
      button.addEventListener('click', (e) => {
        const format = e.target.getAttribute('data-export-format');
        this.exportCurrentReport(format);
      });
    });

    // Filter change handlers - real-time filter application with debouncing
    document.addEventListener('change', (e) => {
      if (e.target.closest('#filtrosReporte')) {
        this.handleFilterChange();
      }
    });

    // Input field handlers for text-based filters
    document.addEventListener('input', (e) => {
      if (e.target.closest('#filtrosReporte')) {
        this.handleFilterChange();
      }
    });

    // Modal reset when closed - clean up state
    const modal = document.getElementById('modalReportes');
    if (modal) {
      modal.addEventListener('hidden.bs.modal', () => {
        this.resetModal();
      });
    }
  }

  /**
   * Configure default date range filters
   * Sets up last 30 days as default time range for reports
   */
  setupDefaultFilters() {
    const today = new Date();
    const thirtyDaysAgo = new Date(today.getTime() - (30 * 24 * 60 * 60 * 1000));
    
    // Format dates for HTML date inputs (YYYY-MM-DD)
    const formatDate = (date) => {
      return date.toISOString().split('T')[0];
    };
    
    // Set default date range in filter inputs
    const startDateInput = document.getElementById('fecha_inicio');
    const endDateInput = document.getElementById('fecha_fin');
    
    if (startDateInput) startDateInput.value = formatDate(thirtyDaysAgo);
    if (endDateInput) endDateInput.value = formatDate(today);
  }

  /**
   * Set up intersection observer for card animations
   * Provides smooth entrance animations for report cards
   */
  setupAnimations() {
    const reportCards = document.querySelectorAll('.report-card');
    
    // Configurar observer para animaciones de entrada
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'translateY(0)';
        }
      });
    }, {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    });
    
    // Aplicar observer a cada tarjeta
    reportCards.forEach(card => {
      card.style.opacity = '0';
      card.style.transform = 'translateY(20px)';
      card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
      observer.observe(card);
    });
  }

  /**
   * Open a specific report type
   * @param {string} type - The type of report to open
   */
  openReport(type) {
    // Set current report state
    this.currentReport = type;
    this.currentFilters = {};
    
    // Open the Bootstrap modal
    const modal = new bootstrap.Modal(document.getElementById('modalReportes'));
    modal.show();
    
    // Set dynamic modal title based on report type
    const modalTitle = document.getElementById('modalReportesLabel');
    if (modalTitle) {
      const titles = {
        'vehiculos': 'Reporte de Vehículos',
        'mantenimientos': 'Reporte de Mantenimientos',
        'llantas': 'Reporte de Llantas',
        'soat': 'Reporte de SOAT',
        'tecnomecanica': 'Reporte de Tecnomecánica',
        'licencias': 'Reporte de Licencias',
        'alertas': 'Reporte de Alertas',
        'actividad': 'Reporte de Actividad'
      };
      modalTitle.textContent = titles[type] || 'Reporte';
    }
    
    // Generate type-specific filters
    this.generateFilters(type);
    
    // Load initial report data
    this.loadReportData(type, {});
  }

  /**
   * Generate dynamic filters based on report type
   * @param {string} type - The report type to generate filters for
   */
  generateFilters(type) {
    const filtersContainer = document.getElementById('filtrosReporte');
    if (!filtersContainer) return;
    
    // Common date range filters for all reports
    let html = `
      <div class="row g-3">
        <div class="col-md-3">
          <label class="form-label">
            <i class="bi bi-calendar me-1"></i>
            Fecha Inicio
          </label>
          <input type="date" class="form-control" id="filtro_fecha_inicio">
        </div>
        <div class="col-md-3">
          <label class="form-label">
            <i class="bi bi-calendar me-1"></i>
            Fecha Fin
          </label>
          <input type="date" class="form-control" id="filtro_fecha_fin">
        </div>
    `;
    
    // Add type-specific filters using switch statement
    html += this.getTypeSpecificFilters(type);
    
    html += `
        <div class="col-12">
          <div class="d-flex gap-2 mt-3">
            <button type="button" class="btn btn-primary" onclick="reportsManager.applyFilters()">
              <i class="bi bi-funnel me-1"></i>
              Aplicar Filtros
            </button>
            <button type="button" class="btn btn-outline-secondary" onclick="reportsManager.clearFilters()">
              <i class="bi bi-arrow-clockwise me-1"></i>
              Limpiar
            </button>
          </div>
        </div>
      </div>
    `;
    
    filtersContainer.innerHTML = html;
    
    // Restore default date filters
    this.setupDefaultFilters();
  }

  /**
   * Generate type-specific filter HTML
   * @param {string} type - The report type
   * @returns {string} HTML string for type-specific filters
   */
  getTypeSpecificFilters(type) {
    let html = '';
    
    switch(type) {
      case 'vehiculos':
        // Vehicle-specific filters: plate and status
        html += `
          <div class="col-md-3">
            <label class="form-label">
              <i class="bi bi-car-front me-1"></i>
              Placa
            </label>
            <input type="text" class="form-control" id="filtro_placa" placeholder="Placa del vehículo">
          </div>
          <div class="col-md-3">
            <label class="form-label">
              <i class="bi bi-gear me-1"></i>
              Estado
            </label>
            <select class="form-select" id="filtro_estado">
              <option value="">Todos los estados</option>
              <option value="activo">Activo</option>
              <option value="inactivo">Inactivo</option>
              <option value="mantenimiento">En Mantenimiento</option>
            </select>
          </div>
        `;
        break;
        
      case 'mantenimientos':
        // Maintenance-specific filters: plate and status
        html += `
          <div class="col-md-3">
            <label class="form-label">
              <i class="bi bi-car-front me-1"></i>
              Placa
            </label>
            <input type="text" class="form-control" id="filtro_placa" placeholder="Placa del vehículo">
          </div>
          <div class="col-md-3">
            <label class="form-label">
              <i class="bi bi-tools me-1"></i>
              Estado
            </label>
            <select class="form-select" id="filtro_estado">
              <option value="">Todos los estados</option>
              <option value="programado">Programado</option>
              <option value="en_proceso">En Proceso</option>
              <option value="completado">Completado</option>
              <option value="cancelado">Cancelado</option>
            </select>
          </div>
        `;
        break;
        
      case 'llantas':
        // Tire-specific filters: plate and status
        html += `
          <div class="col-md-3">
            <label class="form-label">
              <i class="bi bi-car-front me-1"></i>
              Placa
            </label>
            <input type="text" class="form-control" id="filtro_placa" placeholder="Placa del vehículo">
          </div>
          <div class="col-md-3">
            <label class="form-label">
              <i class="bi bi-circle me-1"></i>
              Estado
            </label>
            <select class="form-select" id="filtro_estado">
              <option value="">Todos los estados</option>
              <option value="bueno">Bueno</option>
              <option value="regular">Regular</option>
              <option value="malo">Malo</option>
              <option value="cambiar">Para Cambiar</option>
            </select>
          </div>
        `;
        break;
        
      case 'soat':
      case 'tecnomecanica':
        // SOAT and Technomechanical inspection filters: plate and status
        html += `
          <div class="col-md-3">
            <label class="form-label">
              <i class="bi bi-car-front me-1"></i>
              Placa
            </label>
            <input type="text" class="form-control" id="filtro_placa" placeholder="Placa del vehículo">
          </div>
          <div class="col-md-3">
            <label class="form-label">
              <i class="bi bi-shield-check me-1"></i>
              Estado
            </label>
            <select class="form-select" id="filtro_estado">
              <option value="">Todos los estados</option>
              <option value="vigente">Vigente</option>
              <option value="vencido">Vencido</option>
              <option value="proximo_vencer">Próximo a Vencer</option>
            </select>
          </div>
        `;
        break;
        
      case 'licencias':
        // License-specific filters: document and status
        html += `
          <div class="col-md-3">
            <label class="form-label">
              <i class="bi bi-person me-1"></i>
              Documento
            </label>
            <input type="text" class="form-control" id="filtro_documento" placeholder="Documento">
          </div>
          <div class="col-md-3">
            <label class="form-label">
              <i class="bi bi-person-badge me-1"></i>
              Estado
            </label>
            <select class="form-select" id="filtro_estado">
              <option value="">Todos los estados</option>
              <option value="vigente">Vigente</option>
              <option value="vencido">Vencido</option>
              <option value="proximo_vencer">Próximo a Vencer</option>
            </select>
          </div>
        `;
        break;
        
      case 'alertas':
        // Alert-specific filters: type and read status
        html += `
          <div class="col-md-3">
            <label class="form-label">
              <i class="bi bi-bell me-1"></i>
              Tipo
            </label>
            <select class="form-select" id="filtro_tipo">
              <option value="">Todos los tipos</option>
              <option value="SOAT">SOAT</option>
              <option value="tecnomecanica">Tecnomecánica</option>
              <option value="mantenimiento">Mantenimiento</option>
              <option value="licencia">Licencia</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">
              <i class="bi bi-eye me-1"></i>
              Leído
            </label>
            <select class="form-select" id="filtro_leido">
              <option value="">Todos</option>
              <option value="si">Sí</option>
              <option value="no">No</option>
            </select>
          </div>
        `;
        break;
        
      case 'actividad':
        // Activity-specific filters: activity type
        html += `
          <div class="col-md-6">
            <label class="form-label">
              <i class="bi bi-activity me-1"></i>
              Tipo de Actividad
            </label>
            <select class="form-select" id="filtro_tipo">
              <option value="">Todas las actividades</option>
              <option value="registro">Registros</option>
              <option value="mantenimiento">Mantenimientos</option>
              <option value="soat">SOAT</option>
              <option value="tecnomecanica">Tecnomecánica</option>
            </select>
          </div>
        `;
        break;
    }
    
    return html;
  }

  /**
   * Load report data from server
   * @param {string} type - Report type
   * @param {Object} filters - Applied filters
   */
  loadReportData(type, filters) {
    // Prevent multiple simultaneous requests
    if (this.isLoading) return;
    
    this.isLoading = true;
    const content = document.getElementById('contenidoReporte');
    
    // Show loading animation with shimmer effect
    content.innerHTML = this.getLoadingHTML();
    
    // Prepare request parameters
    const params = new URLSearchParams({
      tipo: type,
      filtros: JSON.stringify(filters)
    });
    
    // Fetch data from server
    fetch(`reportes_ajax.php?${params}`)
      .then(response => {
        if (!response.ok) {
          throw new Error('Error en la respuesta del servidor');
        }
        return response.json();
      })
      .then(data => {
        this.isLoading = false;
        if (data.success) {
          // Generate and display report table
          content.innerHTML = this.generateReportTable(data.datos, type);
          this.animateTableRows();
        } else {
          // Display error message
          content.innerHTML = this.getErrorHTML(data.message || 'Error al cargar los datos');
        }
      })
      .catch(error => {
        this.isLoading = false;
        console.error('Error:', error);
        content.innerHTML = this.getErrorHTML('Error de conexión. Por favor, intente nuevamente.');
      });
  }

  /**
   * Generate loading HTML with shimmer effect
   * @returns {string} Loading HTML
   */
  getLoadingHTML() {
    return `
      <div class="loading-container">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Cargando...</span>
        </div>
        <div class="loading-text">Cargando datos del reporte...</div>
        <div class="mt-4 w-100">
          <div class="loading-shimmer" style="height: 20px; border-radius: 4px; margin-bottom: 10px;"></div>
          <div class="loading-shimmer" style="height: 20px; border-radius: 4px; margin-bottom: 10px; width: 80%;"></div>
          <div class="loading-shimmer" style="height: 20px; border-radius: 4px; width: 60%;"></div>
        </div>
      </div>
    `;
  }

  /**
   * Generate error HTML
   * @param {string} message - Error message
   * @returns {string} Error HTML
   */
  getErrorHTML(message) {
    return `
      <div class="alert alert-danger d-flex align-items-center" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <div>${message}</div>
      </div>
    `;
  }

  /**
   * Generate report table HTML
   * @param {Array} data - Report data
   * @param {string} type - Report type
   * @returns {string} Table HTML
   */
  generateReportTable(data, type) {
    // Handle empty data
    if (!data || data.length === 0) {
      return `
        <div class="no-data-container">
          <i class="bi bi-inbox"></i>
          <h5>No hay datos disponibles</h5>
          <p>No se encontraron registros con los filtros aplicados.</p>
          <button class="btn btn-outline-primary mt-3" onclick="reportsManager.clearFilters()">
            <i class="bi bi-arrow-clockwise me-1"></i>
            Limpiar Filtros
          </button>
        </div>
      `;
    }
    
    let html = `
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
    `;
    
    // Generate table headers dynamically
    Object.keys(data[0]).forEach(key => {
      const displayName = this.formatColumnName(key);
      html += `<th><i class="bi bi-sort-alpha-down me-1"></i>${displayName}</th>`;
    });
    
    html += `
            </tr>
          </thead>
          <tbody>
    `;
    
    // Generate table rows with staggered animation
    data.forEach((row, index) => {
      html += `<tr style="animation-delay: ${index * 0.05}s">`;
      Object.entries(row).forEach(([key, value]) => {
        const formattedValue = this.formatCellValue(key, value);
        const cellClass = this.getCellClass(key, value);
        html += `<td class="${cellClass}">${formattedValue}</td>`;
      });
      html += '</tr>';
    });
    
    html += `
          </tbody>
        </table>
      </div>
      <div class="mt-4 d-flex justify-content-between align-items-center">
        <div class="text-muted">
          <i class="bi bi-info-circle me-1"></i>
          Total de registros: <strong>${data.length}</strong>
        </div>
        <div>
          <button class="btn btn-outline-primary btn-sm me-2" onclick="reportsManager.refreshReport()">
            <i class="bi bi-arrow-clockwise me-1"></i>
            Actualizar
          </button>
        </div>
      </div>
    `;
    
    return html;
  }

  /**
   * Format column names for display
   * @param {string} key - Column key
   * @returns {string} Formatted column name
   */
  formatColumnName(key) {
    const columnNames = {
      placa: 'Placa',
      marca: 'Marca',
      modelo: 'Modelo',
      anio: 'Año',
      color: 'Color',
      estado_vehiculo: 'Estado',
      fecha_registro: 'Fecha Registro',
      fecha_expedicion: 'Fecha Expedición',
      fecha_vencimiento: 'Fecha Vencimiento',
      numero_poliza: 'Número Póliza',
      aseguradora: 'Aseguradora',
      dias_restantes: 'Días Restantes',
      usuario_responsable: 'Usuario Responsable'
    };
    
    return columnNames[key] || key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
  }

  /**
   * Format cell values for display
   * @param {string} key - Column key
   * @param {*} value - Cell value
   * @returns {string} Formatted value
   */
  formatCellValue(key, value) {
    if (value === null || value === undefined) return '-';
    
    // Format dates
    if (key.includes('fecha') && value) {
      const date = new Date(value);
      return date.toLocaleDateString('es-ES');
    }
    
    // Format remaining days with contextual messages
    if (key.includes('dias_restantes') && typeof value === 'number') {
      if (value < 0) return `${Math.abs(value)} días vencido`;
      if (value === 0) return 'Vence hoy';
      return `${value} días`;
    }
    
    return value;
  }

  /**
   * Get CSS classes for cells based on content
   * @param {string} key - Column key
   * @param {*} value - Cell value
   * @returns {string} CSS classes
   */
  getCellClass(key, value) {
    let classes = '';
    
    // Status-based styling
    if (key === 'estado' || key === 'estado_vehiculo') {
      if (typeof value === 'string') {
        const lowerValue = value.toLowerCase();
        if (lowerValue === 'vigente' || lowerValue === 'activo') {
          classes += 'text-success fw-bold';
        } else if (lowerValue === 'vencido' || lowerValue === 'inactivo') {
          classes += 'text-danger fw-bold';
        } else if (lowerValue.includes('próximo') || lowerValue === 'mantenimiento') {
          classes += 'text-warning fw-bold';
        }
      }
    }
    
    // Days remaining styling with color coding
    if (key === 'dias_restantes' && typeof value === 'number') {
      if (value < 0) classes += 'text-danger fw-bold';        // Expired
      else if (value <= 30) classes += 'text-warning fw-bold'; // Expiring soon
      else classes += 'text-success';                          // Valid
    }
    
    return classes;
  }

  /**
   * Animate table rows with staggered entrance
   */
  animateTableRows() {
    const rows = document.querySelectorAll('#contenidoReporte tbody tr');
    rows.forEach((row, index) => {
      row.style.opacity = '0';
      row.style.transform = 'translateX(-20px)';
      row.style.transition = 'all 0.3s ease-out';
      
      setTimeout(() => {
        row.style.opacity = '1';
        row.style.transform = 'translateX(0)';
      }, index * 50);
    });
  }

  /**
   * Handle filter changes with debouncing
   */
  handleFilterChange() {
    // Debounce to avoid multiple rapid calls
    clearTimeout(this.filterTimeout);
    this.filterTimeout = setTimeout(() => {
      this.applyFilters();
    }, 500);
  }

  /**
   * Apply current filters to the report
   */
  applyFilters() {
    if (!this.currentReport) return;
    
    // Collect filter values from form inputs
    const filters = {};
    const inputs = document.querySelectorAll('#filtrosReporte input, #filtrosReporte select');
    
    inputs.forEach(input => {
      if (input.value.trim()) {
        const key = input.id.replace('filtro_', '');
        filters[key] = input.value.trim();
      }
    });
    
    this.currentFilters = filters;
    this.loadReportData(this.currentReport, filters);
  }

  /**
   * Clear all applied filters
   */
  clearFilters() {
    const inputs = document.querySelectorAll('#filtrosReporte input, #filtrosReporte select');
    inputs.forEach(input => {
      input.value = '';
    });
    
    this.currentFilters = {};
    if (this.currentReport) {
      this.loadReportData(this.currentReport, {});
    }
  }

  /**
   * Refresh current report data
   */
  refreshReport() {
    if (this.currentReport) {
      this.loadReportData(this.currentReport, this.currentFilters);
    }
  }

  /**
   * Export report in specified format
   * @param {string} type - Report type
   * @param {string} format - Export format (pdf, excel, csv)
   * @param {Object} filters - Applied filters
   */
  exportReport(type, format, filters = {}) {
    // Build URL parameters for export request
    const params = new URLSearchParams({
      exportar: '1',
      tipo: type,
      formato: format
    });
    
    // Add each filter as a separate parameter
    Object.entries(filters).forEach(([key, value]) => {
      params.append(`filtros[${key}]`, value);
    });
    
    // Create temporary download link
    const link = document.createElement('a');
    link.href = `reportes.php?${params.toString()}`;
    link.target = '_blank';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    // Show export notification
    this.showNotification(`Exportando reporte en formato ${format.toUpperCase()}...`, 'info');
  }

  /**
   * Export current report with applied filters
   * @param {string} format - Export format
   */
  exportCurrentReport(format) {
    if (!this.currentReport) return;
    this.exportReport(this.currentReport, format, this.currentFilters);
  }

  /**
   * Reset modal state when closed
   */
  resetModal() {
    this.currentReport = null;
    this.currentFilters = {};
    this.isLoading = false;
    
    const content = document.getElementById('contenidoReporte');
    if (content) {
      content.innerHTML = '';
    }
  }

  /**
   * Update statistics with animation
   */
  updateStatistics() {
    // Animate statistics cards before reload
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach((card, index) => {
      setTimeout(() => {
        card.style.transform = 'scale(0.95)';
        setTimeout(() => {
          card.style.transform = 'scale(1)';
        }, 150);
      }, index * 100);
    });
    
    // Reload page after animation
    setTimeout(() => {
      location.reload();
    }, 1000);
  }

  /**
   * Show notification to user
   * @param {string} message - Notification message
   * @param {string} type - Notification type (success, error, info)
   */
  showNotification(message, type = 'info') {
    const alertClass = type === 'success' ? 'success' : type === 'error' ? 'error' : 'info';
    const iconClass = type === 'success' ? 'bi-check-circle-fill' : 
                     type === 'error' ? 'bi-exclamation-triangle-fill' : 
                     'bi-info-circle-fill';
    
    const notification = document.createElement('div');
    notification.className = `notification alert ${alertClass} alert-dismissible fade show`;
    notification.innerHTML = `
      <div class="d-flex align-items-center">
        <i class="bi ${iconClass} me-2"></i>
        <div class="flex-grow-1">${message}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
      if (notification.parentNode) {
        notification.classList.remove('show');
        setTimeout(() => {
          notification.remove();
        }, 150);
      }
    }, 5000);
  }
}

// Initialize the manager when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  window.reportsManager = new ReportsManager();
});

// Global functions for compatibility with existing code
function abrirReporte(tipo) {
  if (window.reportsManager) {
    window.reportsManager.openReport(tipo);
  }
}

function exportarReporte(tipo, formato) {
  if (window.reportsManager) {
    window.reportsManager.exportReport(tipo, formato);
  }
}

function aplicarFiltrosReporte() {
  if (window.reportsManager) {
    window.reportsManager.applyFilters();
  }
}

function limpiarFiltrosReporte() {
  if (window.reportsManager) {
    window.reportsManager.clearFilters();
  }
}

function exportarReporteActual(formato) {
  if (window.reportsManager) {
    window.reportsManager.exportCurrentReport(formato);
  }
}

function actualizarEstadisticas() {
  if (window.reportsManager) {
    window.reportsManager.updateStatistics();
  }
}

/**
 * Load vehicle states dynamically from server
 * Uses jQuery AJAX for compatibility with existing code
 */
function cargarEstadosVehiculo() {
  $.ajax({
    url: 'reportes_ajax.php',
    type: 'POST',
    data: { accion: 'obtener_estados_vehiculo' },
    dataType: 'json',
    success: function(estados) {
      var select = $('#estado_vehiculo_filter');
      select.empty();
      select.append('<option value="">Todos los estados</option>');
      estados.forEach(function(estado) {
        select.append(`<option value="${estado}">${estado}</option>`);
      });
    }
  });
}

// Initialize vehicle states when page loads
$(document).ready(function() {
  cargarEstadosVehiculo();
});

