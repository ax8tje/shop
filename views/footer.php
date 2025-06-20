</div>
<footer class="footer">
    <div class="footer-container">
        <div class="footer-contact">
            <h3>Kontakt</h3>
            <p>Email: <a href="mailto:kontakt@moko.store">kontakt@moko.store</a></p>
            <p>Telefon: <a href="tel:+48123456789">+48 123 456 789</a></p>
        </div>
        <div class="footer-social">
            <h3>Znajdź nas</h3>
            <a href="#" aria-label="Facebook" class="social-link"><img src="../assets/img/facebook.png" alt="Facebook" /></a>
            <a href="#" aria-label="Instagram" class="social-link"><img src="../assets/img/instagram.png" alt="Instagram" /></a>
            <a href="#" aria-label="Pinterest" class="social-link"><img src="../assets/img/pinterest.png" alt="Pinterest" /></a>
        </div>
        <div class="footer-copy">
            <p>© 2025 moko.store. Wszelkie prawa zastrzeżone.</p>
        </div>
    </div>
</footer>
<?php if (!empty($pageScripts)):
    foreach ($pageScripts as $s): ?>
        <script src="<?= htmlspecialchars($s) ?>"></script>
    <?php endforeach; endif; ?>
</body>
</html>