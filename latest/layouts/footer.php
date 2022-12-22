        </div>
    </div>
    <footer class="pt-3 mt-4 text-muted border-top"> &copy; <?= date('Y') ?> </footer>
</div>

</main>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
<script>
    (function () {
        'use strict'
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.needs-validation');
        // Loop over them and prevent submission
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        })
    })();
    const alert = document.querySelectorAll('.alert');
    if (alert.length > 0) {
        const initAlert = bootstrap.Alert.getOrCreateInstance('#alert');
        setTimeout(() => {
            initAlert.close();
        }, 5000);
    }
</script>
</body>

</html>