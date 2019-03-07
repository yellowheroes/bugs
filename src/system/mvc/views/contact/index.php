<?php
namespace yellowheroes\bugs\system\mvc\views;

use yellowheroes\bugs\system\mvc\models as models;
use yellowheroes\bugs\system\libs as libs;

/**
 * card with contact details
 * public function card($title = null, $msg = null, $class = 'primary', $list = [], $links = [], $blank=false, $image = null)
 */

$links = ['mailto: admin@yellowheroes.com' => '<i class="fa fa-envelope-o" aria-hidden="true"></i> send us an e-mail',
        'https://github.com/yellowheroes' => '<i class="fa fa-github" aria-hidden="true"></i> find us on GitHub'
        ];
$footer = "We won't promise to come back to you, but we'll try...";
$contactCard = $bootWrap->card('Yellow Heroes', 'Want to get in touch?', 'light', null, $links, true, null, $footer); // set blank=true to open links in new tab
?>
<!-- use bootstrap grid system -->
<div class="row">
    <div class="col"><?php echo $contactCard; ?></div>
</div>