// Función mejorada para inicializar el carrusel
const initSwiper = () => {
    const swiper = new Swiper('.swiper-container', {
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        slidesPerView: 1,
        spaceBetween: 30
    });
    return swiper;
};

// Cargar estadísticas desde API
const loadStats = async () => {
    try {
        const response = await fetch('/api/estadisticas');
        const data = await response.json();
        
        document.getElementById('anos-experiencia').textContent = data.anos + '+';
        document.getElementById('casos-exitosos').textContent = data.casos + '+';
        document.getElementById('clientes-activos').textContent = data.clientes + '+';
    } catch (error) {
        console.error('Error al cargar estadísticas:', error);
    }
};

// Cargar testimonios desde API
const loadTestimonials = async (swiperInstance) => {
    try {
        const response = await fetch('/api/testimonios');
        const data = await response.json();
        const container = document.getElementById('testimonios-container');
        container.innerHTML = '';
        
        data.forEach(testimonio => {
            const slide = document.createElement('div');
            slide.className = 'swiper-slide';
            slide.innerHTML = `
                <blockquote>${testimonio.texto}</blockquote>
                <cite>- ${testimonio.autor}</cite>
            `;
            container.appendChild(slide);
        });
        
        swiperInstance.update();
    } catch (error) {
        console.error('Error al cargar testimonios:', error);
    }
};

// Cargar portafolio desde API
const loadPortfolio = async () => {
    try {
        const response = await fetch('/api/portafolio?limit=3');
        const data = await response.json();
        const container = document.getElementById('portafolio-container');
        container.innerHTML = '';
        
        data.forEach(item => {
            const portfolioItem = document.createElement('div');
            portfolioItem.className = 'portfolio-item';
            portfolioItem.innerHTML = `
                <div class="portfolio-img" style="background-image: url('${item.imagen}');"></div>
                <div class="portfolio-info">
                    <h3>${item.titulo}</h3>
                    <p>${item.descripcion}</p>
                </div>
            `;
            container.appendChild(portfolioItem);
        });
    } catch (error) {
        console.error('Error al cargar portafolio:', error);
    }
};

// Inicializar Google Analytics
const initGoogleAnalytics = () => {
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'UA-XXXXX-Y');
};

// Cuando el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', () => {
    const swiper = initSwiper();
    loadStats();
    loadTestimonials(swiper);
    loadPortfolio();
    initGoogleAnalytics();
});