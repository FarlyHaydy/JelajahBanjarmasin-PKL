// DOM Elements
const mobileToggle = document.getElementById('mobileToggle');
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');
const navLinks = document.querySelectorAll('.nav-link[data-menu]');

// Mobile Menu Toggle Functionality
function toggleSidebar() {
    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');
    document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
}

function closeSidebar() {
    sidebar.classList.remove('active');
    overlay.classList.remove('active');
    document.body.style.overflow = '';
}

// Event Listeners
if (mobileToggle) {
    mobileToggle.addEventListener('click', toggleSidebar);
}

if (overlay) {
    overlay.addEventListener('click', closeSidebar);
}

// Navigation Link Active State
navLinks.forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Check if this is a main menu item with submenu
        const parentNavItem = this.closest('.nav-item');
        const hasSubmenu = parentNavItem.classList.contains('has-submenu');
        
        if (hasSubmenu) {
            // Toggle submenu for Dashboard Admin
            parentNavItem.classList.toggle('active');
        } else {
            // Remove active class from all nav items
            document.querySelectorAll('.nav-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // Add active class to clicked nav item
            parentNavItem.classList.add('active');
        }
        
        // Close sidebar on mobile after selection
        if (window.innerWidth < 992) {
            closeSidebar();
        }
    });
});

// Submenu link handling
document.querySelectorAll('.submenu-link').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Remove active class from all submenu items
        document.querySelectorAll('.submenu-item').forEach(item => {
            item.classList.remove('active');
        });
        
        // Add active class to clicked submenu item
        this.closest('.submenu-item').classList.add('active');
        
        // Handle content switching based on submenu
        const menuText = this.querySelector('span').textContent.trim();
        loadSubmenuContent(menuText);
        
        // Close sidebar on mobile after selection
        if (window.innerWidth < 992) {
            closeSidebar();
        }
    });
});

// Function to load submenu content
function loadSubmenuContent(menuType) {
    const mainContent = document.getElementById('mainContent');
    
    switch(menuType) {
        case 'Buat Postingan':
            loadBuatPostinganContent();
            break;
        case 'Edit Postingan':
            loadEditPostinganContent();
            break;
        case 'Hapus Postingan':
            loadHapusPostinganContent();
            break;
        default:
            loadBuatPostinganContent();
    }
}

function loadBuatPostinganContent() {
    const mainContent = document.getElementById('mainContent');
    mainContent.innerHTML = `
        <div class="page-content">
            <div class="page-header">
                <h2>Buat Postingan</h2>
            </div>
            
            <form class="posting-form">
                <!-- Nama Destinasi -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-building me-2"></i>Nama Destinasi
                    </label>
                    <div class="input-with-text">
                        <input type="text" class="form-control">
                    </div>
                </div>
                
                <!-- Alamat -->
                <div class="form-group">
                    <label class="form-label">Alamat</label>
                    <textarea class="form-control" rows="4"></textarea>
                </div>
                
                <!-- Kategori -->
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <select class="form-select">
                        <option selected>Rekreasi</option>
                        <option value="wisata">Wisata</option>
                        <option value="kuliner">Kuliner</option>
                        <option value="budaya">Budaya</option>
                        <option value="religi">Religi</option>
                    </select>
                </div>
                
                <!-- Detail -->
                <div class="form-group">
                    <label class="form-label">Detail</label>
                    <textarea class="form-control detail-textarea" rows="6"></textarea>
                </div>
                
                <!-- Gambar -->
                <div class="form-group">
                    <label class="form-label">GAMBAR</label>
                    <div class="file-upload-area">
                        <div class="upload-placeholder">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Jika ingin upload foto, klik "Browse File..." atau "Pilih File..."</p>
                            <button type="button" class="btn btn-outline-light btn-sm">Browse File...</button>
                        </div>
                    </div>
                </div>
                
                <!-- Deskripsi -->
                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea class="form-control description-textarea" rows="8"></textarea>
                </div>
                
                <!-- Submit Button -->
                <div class="form-submit">
                    <button type="submit" class="btn btn-primary btn-lg">Buat</button>
                </div>
            </form>
        </div>
    `;
}

function loadEditPostinganContent() {
    const mainContent = document.getElementById('mainContent');
    mainContent.innerHTML = `
        <div class="page-content">
            <div class="page-header">
                <h2>Edit Postingan</h2>
            </div>
            <div class="posting-form">
                <p style="color: #ffffff; text-align: center; font-size: 1.2rem;">
                    Halaman Edit Postingan akan ditampilkan di sini
                </p>
            </div>
        </div>
    `;
}

function loadHapusPostinganContent() {
    const mainContent = document.getElementById('mainContent');
    mainContent.innerHTML = `
        <div class="page-content">
            <div class="page-header">
                <h2>Hapus Postingan</h2>
            </div>
            <div class="posting-form">
                <p style="color: #ffffff; text-align: center; font-size: 1.2rem;">
                    Halaman Hapus Postingan akan ditampilkan di sini
                </p>
            </div>
        </div>
    `;
}

// Handle window resize
window.addEventListener('resize', function() {
    if (window.innerWidth >= 992) {
        closeSidebar();
    }
});

// Close sidebar when clicking outside on mobile
document.addEventListener('click', function(e) {
    if (window.innerWidth < 992) {
        const isClickInsideSidebar = sidebar.contains(e.target);
        const isClickOnToggle = mobileToggle.contains(e.target);
        
        if (!isClickInsideSidebar && !isClickOnToggle && sidebar.classList.contains('active')) {
            closeSidebar();
        }
    }
});

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    // Close sidebar with Escape key
    if (e.key === 'Escape' && sidebar.classList.contains('active')) {
        closeSidebar();
    }
});

// Initialize smooth transitions
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth scrolling to all anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
});