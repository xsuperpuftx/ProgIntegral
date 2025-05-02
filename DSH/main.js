
document.addEventListener('DOMContentLoaded', function () {
    // Menú hamburguesa
    // Selectores
    const burger = document.querySelector('.burger');
    const navLinks = document.querySelector('.nav-links');
    const navItems = document.querySelectorAll('.nav-links li');

    // Función para alternar el menu
    function toggleMenu() {
        // Alternar clase active en navLinks
        navLinks.classList.toggle('active');
        
        // Animacion para los items del menu
        navItems.forEach((item, index) => {
            if (item.style.animation) {
                item.style.animation = '';
            } else {
                item.style.animation = `navLinkFade 0.5s ease forwards ${index / 7 + 0.3}s`;
            }
        });
        
        // Animacion para el boton hamburguesa
        burger.classList.toggle('toggle');
    }

 
    burger.addEventListener('click', toggleMenu);

    navItems.forEach(item => {
        item.addEventListener('click', () => {
            if (navLinks.classList.contains('active')) {
                toggleMenu();
            }
        });
    });
    
    // Carrusel de testimonios 
    const testimonialsContainer = document.querySelector('.testimonials-carousel');

    // cargar testimonios desde API
    async function loadTestimonials() {
        try {

            const testimonials = [{
                    id: 1,
                    text: "Excelente servicio profesional. Resolvieron mi caso de manera rápida y eficiente.",
                    author: "Juan Pérez"
                },
                {
                    id: 2,
                    text: "Muy satisfecho con el asesoramiento recibido. Los recomiendo totalmente.",
                    author: "María González"
                },
                {
                    id: 3,
                    text: "El equipo de DSH Consultores demostró gran conocimiento y profesionalismo.",
                    author: "Carlos Rodríguez"
                }
            ];

            displayTestimonials(testimonials);
            startTestimonialRotation(testimonials);

        } catch (error) {
            console.error('Error al cargar testimonios:', error);
            testimonialsContainer.innerHTML = `
                <div class="testimonial">
                    <p class="testimonial-text">"Los testimonios no están disponibles en este momento."</p>
                </div>
            `;
        }
    }

    // Mostrar testimonios en el carrusel
    function displayTestimonials(testimonials) {
        testimonialsContainer.innerHTML = '';

        testimonials.forEach(testimonial => {
            const testimonialElement = document.createElement('div');
            testimonialElement.className = 'testimonial';
            testimonialElement.id = `testimonial-${testimonial.id}`;
            testimonialElement.innerHTML = `
                <p class="testimonial-text">"${testimonial.text}"</p>
                <p class="testimonial-author">- ${testimonial.author}</p>
            `;

            testimonialsContainer.appendChild(testimonialElement);
        });
    }

    // Rotacion de testimonios
    function startTestimonialRotation(testimonials) {
        let currentIndex = 0;

        setInterval(() => {
            currentIndex = (currentIndex + 1) % testimonials.length;
            const nextTestimonial = testimonials[currentIndex];

            testimonialsContainer.innerHTML = `
                <div class="testimonial">
                    <p class="testimonial-text">"${nextTestimonial.text}"</p>
                    <p class="testimonial-author">- ${nextTestimonial.author}</p>
                </div>
            `;
        }, 5000); // Cambia cada 5 segundos
    }

    // formulario de contacto
    const contactForm = document.getElementById('contact-form');

    if (contactForm) {
        contactForm.addEventListener('submit', function (e) {
            e.preventDefault();

            // validacion basica
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const service = document.getElementById('service').value;
            const message = document.getElementById('message').value.trim();

            if (!name || !email || !phone || service === '' || !message) {
                alert('Por favor complete todos los campos del formulario.');
                return;
            }

            // validacion de email simple
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

    // Cargar los testimonios al iniciar
    loadTestimonials();

    // Efecto de scroll para las secciones
    window.addEventListener('scroll', function () {
        const header = document.querySelector('.header');
        if (window.scrollY > 100) {
            header.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.2)';
        } else {
            header.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
        }
    });

   // Manejo del formulario de login administrativo
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
});