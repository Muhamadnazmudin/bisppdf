</div> <!-- end wrapper -->

<footer class="bg-white border-top mt-5 py-4">
    <div class="container text-center small text-muted">
        © <?= date('Y') ?> 
        <strong>BISP PDF Tools</strong> · 
        By 
        <a href="https://www.profilsaya.my.id" 
           target="_blank" 
           rel="noopener noreferrer"
           class="footer-link">
           User Kagura
        </a>
    </div>
</footer>

<style>
.footer-link {
    color: #dc3545;
    font-weight: 600;
    text-decoration: none;
    transition: 0.2s ease;
}

.footer-link:hover {
    text-decoration: underline;
    opacity: 0.8;
}
</style>


<!-- JS -->
<script src="<?= base_url('assets/sbadmin2/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/sbadmin2/js/sb-admin-2.min.js') ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
pdfjsLib.GlobalWorkerOptions.workerSrc =
  "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js";
</script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
</body>
</html>
