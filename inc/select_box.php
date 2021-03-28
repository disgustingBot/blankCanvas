<?php
/*
=selectBox

This function generates a selectBox object

PARAMETROS:
$name => el nombre visible o "label" del select
$options => un vector del tipo:
array(
	[0] => array(
		'slug' => 'option_0_slug'
		'name' => 'option_0_name'
		'selected' => True
	),
	[1] => array(
		'slug' => 'option_1_slug'
		'name' => 'option_1_name'
	)
)
$empty_label => el nombre visible de la opcion de vaciar el select
$slug => el nombre invisible del select (para CSS) se concatena a selectBox, ejemplo:
$slug = 'MiSelect' resulta en la clase -> 'selectBoxMiSelect'
*/
function selectBox($name, $options = array(), $empty_label = 'Vaciar', $slug = false){
	if(!$slug){ $slug = sanitize_title($name); }
	// var_dump($options);
	$selected = array('name'=>'','selected'=>False);
	$selected = array_reduce($options,function($accu, $opt){
		if ($accu != False) return $accu;
		if (isset($opt['selected']) and $opt['selected'] == True) return $opt;
		return False;},
	False);

	// var_dump($selected);
	?>
	<div class="SelectBox <?php if ($selected['selected']) {echo 'alt';} ?> selectBox<?= $slug; ?>" tabindex="1" id="selectBox<?= $slug; ?>">
		<div class="selectBoxButton" onclick="altClassFromSelector('focus', '#selectBox<?= $slug; ?>')">
			<p class="selectBoxPlaceholder"><?= $name; ?></p>
			<p class="selectBoxCurrent" id="selectBoxCurrent<?= $slug; ?>"><?php if(isset($selected['name'])) echo $selected['name']; ?></p>
		</div>
		<div class="selectBoxList focus">
			<label for="nul<?= $slug; ?>" class="selectBoxOption" id="selectBoxOptionNul"><?= $empty_label; ?>
				<input
					class="selectBoxInput"
					id="nul<?= $slug; ?>"
					type="radio"
					name="<?= $slug; ?>"
					onclick="selectBoxControler('','#selectBox<?= $slug; ?>','#selectBoxCurrent<?= $slug; ?>')"
					value="0"
					<?php if(!isset($_GET[$slug])){ ?>
						checked
					<?php } ?>
				>
				<!-- <span class="checkmark"></span> -->
				<p class="colrOptP"></p>
			</label>


			<?php foreach ($options as $option) {
				$option['name'] = preg_replace('/\s+/', ' ', trim($option['name'])); ?>

				<label for="<?= $slug; ?>_<?= $option['slug']; ?>" class="selectBoxOption">
					<input
						class="selectBoxInput <?= $option['slug']; ?>"
						type="radio"
						id="<?= $slug; ?>_<?= $option['slug']; ?>"
						name="<?= $slug; ?>"
						onclick="selectBoxControler('<?= $option['name']; ?>', '#selectBox<?= $slug; ?>', '#selectBoxCurrent<?= $slug; ?>')"
						value="<?= $option['slug']; ?>"
						<?php if(isset($option['selected']) && $option['selected'] == True){ ?>
							checked
						<?php } ?>
						<?php
						if (isset($option['data'])) {
							foreach ($option['data'] as $key => $value) {
								echo "$key='$value'";
							}
						}
						?>
					>
					<!-- <span class="checkmark"></span> -->
					<p class="colrOptP"><?= $option['name']; ?></p>
				</label>


			<?php } ?>
		</div>
	</div>
<?php }
