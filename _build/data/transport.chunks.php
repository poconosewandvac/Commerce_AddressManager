<?php

function getChunkContent($filename) {
    $o = file_get_contents($filename);
    return $o;
}

$chunks = array();

$chunks[0] = $modx->newObject('modChunk');
$chunks[0]->fromArray(array(
    'id' => 0,
    'name' => 'AddressManagerEdit',
    'description' => 'Default input editing/adding form for Commerce_AddressManager.',
    'snippet' => getChunkContent($sources['source_core'].'/elements/chunks/chunk.addressmanageredit.tpl'),
),'',true,true);

$chunks[1] = $modx->newObject('modChunk');
$chunks[1]->fromArray(array(
    'id' => 0,
    'name' => 'AddressManagerWrap',
    'description' => 'Default wrapper for Commerce_AddressManager.',
    'snippet' => getChunkContent($sources['source_core'].'/elements/chunks/chunk.addressmanagerwrap.tpl'),
),'',true,true);

$chunks[2] = $modx->newObject('modChunk');
$chunks[2]->fromArray(array(
    'id' => 0,
    'name' => 'AddressManagerRow',
    'description' => 'Default row for Commerce_AddressManager.',
    'snippet' => getChunkContent($sources['source_core'].'/elements/chunks/chunk.addressmanagerrow.tpl'),
),'',true,true);

$chunks[3] = $modx->newObject('modChunk');
$chunks[3]->fromArray(array(
    'id' => 0,
    'name' => 'AddressManagerError',
    'description' => 'Default template for displaying address validation errors.',
    'snippet' => getChunkContent($sources['source_core'].'/elements/chunks/chunk.addressmanagererror.tpl'),
),'',true,true);

return $chunks;