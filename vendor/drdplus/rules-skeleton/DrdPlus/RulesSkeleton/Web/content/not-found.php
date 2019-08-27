<?php declare(strict_types=1);
/** @var \DrdPlus\RulesSkeleton\Web\DebugContactsBody $debugContactsBody */
/** @var $request \DrdPlus\RulesSkeleton\Request */
?>
<h1>Kde nic tu nic 😢</h1>

<h2>Hod na smysly selhal</h2>
<div class="row">
  <div class="col">
    Ať hledáme, jak hledáme, tak na odkazu <span class="keyword"><?= htmlentities($request->getCurrentUrl()) ?></span>
    nic nenacházíme...👀
  </div>
</div>

<div class="row">
  <div class="col">
    Zkus opravit odkaz ve Tvém prohlížeči, nebo skoč na <a href="/">hlavní stránku</a>, nebo na
    <a href="https://www.drdplus.info">rozcestník</a>.
  </div>
</div>

<h2>Co když je to chyba?</h2>
<div class="row">
  <div class="col">
    Kde tě takový odkaz potkal? Možná by chtěl opravit!
  </div>
</div>
<div class="row">
  <div class="col">
      <?= $debugContactsBody->getValue() ?>
    My už tomu odkazu ukážeme cestu.
  </div>
</div>
