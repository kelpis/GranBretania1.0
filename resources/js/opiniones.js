document.addEventListener('DOMContentLoaded', () => {
    const track = document.getElementById('opinionesTrack');
    if (!track) return;

    const slides = document.querySelectorAll('[data-opinion-slide]');
    const dots = document.querySelectorAll('[data-opinion-dot]');
    const total = slides.length;
    let index = 0;
    let intervalId = null;

    function goToSlide(i) {
        index = (i + total) % total;
        track.style.transform = `translateX(-${index * 100}%)`;

        dots.forEach((dot, j) => {
            if (j === index) {
                dot.classList.add('bg-azul');
                dot.classList.remove('bg-beige2');
            } else {
                dot.classList.add('bg-beige2');
                dot.classList.remove('bg-azul');
            }
        });
    }

    function startAuto() {
        intervalId = setInterval(() => {
            goToSlide(index + 1);
        }, 10000); // 10s per slide
    }

    function stopAuto() {
        if (intervalId) clearInterval(intervalId);
    }

    dots.forEach((dot, i) => {
        dot.addEventListener('click', () => {
            stopAuto();
            goToSlide(i);
            startAuto();
        });
    });

    goToSlide(0);
    startAuto();
});
