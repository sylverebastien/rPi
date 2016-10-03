<!-- Text inputs -->
<div class="list-block">
  <ul>
    <li>
      <div class="item-content">
        <div class="item-inner">
          <div class="item-title label">Gestion automatisée chauffage</div>
          <div class="item-input">
            <label class="label-switch">
              <input id="chauffage" class="autotools" type="checkbox" <?= $states['auto-chauffage'];?>>
              <div class="checkbox"></div>
            </label>
          </div>
        </div>
      </div>
    </li>
    <li>
      <div class="item-content">
        <div class="item-inner">
          <div class="item-title label">Gestion automatisée réveil</div>
          <div class="item-input">
            <label class="label-switch">
              <input id="reveil" class="autotools" type="checkbox" <?= $states['reveil'];?>>
              <div class="checkbox"></div>
            </label>
          </div>
        </div>
      </div>
    </li>
    <li>
      <div class="item-content">
        <div class="item-inner">
          <div class="item-title label">Gestion automatisée alarme</div>
          <div class="item-input">
            <label class="label-switch">
              <input id="alarme" class="autotools" type="checkbox" <?= $states['alarme'];?>>
              <div class="checkbox"></div>
            </label>
          </div>
        </div>
      </div>
    </li>
  </ul>
</div>
<div class="list-block">
  <ul>
    <li class="item-content">
      <div class="item-media"><i class="stats pe-7s-light"></i></div>
      <div class="item-inner">
        <div class="item-title">Lampe #1</div>
        <div class="item-after"><?= $nb['lampe1']?></div>
      </div>
    </li>
    <li class="item-content">
      <div class="item-media"><i class="stats pe-7s-light"></i></div>
      <div class="item-inner">
        <div class="item-title">Lampe #2</div>
        <div class="item-after"><?php echo $nb['lampe2']?></div>
      </div>
    </li>
    <li class="item-content">
      <div class="item-media"><i class="stats pe-7s-light"></i></div>
      <div class="item-inner">
        <div class="item-title">Lampe #3</div>
        <div class="item-after"><?php echo $nb['lampe3']?></div>
      </div>
    </li>
    <li class="item-content">
      <div class="item-media"><i class="stats pe-7s-light"></i></div>
      <div class="item-inner">
        <div class="item-title">Lampe #4</div>
        <div class="item-after"><?php echo $nb['lampe4']?></div>
      </div>
    </li>
  </li>
</ul>
<div class="list-block">
  <ul>
    <li class="item-content">
      <div class="item-media"><i class="stats pe-7s-bluetooth"></i></div>
      <div class="item-inner">
        <div class="item-title">BT</div>
        <div class="item-after"><?php echo $nb['bt']?></div>
      </div>
    </li>
    <li class="item-content">
      <div class="item-media"><i class="stats pe-7s-music"></i></div>
      <div class="item-inner">
        <div class="item-title">HP</div>
        <div class="item-after"><?php echo $nb['hp']?></div>
      </div>
    </li>
    <li class="item-content">
      <div class="item-media"><i class="stats pe-7s-network"></i></div>
      <div class="item-inner">
        <div class="item-title">PC</div>
        <div class="item-after"><?php echo $nb['pc']?></div>
      </div>
    </li>
    <li class="item-content">
      <div class="item-media"><i class="stats pe-7s-server"></i></div>
      <div class="item-inner">
        <div class="item-title">Reboot serveur</div>
        <div class="item-after"><?php echo $nb['serveur']?></div>
      </div>
    </li>
  </ul>
  <div class="list-block-label">Statistiques débutées le 9 Février 2015.</div>
  <ul>
    <li class="item-content">
      <div class="item-media"><i class="stats pe-7s-stopwatch"></i></div>
      <div class="item-inner">
        <div class="item-title">Dernier Reboot</div>
        <div class="item-after">Le <?php echo $nb['datereboot'];?></div>
      </div>
    </li>
    <li>
      <div class="item-content">
        <div class="item-inner">
          <div class="item-title label">Reboot</div>
          <div class="item-input">
            <label class="label-switch" id="rebootlabel">
              <input id="serveur" type="checkbox" >
              <div class="checkbox"></div>
            </label>
          </div>
        </div>
      </div>
    </li>
  </ul>
</div>

<script type="text/javascript">
function post(id){
  var idpdf = id;
  if ($('#'+id).is(':checked')) {
    var val = "1";
  }
  else {
    var val = "0";
  }
  $.post( "index.php?q=ajax&action="+id+"", { val: val } );
}
$("#chauffage").change(function() {
  post('chauffage');
});
$("#reveil").change(function() {
  post('reveil');
});
$("#alarme").change(function() {
  post('alarme');
});
$("#serveur").change(function() {
  post('serveur');
});
</script>
