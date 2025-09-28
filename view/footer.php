<footer class='footer mt-auto py-3 bg-light'>
    <div class='container'>
        <span class='text-muted'>
            @copyright <?= date('d/m/Y') ?> </span>
    </div>
</footer>


<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3/dist/js/bootstrap.bundle.min.js'></script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const message = document.getElementById('message');
        if (message) {
            setTimeout(() => {
                message.classList.add('fade-out');

                setTimeout(() => {
                    message.style.display = 'none';
                }, 2000);
            }, 5000);

        }
    });
</script>
</body>

</html>
