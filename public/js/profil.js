// Toggle sidebar pada mobile
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    const menuBtn = document.querySelector('.mobile-menu-btn');

    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');
    
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
    const menuBtn = document.querySelector('.mobile-menu-btn');
    
    sidebar.classList.remove('active');
    overlay.classList.remove('active');
    menuBtn.style.display = 'block';
}

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

// Form validation dan submission dengan UI feedback
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('profileForm');
    const saveBtn = document.querySelector('.save-btn');
    
    if (form && saveBtn) {
        form.addEventListener('submit', function(e) {
            // Validasi form sebelum submit
            if (!validateForm()) {
                e.preventDefault();
                return false;
            }
            
            // Konfirmasi sebelum submit
            if (!confirm('Apakah Anda yakin ingin menyimpan perubahan profil?')) {
                e.preventDefault();
                return false;
            }
            
            // UI feedback saat submit (tidak prevent default, biarkan form submit normal)
            showLoadingState();
            
            // Form akan submit normal ke server
            // Server akan handle redirect dan response
        });
    }
});

// Validasi form
function validateForm() {
    let isValid = true;
    const form = document.getElementById('profileForm');
    
    // Reset previous validation states
    const inputs = form.querySelectorAll('.form-control, .form-select');
    inputs.forEach(input => {
        input.classList.remove('is-invalid', 'is-valid');
    });
    
    // Validasi nama asli
    const namaAsli = form.querySelector('input[name="nama_asli"]');
    if (!namaAsli.value.trim()) {
        showFieldError(namaAsli, 'Nama lengkap wajib diisi');
        isValid = false;
    } else if (namaAsli.value.trim().length < 2) {
        showFieldError(namaAsli, 'Nama lengkap minimal 2 karakter');
        isValid = false;
    } else {
        showFieldSuccess(namaAsli);
    }
    
    // Validasi email
    const email = form.querySelector('input[name="email"]');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email.value.trim()) {
        showFieldError(email, 'Email wajib diisi');
        isValid = false;
    } else if (!emailRegex.test(email.value)) {
        showFieldError(email, 'Format email tidak valid');
        isValid = false;
    } else {
        showFieldSuccess(email);
    }
    
    // Validasi alamat
    const alamat = form.querySelector('input[name="alamat"]');
    if (!alamat.value.trim()) {
        showFieldError(alamat, 'Alamat wajib diisi');
        isValid = false;
    } else if (alamat.value.trim().length < 5) {
        showFieldError(alamat, 'Alamat minimal 5 karakter');
        isValid = false;
    } else {
        showFieldSuccess(alamat);
    }
    
    // Validasi nomor telepon
    const nomorTelepon = form.querySelector('input[name="nomor_telepon"]');
    if (!nomorTelepon.value.trim()) {
        showFieldError(nomorTelepon, 'Nomor telepon wajib diisi');
        isValid = false;
    } else if (nomorTelepon.value.trim().length < 10) {
        showFieldError(nomorTelepon, 'Nomor telepon minimal 10 digit');
        isValid = false;
    } else {
        showFieldSuccess(nomorTelepon);
    }
    
    // Validasi jenis kelamin
    const jenisKelamin = form.querySelector('select[name="jenis_kelamin"]');
    if (!jenisKelamin.value) {
        showFieldError(jenisKelamin, 'Jenis kelamin wajib dipilih');
        isValid = false;
    } else {
        showFieldSuccess(jenisKelamin);
    }
    
    // Validasi kota
    const kota = form.querySelector('select[name="kota"]');
    if (!kota.value) {
        showFieldError(kota, 'Kota wajib dipilih');
        isValid = false;
    } else {
        showFieldSuccess(kota);
    }
    
    // Validasi kecamatan
    const kecamatan = form.querySelector('select[name="kecamatan"]');
    if (!kecamatan.value) {
        showFieldError(kecamatan, 'Kecamatan wajib dipilih');
        isValid = false;
    } else {
        showFieldSuccess(kecamatan);
    }
    
    // Validasi password (opsional, tapi jika diisi harus minimal 6 karakter)
    const password = form.querySelector('input[name="password"]');
    if (password.value && password.value.length < 6) {
        showFieldError(password, 'Password minimal 6 karakter');
        isValid = false;
    } else if (password.value) {
        showFieldSuccess(password);
    }
    
    return isValid;
}

// Show field error
function showFieldError(field, message) {
    field.classList.add('is-invalid');
    field.classList.remove('is-valid');
    
    const feedback = field.parentNode.querySelector('.invalid-feedback');
    if (feedback) {
        feedback.textContent = message;
    }
}

// Show field success
function showFieldSuccess(field) {
    field.classList.add('is-valid');
    field.classList.remove('is-invalid');
}

// Show loading state saat submit
function showLoadingState() {
    const saveBtn = document.querySelector('.save-btn');
    if (saveBtn) {
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
        saveBtn.disabled = true;
        saveBtn.classList.add('loading');
    }
}

// Reset button state (dipanggil jika ada error)
function resetButtonState() {
    const saveBtn = document.querySelector('.save-btn');
    if (saveBtn) {
        saveBtn.innerHTML = '<i class="fas fa-save"></i> Simpan Perubahan';
        saveBtn.disabled = false;
        saveBtn.classList.remove('loading', 'success');
    }
}

// Show success state (dipanggil jika berhasil)
function showSuccessState() {
    const saveBtn = document.querySelector('.save-btn');
    if (saveBtn) {
        saveBtn.innerHTML = '<i class="fas fa-check"></i> Tersimpan!';
        saveBtn.classList.remove('loading');
        saveBtn.classList.add('success');
        
        // Reset setelah 2 detik
        setTimeout(() => {
            resetButtonState();
        }, 2000);
    }
}

// Real-time validation saat user mengetik
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('profileForm');
    if (form) {
        const inputs = form.querySelectorAll('input[required], select[required]');
        
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                // Validasi field saat kehilangan focus
                validateSingleField(this);
            });
            
            input.addEventListener('input', function() {
                // Hapus error state saat user mulai mengetik
                if (this.classList.contains('is-invalid')) {
                    this.classList.remove('is-invalid');
                }
            });
        });
    }
});

// Validasi single field
function validateSingleField(field) {
    const fieldName = field.getAttribute('name');
    
    switch(fieldName) {
        case 'nama_asli':
            if (!field.value.trim()) {
                showFieldError(field, 'Nama lengkap wajib diisi');
            } else if (field.value.trim().length < 2) {
                showFieldError(field, 'Nama lengkap minimal 2 karakter');
            } else {
                showFieldSuccess(field);
            }
            break;
            
        case 'email':
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!field.value.trim()) {
                showFieldError(field, 'Email wajib diisi');
            } else if (!emailRegex.test(field.value)) {
                showFieldError(field, 'Format email tidak valid');
            } else {
                showFieldSuccess(field);
            }
            break;
            
        case 'alamat':
            if (!field.value.trim()) {
                showFieldError(field, 'Alamat wajib diisi');
            } else if (field.value.trim().length < 5) {
                showFieldError(field, 'Alamat minimal 5 karakter');
            } else {
                showFieldSuccess(field);
            }
            break;
            
        case 'nomor_telepon':
            if (!field.value.trim()) {
                showFieldError(field, 'Nomor telepon wajib diisi');
            } else if (field.value.trim().length < 10) {
                showFieldError(field, 'Nomor telepon minimal 10 digit');
            } else {
                showFieldSuccess(field);
            }
            break;
            
        case 'jenis_kelamin':
        case 'kota':
        case 'kecamatan':
            if (!field.value) {
                showFieldError(field, field.getAttribute('data-error') || 'Field ini wajib dipilih');
            } else {
                showFieldSuccess(field);
            }
            break;
            
        case 'password':
            if (field.value && field.value.length < 6) {
                showFieldError(field, 'Password minimal 6 karakter');
            } else if (field.value) {
                showFieldSuccess(field);
            }
            break;
    }
}