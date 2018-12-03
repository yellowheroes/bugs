<?php
namespace yellowheroes\projectname\system\mvc\views;

use yellowheroes\projectname\system\libs as libs;

$footer = new libs\BootWrap();
/* other is anything that comes after </body> element */
$other =<<<HEREDOC
<script>
    converse.initialize({
        bosh_service_url: 'https://conversejs.org/http-bind/', // Please use this connection manager only for testing purposes
        show_controlbox_by_default: true
        allow_contact_requests: true
    });
</script>
HEREDOC;
$footer->setFooter("Yellow Heroes", null); // copyright notice is appended by default
echo $footer->getFooter();