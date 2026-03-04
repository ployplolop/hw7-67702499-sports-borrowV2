    </div><!-- /.container -->
    <footer style="text-align:center; padding:20px; color:#95a5a6; font-size:.85rem;">
        &copy; <?= date('Y') ?> Sports Equipment Borrowing System
    </footer>

    <!-- Flash Alert System -->
    <?php
    if (!empty($_SESSION['flash'])):
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        SportAlert.<?= $flash['level'] ?>(
            '<?= addslashes($flash['title']) ?>',
            '<?= addslashes($flash['message']) ?>'
        );
    });
    </script>
    <?php endif; ?>
</body>
</html>
