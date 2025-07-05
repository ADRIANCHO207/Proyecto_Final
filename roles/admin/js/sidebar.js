// JavaScript para el sidebar moderno
class ModernSidebar {
  constructor() {
    this.sidebar = document.getElementById("sidebar")
    this.toggleBtn = document.getElementById("toggleBtn")
    this.overlay = document.getElementById("sidebarOverlay")
    this.isExpanded = false
    this.isMobile = window.innerWidth <= 768

    this.init()
  }

  init() {
    this.setupEventListeners()
    this.handleResize()
    this.setActiveLink()
  }

  setupEventListeners() {
    // Toggle button
    if (this.toggleBtn) {
      this.toggleBtn.addEventListener("click", () => this.toggle())
    }

    // Hover events para desktop
    if (!this.isMobile) {
      this.sidebar.addEventListener("mouseenter", () => this.expand())
      this.sidebar.addEventListener("mouseleave", () => this.collapse())
    }

    // Overlay click para móvil
    if (this.overlay) {
      this.overlay.addEventListener("click", () => this.collapse())
    }

    // Resize handler
    window.addEventListener("resize", () => this.handleResize())

    // Escape key
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape" && this.isExpanded && this.isMobile) {
        this.collapse()
      }
    })

    // Click en enlaces para móvil
    const navLinks = this.sidebar.querySelectorAll(".nav-link")
    navLinks.forEach((link) => {
      link.addEventListener("click", () => {
        if (this.isMobile) {
          this.collapse()
        }
      })
    })
  }

  expand() {
    this.sidebar.classList.add("expanded")
    this.isExpanded = true

    if (this.isMobile && this.overlay) {
      this.overlay.classList.add("active")
      document.body.style.overflow = "hidden"
    }
  }

  collapse() {
    this.sidebar.classList.remove("expanded")
    this.isExpanded = false

    if (this.overlay) {
      this.overlay.classList.remove("active")
      document.body.style.overflow = ""
    }
  }

  toggle() {
    if (this.isExpanded) {
      this.collapse()
    } else {
      this.expand()
    }
  }

  handleResize() {
    const wasMobile = this.isMobile
    this.isMobile = window.innerWidth <= 768

    if (wasMobile !== this.isMobile) {
      if (!this.isMobile) {
        // Cambió de móvil a desktop
        this.collapse()
        document.body.style.overflow = ""
      }
    }
  }

  setActiveLink() {
    const currentPath = window.location.pathname
    const navLinks = this.sidebar.querySelectorAll(".nav-link")

    navLinks.forEach((link) => {
      link.classList.remove("active")
      const href = link.getAttribute("href")

      if (href && currentPath.includes(href.replace(".php", ""))) {
        link.classList.add("active")
      }
    })
  }

  // Método para actualizar badges dinámicamente
  updateBadge(linkSelector, count) {
    const link = this.sidebar.querySelector(linkSelector)
    if (link) {
      let badge = link.querySelector(".nav-badge")

      if (count > 0) {
        if (!badge) {
          badge = document.createElement("div")
          badge.className = "nav-badge"
          link.appendChild(badge)
        }
        badge.textContent = count > 99 ? "99+" : count
      } else if (badge) {
        badge.remove()
      }
    }
  }

  // Método para mostrar notificaciones
  showNotification(message, type = "info") {
    const notification = document.createElement("div")
    notification.className = `sidebar-notification ${type}`
    notification.innerHTML = `
      <i class="bi bi-info-circle"></i>
      <span>${message}</span>
    `

    this.sidebar.appendChild(notification)

    setTimeout(() => {
      notification.remove()
    }, 3000)
  }
}

// Inicializar el sidebar cuando el DOM esté listo
document.addEventListener("DOMContentLoaded", () => {
  window.modernSidebar = new ModernSidebar()

  // Ejemplo de uso de badges dinámicos
  // window.modernSidebar.updateBadge('a[href="alertas.php"]', 5);
  // window.modernSidebar.updateBadge('a[href="#"]:has(.bi-bell-fill)', 12);
})

// Función para actualizar el perfil del usuario
function updateUserProfile(name, role, avatarUrl) {
  const profileName = document.querySelector(".profile-name")
  const profileRole = document.querySelector(".profile-role")
  const avatarImg = document.querySelector(".avatar-img")

  if (profileName) profileName.textContent = name
  if (profileRole) profileRole.textContent = role
  if (avatarImg && avatarUrl) avatarImg.src = avatarUrl
}

// Exportar para uso global
window.updateUserProfile = updateUserProfile