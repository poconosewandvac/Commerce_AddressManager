<?php

function getSnippetContent($filename) {
    $o = file_get_contents($filename);
    $o = str_replace('<?php','',$o);
    $o = str_replace('?>','',$o);
    $o = trim($o);
    return $o;
}

$snippets = array();
$snippets[0] = $modx->newObject('modSnippet');
$snippets[0]->fromArray(array(
    'id' => 0,
    'name' => 'AddressManager',
    'description' => 'Let customers manage their Commerce comAddress addresses.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/snippet.addressmanager.php'),
),'',true,true);

return $snippets;