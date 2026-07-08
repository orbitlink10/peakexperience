<script>
    (() => {
        try {
            if (window.sessionStorage.getItem('peak-page-transition-active') !== null) {
                document.documentElement.classList.add('is-page-transitioning');
            }
        } catch (error) {
            document.documentElement.classList.remove('is-page-transitioning');
        }
    })();
</script>
