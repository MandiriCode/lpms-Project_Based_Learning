</section>
<footer class="app-footer">LPMS · Mendukung pembelajaran berbasis proyek dan pengembangan kreativitas siswa</footer>
</main>
</div>
<script>
    document.addEventListener('click', function(e) {
        const open = e.target.closest('[data-open-modal]');
        if (open) {
            const d = document.getElementById(open.dataset.openModal);
            if (d) d.showModal();
        }
        const close = e.target.closest('[data-close-modal]');
        if (close) {
            const d = close.closest('dialog');
            if (d) d.close();
        }
    });
    document.querySelectorAll('dialog.app-modal').forEach(d => d.addEventListener('click', e => {
        if (e.target === d) d.close();
    }));
</script>
</body>

</html>