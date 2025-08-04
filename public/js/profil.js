// Toggle sidebar pada mobile
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    const menuBtn = document.querySelector('.mobile-menu-btn'); // Target tombol toggle

    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');
    
    // Sembunyikan tombol toggle ketika sidebar aktif
    if (sidebar.classList.contains('active')) {
        menuBtn.style.display = 'none';
    } else {
        menuBtn.style.display = 'block';
    }
}

// Tutup sidebar
function closeSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    const menuBtn = document.querySelector('.mobile-menu-btn'); // Target tombol toggle
    
    sidebar.classList.remove('active');
    overlay.classList.remove('active');
    
    // Tampilkan tombol toggle ketika sidebar ditutup
    menuBtn.style.display = 'block';
}

        // Toggle password visibility
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.className = 'fas fa-eye-slash';
            } else {
                passwordField.type = 'password';
                eyeIcon.className = 'fas fa-eye';
            }
        }

        // Form submission
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Simulate form submission
            const saveBtn = document.querySelector('.save-btn');
            const originalText = saveBtn.textContent;
            
            saveBtn.textContent = 'Menyimpan...';
            saveBtn.disabled = true;
            
            setTimeout(() => {
                saveBtn.textContent = 'Tersimpan!';
                saveBtn.style.background = '#28a745';
                saveBtn.style.borderColor = '#28a745';
                
                setTimeout(() => {
                    saveBtn.textContent = originalText;
                    saveBtn.disabled = false;
                    saveBtn.style.background = '#3a3a3a';
                    saveBtn.style.borderColor = '#4a4a4a';
                }, 2000);
            }, 1500);
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const menuBtn = document.querySelector('.mobile-menu-btn');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(e.target) && 
                !menuBtn.contains(e.target) && 
                sidebar.classList.contains('active')) {
                closeSidebar();
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                closeSidebar();
            }
        });