        </main><!-- /.content-area -->

        <footer class="app-footer">
            &copy; <?= date('Y') ?> Sports Equipment Borrowing System
        </footer>

    </div><!-- /.main-area -->

    <!-- Flash Messages via Toast -->
    <?php
    if (!empty($_SESSION['flash'])):
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        $level = $flash['level'] === 'danger' ? 'error' : $flash['level'];
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        Toast.<?= $level ?>(
            '<?= addslashes($flash['title']) ?>',
            '<?= addslashes($flash['message']) ?>'
        );
    });
    </script>
    <?php endif; ?>
</body>
</html>
