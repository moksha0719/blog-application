<footer class="site-footer">
    <div class="footer-container">
        <div class="footer-brand">
            <span class="footer-brand-icon">B</span>
            <div>
                <h3>Blog<span>Space</span></h3>
                <p>A platform built for storytellers.</p>
            </div>
        </div>
        
        <div class="footer-links">
            <a href="index.php">Home</a>
            <a href="index.php#how-it-works">How It Works</a>
            <?php if (!isset($_SESSION["user_id"])): ?>
                <a href="register.php">Sign Up</a>
            <?php endif; ?>
        </div>
        
        <div class="footer-copy">
            © <?php echo date("Y"); ?> BlogSpace. All rights reserved.
        </div>
    </div>
</footer>

<script src="js/script.js"></script>

</body>
</html>