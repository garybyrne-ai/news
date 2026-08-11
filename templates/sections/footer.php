<?php
$businessName = setting('business_name', 'Villa Andie');
$phone = setting('phone');
$email = setting('email');
$year  = date('Y');
?>
<footer class="site-footer">
  <div class="container">
    <div class="site-footer__hero">
      <p class="site-footer__logo"><span class="nav-logo__mark" aria-hidden="true"></span> <?= e($businessName) ?></p>
      <p class="site-footer__statement reveal" data-reveal>Let's plan<br>your escape.</p>
      <a class="btn btn--light btn--lg" data-magnetic href="#contact">Send an enquiry</a>
    </div>

    <nav class="site-footer__cols" aria-label="Footer">
      <div>
        <h3>The villa</h3>
        <ul>
          <li><a href="#services">Amenities</a></li>
          <li><a href="#why-us">Why Villa Andie</a></li>
          <li><a href="#living">Life at the villa</a></li>
          <li><a href="#faq">FAQ</a></li>
        </ul>
      </div>
      <div>
        <h3>Location</h3>
        <ul>
          <li><a href="#areas">Puerto del Carmen</a></li>
          <li><a href="#areas">Beaches nearby</a></li>
          <li><a href="#areas">Getting here</a></li>
        </ul>
      </div>
      <div>
        <h3>Plan</h3>
        <ul>
          <li><a href="#process">How it works</a></li>
          <li><a href="#contact">Availability</a></li>
          <li><a href="#contact">Contact</a></li>
        </ul>
      </div>
      <div>
        <h3>Reach us</h3>
        <ul>
          <?php if ($email !== ''): ?><li><a href="mailto:<?= e($email) ?>"><?= e($email) ?></a></li><?php endif; ?>
          <?php if ($phone !== ''): ?><li><a href="tel:<?= e(preg_replace('/\s+/', '', $phone)) ?>"><?= e($phone) ?></a></li><?php endif; ?>
          <?php if (setting('instagram') !== ''): ?><li><a href="<?= e(setting('instagram')) ?>" rel="noopener">Instagram</a></li><?php endif; ?>
          <?php if (setting('facebook') !== ''): ?><li><a href="<?= e(setting('facebook')) ?>" rel="noopener">Facebook</a></li><?php endif; ?>
          <li><a href="#contact">Enquiry form</a></li>
        </ul>
      </div>
    </nav>

    <div class="site-footer__legal">
      <p>© <?= $year ?> <?= e($businessName) ?> · <?= e(setting('locality')) ?>, <?= e(setting('region')) ?></p>
      <ul>
        <li><a href="<?= e(url('/privacy')) ?>">Privacy</a></li>
        <li><a href="<?= e(url('/cookies')) ?>">Cookies</a></li>
        <li><a href="<?= e(url('/terms')) ?>">Terms</a></li>
        <li><a href="<?= e(url('/accessibility')) ?>">Accessibility</a></li>
      </ul>
    </div>
  </div>
</footer>
