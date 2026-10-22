<?php if(!empty($_SESSION['flash'])): $f=$_SESSION['flash']; unset($_SESSION['flash']); ?><div class="alert <?=e($f['type'])?>"><?=e($f['message'])?></div><?php endif; ?>
