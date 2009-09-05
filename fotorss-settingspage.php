<div class="wrap">
	<h2>fotoRss Configurare</h2>

	<form method="post">
		<table class="form-table">
			<tr valign="top">
				<th scope="row">Afisare</th>
				<td>
					<select name="fotoRss_num_items" id="fotoRss_num_items">
						<?php for ($i=1; $i<=20; $i++) { ?>
							<option <?php if ($settings['num_items'] == $i) { echo 'selected'; } ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>	
						<?php } ?>
					</select>
					<select name="fotoRss_type" id="fotoRss_type">
						<option <?php if($settings['type'] == 'user') { echo 'selected'; } ?> value="user">user</option>
					<!--	<option <?php if($settings['type'] == 'set') { echo 'selected'; } ?> value="set">set</option>
						<option <?php if($settings['type'] == 'favorite') { echo 'selected'; } ?> value="favorite">favorite</option>
						<option <?php if($settings['type'] == 'group') { echo 'selected'; } ?> value="group">group</option>-->
						<option <?php if($settings['type'] == 'public') { echo 'selected'; } ?> value="public">public</option>
					</select>
					photos.
				</td> 
			</tr>
			<tr valign="top" id="userid">
				<th scope="row" id="userid_label">Utilizator</th>
				<td><input name="fotoRss_id" type="text" id="fotoRss_id" value="<?php echo $settings['id']; ?>" size="20" />
				</td>
			</tr>
		
			<tr valign="top" id="tags">
				<th scope="row">Tag-uri (optional)</th>
				<td><input name="fotoRss_tags" type="text" id="fotoRss_tags" value="<?php echo $settings['tags']; ?>" size="40" /> separate prin virgula</p>
			</tr>
			<tr valign="top">
				<th scope="row">Constructor HTML</th>
				<td>
					<table>
						<tr>
							<td colspan="2" valign="top" style="border-width: 0px;">
								<label for="fotoRss_before_list">Inainte de Lista:</label><br/><input name="fotoRss_before_list" type="text" id="fotoRss_before_list" value="<?php echo htmlspecialchars(stripslashes($settings['before_list'])); ?>" style="width:400px;" />
							</td>
						</tr>
						<tr>
							<td valign="top" style="border-width: 0px;">
								<label for="fotoRss_html">Cod Poza HTML:</label><br/> <textarea name="fotoRss_html" type="text" id="fotoRss_html" style="width:400px;" rows="10"><?php echo htmlspecialchars(stripslashes($settings['html'])); ?></textarea>
							</td>
							<td valign="top" style="border-width: 0px;">
								<div>
									<h4>"Cod Poza HTML" metatag-uri posibile:</h4>
									<ul>
										<li><code>%fotorss_page%</code></li>
										<li><code>%title%</code></li>
										<li><code>%image_square%</code></li>
									
										<li><code>%image_thumbnail%</code></li>
										<li><code>%image_medium%</code></li>
										
									</ul>
								</div>
							</td>
						</tr>
						<tr>
							<td valign="top" colspan="2" style="border-width: 0px;">
								<label for="fotoRss_after_list">Dupa lista:</label><br/> <input name="fotoRss_after_list" type="text" id="fotoRss_after_list" value="<?php echo htmlspecialchars(stripslashes($settings['after_list'])); ?>" style="width:400px;" />
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>

		<div class="submit">
			<input type="submit" name="reset_fotoRss_settings" value="<?php _e('Reseteaza Setarile') ?>" />
			<input type="submit" name="save_fotoRss_settings" value="<?php _e('Salveaza Setari') ?>" />
		</div>
		<script>
			(function() {
				var $ = jQuery;
				$(document).ready(function(){
					function uiChange() {
						$("#tags, #userid").hide();
						var sel = $("#fotoRss_type").val();
						if (sel == "set") {
							$("#set").show();
						}
						if (sel.match(/(user|public)/)) {
							$("#tags").show();
						}
						if (sel.match(/(user|favorite|set)/)) {
							$("#userid").show();
							$("#userid_label").text(sel=="group"?"Group ID":"User ID");
						}
						
					}
					$("#fotoRss_type").change(uiChange);
					
					uiChange();
					
				});
			})();
		</script>
	</form>
</div>