document.addEventListener('DOMContentLoaded', function () {
    // Menú de hamburguesa
    // Selectores
    const burger = document.querySelector('.burger');
    const navLinks = document.querySelector('.nav-links');
    const navItems = document.querySelectorAll('.nav-links li');

    function toggleMenu() {
        // Alternar clase active en navLinks
        navLinks.classList.toggle('active');
        
        // Animación para el menu
        navItems.forEach((item, index) => {
            if (item.style.animation) {
                item.style.animation = '';
            } else {
                item.style.animation = `navLinkFade 0.5s ease forwards ${index / 7 + 0.3}s`;
            }
        });
        
        // Animación para botón de hamburguesa
        burger.classList.toggle('toggle');
    }

    burger.addEventListener('click', toggleMenu);

    // Cerrar el menu al hacer clic en un item
    navItems.forEach(item => {
        item.addEventListener('click', () => {
            if (navLinks.classList.contains('active')) {
                toggleMenu();
            }
        });
    });
    
    // Carrusel de testimonios
    const testimonialsContainer = document.querySelector('.testimonial-wrapper');
    const dotsContainer = document.querySelector('.carousel-dots');
    const prevButton = document.querySelector('.carousel-button.prev');
    const nextButton = document.querySelector('.carousel-button.next');

    let currentTestimonialIndex = 0;
    let testimonialsData = [];
    let autoRotateInterval;

    // Cargar testimonios desde JSON
    async function loadTestimonials() {
        try {
            const response = await fetch('testimonials.json');
            
            if (!response.ok) {
                throw new Error('No se pudo cargar el archivo JSON');
            }
            
            testimonialsData = await response.json();
            
            if (!Array.isArray(testimonialsData) || testimonialsData.length === 0) {
                throw new Error('El archivo JSON no contiene testimonios válidos');
            }

            displayTestimonials(testimonialsData);
            createDotsNavigation(testimonialsData);
            startAutoRotation();
            setupEventListeners();

        } catch (error) {
            console.error('Error al cargar testimonios:', error);
            testimonialsContainer.innerHTML = `
                <div class="testimonial active">
                    <p class="testimonial-text">"Los testimonios no están disponibles en este momento."</p>
                </div>
            `;
        }
    }

    // Mostrar testimonios en el carrusel
    function displayTestimonials(testimonials) {
        testimonialsContainer.innerHTML = '';

        testimonials.forEach((testimonial, index) => {
            const testimonialElement = document.createElement('div');
            testimonialElement.className = `testimonial ${index === 0 ? 'active' : ''}`;
            testimonialElement.id = `testimonial-${testimonial.id}`;
            testimonialElement.innerHTML = `
                <p class="testimonial-text">"${testimonial.text}"</p>
                <p class="testimonial-author">- ${testimonial.author}</p>
            `;

            testimonialsContainer.appendChild(testimonialElement);
        });
    }

    // Crear puntos de navegacion
    function createDotsNavigation(testimonials) {
        dotsContainer.innerHTML = '';
        
        testimonials.forEach((_, index) => {
            const dot = document.createElement('div');
            dot.className = `carousel-dot ${index === 0 ? 'active' : ''}`;
            dot.dataset.index = index;
            dotsContainer.appendChild(dot);
        });
    }

    // Cambiar al testimonio específico
    function goToTestimonial(index) {
        if (index < 0 || index >= testimonialsData.length) return;
        
        const testimonials = document.querySelectorAll('.testimonial');
        const dots = document.querySelectorAll('.carousel-dot');
        const currentTestimonial = testimonials[currentTestimonialIndex];
        const nextTestimonial = testimonials[index];
        
        // Determinar dirección de la animación
        const direction = index > currentTestimonialIndex ? 'right' : 'left';
        
        // Aplicar clases de animación
        currentTestimonial.classList.remove('active');
        currentTestimonial.classList.add(direction === 'right' ? 'slide-out-left' : 'slide-out-right');
        
        nextTestimonial.classList.add('active');
        nextTestimonial.classList.remove('slide-out-left', 'slide-out-right');
        
        // Actualizar puntos de navegación
        dots[currentTestimonialIndex].classList.remove('active');
        dots[index].classList.add('active');
        
        currentTestimonialIndex = index;
        
        // Reiniciar el intervalo de rotación automática
        resetAutoRotation();
        
        // Limpiar clases de animación después de que termine
        setTimeout(() => {
            currentTestimonial.classList.remove('slide-out-left', 'slide-out-right');
        }, 500);
    }

    // Rotación automática de testimonios
    function startAutoRotation() {
        autoRotateInterval = setInterval(() => {
            const nextIndex = (currentTestimonialIndex + 1) % testimonialsData.length;
            goToTestimonial(nextIndex);
        }, 5000);
    }

    // Reiniciar la rotacion
    function resetAutoRotation() {
        clearInterval(autoRotateInterval);
        startAutoRotation();
    }

    // Configurar event listeners
    function setupEventListeners() {
        // Botones de navegacion
        prevButton.addEventListener('click', () => {
            const prevIndex = (currentTestimonialIndex - 1 + testimonialsData.length) % testimonialsData.length;
            goToTestimonial(prevIndex);
        });
        
        nextButton.addEventListener('click', () => {
            const nextIndex = (currentTestimonialIndex + 1) % testimonialsData.length;
            goToTestimonial(nextIndex);
        });
        
        // Puntos de navegacion
        const dots = document.querySelectorAll('.carousel-dot');
        dots.forEach(dot => {
            dot.addEventListener('click', () => {
                const dotIndex = parseInt(dot.dataset.index);
                if (dotIndex !== currentTestimonialIndex) {
                    goToTestimonial(dotIndex);
                }
            });
        });
        
        // Pausar al pasar el mouse
        testimonialsContainer.addEventListener('mouseenter', () => {
            clearInterval(autoRotateInterval);
        });
        
        testimonialsContainer.addEventListener('mouseleave', () => {
            startAutoRotation();
        });
    }

    // Formulario de contacto
    const contactForm = document.getElementById('contact-form');

    if (contactForm) {
        contactForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const service = document.getElementById('service').value;
            const message = document.getElementById('message').value.trim();

            if (!name || !email || !phone || service === '' || !message) {
                alert('Por favor complete todos los campos del formulario.');
                return;
            }

            // Validar el correo
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                alert('Por favor ingrese un correo electrónico válido.');
                return;
            }

            // Enviar el formulario con fetch
            const formData = new FormData(contactForm);

            fetch('submit.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la red');
                    }
                    return response.text();
                })
                .then(data => {
                    alert('Gracias por su mensaje. Nos pondremos en contacto pronto.');
                    contactForm.reset();
                    window.location.href = 'index.html?success=1#contacto';
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Hubo un error al enviar el formulario. Por favor intente nuevamente.');
                });
        });
    }

    // Efecto de scroll para las secciones
    window.addEventListener('scroll', function () {
        const header = document.querySelector('.header');
        if (window.scrollY > 100) {
            header.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.2)';
        } else {
            header.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
        }
    });
   
    // Manejo del formulario de login 
    document.getElementById('admin-login-form')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const username = formData.get('username');
        const password = formData.get('password');
        
        fetch('php/login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'dashboard.php';
            } else {
                alert('Acceso denegado. Verifique sus credenciales.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al conectar con el servidor');
        });
    });
    
    // Cargar los testimonios al iniciar
    loadTestimonials();
});