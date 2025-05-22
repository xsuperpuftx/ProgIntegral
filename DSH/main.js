document.addEventListener('DOMContentLoaded', function() {
    /// menu de hamburguesa
    // Selectores
    const burger = document.querySelector('.burger');
    const navLinks = document.querySelector('.nav-links');
    const navItems = document.querySelectorAll('.nav-links li a');

    function toggleMenu() {
        navLinks.classList.toggle('active');
        burger.classList.toggle('toggle');
        document.body.style.overflow = navLinks.classList.contains('active') ? 'hidden' : '';
    }

    burger.addEventListener('click', toggleMenu);

    navItems.forEach(item => {
        item.addEventListener('click', () => {
            if (navLinks.classList.contains('active')) toggleMenu();
            if (item.getAttribute('href').startsWith('#')) {
                document.querySelector(item.getAttribute('href')).scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    const carouselContainer = document.querySelector('.testimonials-carousel');
    
    if (carouselContainer) {
        let testimonials = [];
        let currentIndex = 0;
        let carouselInterval;

        // Cargar testimonios desde JSON
        async function loadTestimonials() {
            try {
                const response = await fetch('testimonios.json');
                if (!response.ok) throw new Error('Error al cargar testimonios');
                
                testimonials = await response.json();
                initCarousel();
            } catch (error) {
                console.error('Error:', error);
                // Datos de respaldo
                testimonials = [
                    {
                        "id": 1,
                        "text": "Excelente servicio profesional. Resolvieron mi caso de manera rápida y eficiente.",
                        "author": "Juan Pérez"
                    },
                    {
                        "id": 2,
                        "text": "Muy satisfecho con el asesoramiento recibido. Los recomiendo totalmente.",
                        "author": "María González"
                    },
                    {
                        id: 3,
                        text: "El equipo de DSH Consultores demostró gran conocimiento y profesionalismo.",
                        author: "Carlos Rodríguez"
                    }
                ];
                initCarousel();
            }
        }

        // Inicializar carrusel 
        function initCarousel() {
            renderTestimonials();
            renderIndicators();
            startCarousel();
            
            document.querySelector('.prev')?.addEventListener('click', () => navigate('prev'));
            document.querySelector('.next')?.addEventListener('click', () => navigate('next'));
            
            carouselContainer.addEventListener('mouseenter', pauseCarousel);
            carouselContainer.addEventListener('mouseleave', startCarousel);
        }

        function renderTestimonials() {
            const testimonialsHTML = testimonials.map((testimonial, index) => `
                <div class="testimonial ${index === 0 ? 'active' : ''}">
                    <p class="testimonial-text">"${testimonial.text}"</p>
                    <p class="testimonial-author">- ${testimonial.author}</p>
                </div>
            `).join('');
            
            carouselContainer.insertAdjacentHTML('afterbegin', testimonialsHTML);
        }

        function renderIndicators() {
            const indicatorsContainer = document.createElement('div');
            indicatorsContainer.className = 'carousel-indicators';
            
            testimonials.forEach((_, index) => {
                const indicator = document.createElement('span');
                if (index === 0) indicator.classList.add('active');
                indicator.addEventListener('click', () => goToTestimonial(index));
                indicatorsContainer.appendChild(indicator);
            });
            
            carouselContainer.appendChild(indicatorsContainer);
        }

        // funcionalidad de flechitas
        function navigate(direction) {
            resetInterval();
            currentIndex = direction === 'next' 
                ? (currentIndex + 1) % testimonials.length 
                : (currentIndex - 1 + testimonials.length) % testimonials.length;
            updateActiveTestimonial();
        }

        function goToTestimonial(index) {
            resetInterval();
            currentIndex = index;
            updateActiveTestimonial();
        }

        function updateActiveTestimonial() {
            document.querySelectorAll('.testimonial').forEach((item, index) => {
                item.classList.remove('active');
                if (index === currentIndex) {
                    setTimeout(() => item.classList.add('active'), 10);
                }
            });

            document.querySelectorAll('.carousel-indicators span').forEach((indicator, index) => {
                indicator.classList.toggle('active', index === currentIndex);
            });
        }

        // intervalo de testimonios
        function startCarousel() {
            carouselInterval = setInterval(() => navigate('next'), 8000);
        }

        function pauseCarousel() {
            clearInterval(carouselInterval);
        }

        function resetInterval() {
            pauseCarousel();
            startCarousel();
        }

        // Iniciar carga
        loadTestimonials();
    }

    //validacion del telefono
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            const errorElement = this.nextElementSibling;
            if (errorElement && errorElement.classList.contains('error-message')) {
                errorElement.style.display = (this.value.length >= 10 && this.value.length <= 15) ? 'none' : 'block';
            }
        });
    }

    // validacion del formaulrio
    const contactForm = document.getElementById('contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = {
                name: document.getElementById('name').value.trim(),
                email: document.getElementById('email').value.trim(),
                phone: document.getElementById('phone').value.trim(),
                service: document.getElementById('service').value,
                message: document.getElementById('message').value.trim()
            };

            // Validaciones
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(formData.email)) {
                alert('Por favor ingrese un correo electrónico válido.');
                return;
            }

            if (!/^[0-9]{10,15}$/.test(formData.phone)) {
                alert('El teléfono debe contener entre 10 y 15 dígitos.');
                return;
            }

            // Envío real (descomenta para producción)
            /*
            try {
                const response = await fetch('submit_form.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formData)
                });
                
                if (!response.ok) throw new Error('Error en el envío');
                alert('¡Mensaje enviado con éxito!');
                this.reset();
            } catch (error) {
                console.error('Error:', error);
                alert('Error al enviar el mensaje. Por favor intente nuevamente.');
            }
            */
            
            // Simulación de envío (solo para desarrollo)
            console.log('Datos del formulario:', formData);
            alert('¡Mensaje enviado con éxito! (Simulación)');
            this.reset();
        });
    }

    // =============================================
    // 4. Efectos Adicionales
    // =============================================
    // Header con sombra al hacer scroll
    window.addEventListener('scroll', function() {
        const header = document.querySelector('.header');
        if (header) {
            header.style.boxShadow = window.scrollY > 50 ? '0 2px 10px rgba(0,0,0,0.1)' : 'none';
        }
    });

    // Smooth scrolling para enlaces internos
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
});