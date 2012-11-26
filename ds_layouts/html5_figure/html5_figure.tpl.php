<figure class="<?php print $classes;?> clearfix">
  <?php if ($media): print $media; endif; ?>

  <?php if ($title): ?>
    <h2><?php print $title; ?></h2>
  <?php endif; ?>

  <?php if ($caption): ?>
    <figcaption><?php print ($caption); ?></figcaption>
  <?php endif; ?>
</figure>