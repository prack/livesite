<h2 id="env-info">Request Information:</h2>
<table class="req">
  <thead>
    <tr>
      <th>Variable</th>
      <th>Value</th>
    </tr>
  </thead>
  <tbody>
      <?php
        $callback = create_function( '$k,$v', 'return Prb::Str( $k );' );
        $sorted   = $env->sortBy( $callback );
        foreach( $sorted->raw() as $key => $val ):
      ?>
      <tr>
        <td><?php echo Prack_Utils::singleton()->escapeHTML( Prb::Str( $key ) ); ?></td>
        <td class="code"><div><?php echo print_r( $val, true ); ?></div></td>
      </tr>
      <?php endforeach; ?>
  </tbody>
</table>
