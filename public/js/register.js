 // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordEye = document.getElementById('password-eye');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordEye.classList.remove('fa-eye');
                passwordEye.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordEye.classList.remove('fa-eye-slash');
                passwordEye.classList.add('fa-eye');
            }
        }
        
        function toggleConfirmPassword() {
            const confirmPasswordInput = document.getElementById('confirm_password');
            const confirmPasswordEye = document.getElementById('confirm-password-eye');
            
            if (confirmPasswordInput.type === 'password') {
                confirmPasswordInput.type = 'text';
                confirmPasswordEye.classList.remove('fa-eye');
                confirmPasswordEye.classList.add('fa-eye-slash');
            } else {
                confirmPasswordInput.type = 'password';
                confirmPasswordEye.classList.remove('fa-eye-slash');
                confirmPasswordEye.classList.add('fa-eye');
            }
        }

        // Dynamic kecamatan based on kota
        document.getElementById('kota').addEventListener('change', function() {
            const kota = this.value;
            const kecamatanSelect = document.getElementById('kecamatan');
            
            // Clear existing options
            kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
            
            if (kota === 'Banjarmasin') {
                const banjarmasinKecamatan = [
                    'Banjarmasin Utara', 'Banjarmasin Selatan', 'Banjarmasin Tengah',
                    'Banjarmasin Timur', 'Banjarmasin Barat'
                ];
                
                banjarmasinKecamatan.forEach(kec => {
                    const option = document.createElement('option');
                    option.value = kec;
                    option.textContent = kec;
                    kecamatanSelect.appendChild(option);
                });
            } else if (kota === 'Banjarbaru') {
                const banjarbaruKecamatan = [
                    'Banjarbaru Utara', 'Banjarbaru Selatan', 'Landasan Ulin',
                    'Liang Anggang', 'Cempaka'
                ];
                
                banjarbaruKecamatan.forEach(kec => {
                    const option = document.createElement('option');
                    option.value = kec;
                    option.textContent = kec;
                    kecamatanSelect.appendChild(option);
                });
            }
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password.length < 6) {
                e.preventDefault();
                alert('Password minimal 6 karakter!');
                return;
            }
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Password dan konfirmasi password tidak sama!');
                return;
            }
        });