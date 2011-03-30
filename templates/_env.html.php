<style type="text/css">
#env-info {
	width: 100%;
	padding-top: 0.5em;
	min-height: 10em; }
#env-info h3.info {
	line-height: 1em;
	padding-left: 1em; }
#env-info table.vars {
    margin-left: 1em;
    border:1px solid #ccc; border-collapse: collapse; background:white; width: 80%; }
#env-info table.vars tbody td, #env-info tbody th { vertical-align:top; ; }
#env-info table.vars thead th {
    background:#fefefe; text-align:left;
    font-weight:normal; font-size:11px; border:1px solid #ddd; }
#env-info table.vars tbody th { text-align:right; color:#666; }
#env-info table.vars td { font-family:monospace; }
</style>

<div id="env-info">
  <h3 class="info">Request Environment:</h2>
  <table class="vars">
    <thead>
      <tr>
        <th>Variable</th>
        <th>Value</th>
      </tr>
    </thead>
    <tbody>
        <?php
          uksort( $env, 'strnatcasecmp' );
          foreach( $env as $key => $val ):
        ?>
        <tr>
          <td><?php echo Prack_Utils::singleton()->escapeHTML( $key ); ?></td>
          <td class="code"><div><?php echo print_r( $val, true ); ?></div></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
  </table>
</div>