<footer class="site-footer">
    <div class="footer-container">
        <div class="footer-brand">
            <span class="footer-brand-icon"><i class="fas fa-blog"></i></span>
            <div>
                <h3>Blog<span>Space</span></h3>
                <p><i class="fas fa-quote-left"></i> A platform built for storytellers.</p>
            </div>
        </div>
        
        <div class="footer-links">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
            <a href="index.php#how-it-works"><i class="fas fa-info-circle"></i> How It Works</a>
            <?php if (!isset($_SESSION["user_id"])): ?>
                <a href="register.php"><i class="fas fa-user-plus"></i> Sign Up</a>
            <?php endif; ?>
        </div>
        
        <div class="footer-copy">
            <i class="far fa-copyright"></i> <?php echo date("Y"); ?> BlogSpace. All rights reserved.
        </div>
    </div>
</footer>

<script src="js/script.js"></script>

</body>
</html>