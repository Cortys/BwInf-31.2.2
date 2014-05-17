<div id="container">
	<canvas id="landscape" width="750" height="525"></canvas>
</div>
<div id="side">
	<table id="sideTable">
	<tr class="start">
		<td><label for="robotNum">Roboteranzahl:</label></td>
		<td colspan=""><input type="text" name="robotNum" id="robotNum" value="100" maxlength="3" class="l" title="Aus Performancegr&uuml;nden sind maximal 200 Roboter m&ouml;glich." /></td>
		<td>St&uuml;ck</td>
	</tr>
	<tr class="start">
		<td><label for="fieldSize">Feldgr&ouml;&szlig;e:</label></td>
		<td colspan="2"><select name="fieldSize" id="fieldSize">
				<option value="1">Klein (400x400)</option>
				<option value="2" selected="1">Normal (750x525)</option>
				<option value="3">Gro&szlig; (1500x1050)</option>
			</select></td>
	</tr>
	<tr class="start">
		<td><label for="robotView">Sichtweite:</label></td>
		<td><input type="text" id="robotView" name="robotView" value="200" maxlength="3" class="l" /></td>
		<td>Meter</td>
	</tr>
	<tr class="start">
		<td><label for="distribution">Streuung:</label></td>
		<td colspan="2"><div id="distribution" name="distribution" title="Je h&ouml;her die Streuung, desto weiter fallen die Roboter tendenziell voneinander entfernt."></div></td>
	</tr>
	<tr class="start">
		<td><label for="fallDuration">Falldauer:</label></td>
		<td><input type="text" name="fallDuration" id="fallDuration" value="5" maxlength="2" class="l" title="" /></td>
		<td>Sek.</td>
	</tr>
	<tr class="startButton">
		<td colspan="3" style="padding-bottom: 10px;"><input type="button" id="start" value="Start" class="l" /></td>
	</tr>
	<tr class="stop">
		<td colspan="3" style="padding-bottom: 10px;"><input type="button" id="stop" value="Stop" class="l" /></td>
	</tr> 
	<tr>
		<td colspan="3" class="trenner">Darstellung</td>
	</tr>
	<tr>
		<td colspan="3">
			<div class="metaBox" id="speedBox">
				<span>Geschwindigkeit</span>
				<div id="speed"></div>
				<img src="img/speedLabel.png" alt="" />
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="3"><input type="radio" value="0" name="showMovements" id="showMovements0" checked="1" /> <label for="showMovements0">Nur Roboter zeigen</label></td>
	</tr>
	<tr>
		<td colspan="3"><input type="radio" value="1" name="showMovements" id="showMovements1" /> <label for="showMovements1">Bewegungskurven zeigen</label></td>
	</tr>
	<tr>
		<td colspan="3"><input type="radio" value="2" name="showMovements" id="showMovements2" /> <label for="showMovements2">Umkreis zeigen</label></td>
	</tr>
	</table>
</div>
<div id="meta">
	<img src="img/smallLoad.gif" alt="" /> Laden...
</div>