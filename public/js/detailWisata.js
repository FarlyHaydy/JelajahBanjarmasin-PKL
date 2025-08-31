// Change main hero image when thumbnail is clicked
        function changeMainImage(thumbnail) {
            const mainImage = document.getElementById('mainImage');
            const newImageSrc = thumbnail.getAttribute('data-image');
            
            // Remove active class from all thumbnails
            document.querySelectorAll('.thumbnail').forEach(thumb => {
                thumb.classList.remove('active');
            });
            
            // Add active class to clicked thumbnail
            thumbnail.classList.add('active');
            
            // Fade effect for main image change
            mainImage.style.opacity = '0.5';
            
            setTimeout(() => {
                mainImage.src = newImageSrc;
                mainImage.style.opacity = '1';
            }, 150);
        }

        // Show image in modal when hero image is clicked
        function showImageModal(imageSrc) {
            const modal = new bootstrap.Modal(document.getElementById('imageModal'));
            const modalImage = document.getElementById('modalImage');
            
            modalImage.src = imageSrc;
            modal.show();
        }

        // Change modal image
        function changeModalImage(imageSrc) {
            const modalImage = document.getElementById('modalImage');
            modalImage.style.opacity = '0.5';
            
            setTimeout(() => {
                modalImage.src = imageSrc;
                modalImage.style.opacity = '1';
            }, 150);
        }

        // Bookmark functionality
        function toggleBookmark() {
            const bookmarkIcon = document.getElementById('bookmarkIcon');
            
            if (bookmarkIcon.classList.contains('far')) {
                bookmarkIcon.classList.remove('far');
                bookmarkIcon.classList.add('fas');
            } else {
                bookmarkIcon.classList.remove('fas');
                bookmarkIcon.classList.add('far');
            }
        }

        // Show all photos
        function showAllPhotos() {
            showImageModal(document.getElementById('mainImage').src);
        }

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });