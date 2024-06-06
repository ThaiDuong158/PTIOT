<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelector('.sidebar-mini').click();
        document.querySelectorAll('.sidebar-width').forEach((sidebarWidth) => {
            sidebarWidth.classList.remove('sidebar-transition')
        })
    });
</script>