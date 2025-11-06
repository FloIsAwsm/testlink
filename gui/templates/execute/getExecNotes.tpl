{* 
TestLink Open Source Project - http://testlink.sourceforge.net/
@filesource getExecNotes.tpl
smarty template - used to show execution notes on execution feature
*}
<html>
<head>
</head>
<body>
{if $webeditorType == 'none'}
<div {$readonly} name="notes" cols="{$webeditorCfg.cols}" 
          rows="{$webeditorCfg.rows}" style="background:transparent;">
{$notes}
</div>
{else}
{$notes}
{/if}
</body>
</html>