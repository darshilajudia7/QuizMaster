(() => {
    const percentage = parseFloat(
        document.currentScript?.dataset.percentage ?? 0
    );

    function launchConfetti() {
        if (percentage < 50) return;

        const canvas = document.getElementById('confetti');
        const ctx = canvas.getContext('2d');
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        const colors = ['#4F46E5', '#7C3AED', '#10B981', '#FF6B4A'];
        const particles = Array.from({ length: 150 }, () => ({
            x: Math.random() * canvas.width,
            y: Math.random() * canvas.height - canvas.height,
            size: Math.random() * 6 + 4,
            color: colors[Math.floor(Math.random() * colors.length)],
            speed: Math.random() * 3 + 2,
            angle: Math.random() * 360,
        }));

        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            for (let i = particles.length - 1; i >= 0; i--) {
                const p = particles[i];
                p.y += p.speed;
                p.angle += 2;

                ctx.fillStyle = p.color;
                ctx.save();
                ctx.translate(p.x, p.y);
                ctx.rotate(p.angle * Math.PI / 180);
                ctx.fillRect(-p.size / 2, -p.size / 2, p.size, p.size);
                ctx.restore();

                if (p.y > canvas.height) particles.splice(i, 1);
            }

            if (particles.length > 0) requestAnimationFrame(animate);
        }

        animate();
    }

    window.addEventListener('load', () => setTimeout(launchConfetti, 400));

    window.addEventListener('resize', () => {
        const canvas = document.getElementById('confetti');
        if (canvas) {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }
    });
})();