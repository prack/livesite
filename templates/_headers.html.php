<style type="text/css">
#headers-info {
	background-repeat: repeat-x;
	width: 100%;
	padding-top: 0.5em;
	min-height: 10em; }
#headers-info h3.info {
	line-height: 1em;
	padding-left: 1em; }
#headers-info table.header {
    margin-left: 1em;
    margin-bottom: 1em;
    border:1px solid #ccc; border-collapse: collapse; background:white; width: 80%; }
#headers-info table.header tbody td, #headers-info tbody th { vertical-align:top; ; }
#headers-info table.header thead th {
    background:#fefefe; text-align:left;
    font-weight:normal; font-size:11px; border:1px solid #ddd; }
#headers-info table.header tbody th { text-align:right; color:#666; }
#headers-info table.header td { font-family:monospace; }
</style>

<div id="headers-info">
	<h3 class="info">Response Headers (final Content-Length will vary):</h3>
	<table class="header">
	  <thead>
	    <tr>
	      <th>Header</th>
	      <th>Value</th>
	    </tr>
	  </thead>
	  <tbody>
	      <?php
	        uksort( $headers, 'strnatcasecmp' );
	        foreach( $headers as $key => $val ):
	      ?>
	      <tr>
	        <td><?php echo Prack_Utils::singleton()->escapeHTML( $key ); ?></td>
	        <td class="code"><div><?php echo print_r( $val, true ); ?></div></td>
	      </tr>
	      <?php endforeach; ?>
	  </tbody>
	</table>
</div>