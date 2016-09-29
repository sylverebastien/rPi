<div class="list-block">
  <ul>
    <li>
      <a href="#" onclick="envoi('nova');" id="nova" class="item-link list-button">Nova</a>
    </li>
    <li>
      <a href="#" onclick="envoi('brume');" id="brume" class="item-link list-button">Brume</a>
    </li>
    <li>
      <a href="#" onclick="envoi('jazz');" id="jazz" class="item-link list-button">Jazz</a>
    </li>
    <li>
      <a href="#" onclick="envoi('canut');" id="canut" class="item-link list-button">Canut</a>
    </li>
    <li>
      <a href="#" onclick="envoi('france-inter');" id="france-inter" class="item-link list-button">France inter</a>
    </li>
  </ul>
</div>

<div class="content-block" style="margin-bottom: 5px;">
  <div class="row">
    <div class="col-50">
      <a href="#" onclick="envoi('play');" id="play" class="button button-big button-green">Play</a>
    </div>
    <div class="col-50">
      <a href="#" onclick="envoi('stop');" id="pause" class="button button-big button-red">Stop</a>
    </div>
  </div>
</div>
<div class="content-block" style="margin-bottom: 5px;">
  <div class="row">
    <div class="col-50">
      <a href="#" onclick="envoi('precedent');" id="precedent" class="button button-big button-red">Précédent</a>
    </div>
    <div class="col-50">
      <a href="#" onclick="envoi('suivant');" id="suivant" class="button button-big button-green">Suivant</a>
    </div>
  </div>
</div>
<form id="my-form" style="margin: 35px 0;" class="list-block">
  <ul><li>
    <div class="item-content">
      <div class="item-inner">
        <div class="item-input">
          <div class="range-slider" >
            <input type="range"  min="-3500" max="1000" value="-2500" step="100" id="volume" name="slider">
          </div>
        </div>
      </div>
    </div>
  </li></ul>
</form>
<div style="margin: 25px 0;" class="content-block">
  <p><a href="#" id="serveur"  onClick="var val = $('#volume').val();envoi('vol '+val);" class="button save-storage-data">Changer volume</a></p>
</div>

<div class="content-block">
  <p id="reponse"></p>
</div>
<script type="text/javascript">
function envoi(id) {
  $("#reponse").load( "index.php?q=ajax&action=glad", { text : id } );
}
</script>
